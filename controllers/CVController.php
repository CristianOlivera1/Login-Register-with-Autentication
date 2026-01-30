<?php

require_once __DIR__ . '/../src/CV.php';

class CVController
{
    private $cvModel;

    public function __construct()
    {
        $this->cvModel = new CV();
    }

    public function getTemplates()
    {
        header('Content-Type: application/json');

        try {
            $templates = $this->cvModel->getAllTemplates();
            echo json_encode([
                'success' => true,
                'templates' => $templates
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar plantillas'
            ]);
        }
    }

    public function generateCV()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['json_data']) || !isset($input['template_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'JSON data and template ID are required']);
            return;
        }

        $jsonData = $input['json_data'];
        $templateId = $input['template_id'];
        $isAuthenticated = isset($_SESSION['user_id']) || (isset($input['user_id']) && !empty($input['user_id']));

        // Validar JSON
        if (!$this->isValidJSON($jsonData)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Formato JSON inválido']);
            return;
        }

        try {
            if ($isAuthenticated) {
                session_start();
                $userId = $_SESSION['user_id'];

                // Generar slug único
                $slug = $this->generateSlug($jsonData);

                // Guardar CV en base de datos
                $cvId = $this->cvModel->createCV([
                    'user_id' => $userId,
                    'template_id' => $templateId,
                    'cv_data' => $jsonData,
                    'original_json_input' => json_encode($jsonData),
                    'slug' => $slug,
                    'title' => $jsonData['basics']['name'] ?? 'Mi CV'
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => 'CV generado exitosamente',
                    'cv_id' => $cvId,
                    'public_url' => "/cv/{$slug}",
                    'cv_data' => $jsonData
                ]);
            } else {
                // Para usuarios no autenticados, solo devolver los datos procesados
                echo json_encode([
                    'success' => true,
                    'message' => 'CV generado temporalmente',
                    'cv_data' => $jsonData,
                    'note' => 'Regístrate para guardar tu CV permanentemente'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error generating CV: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al generar CV'
            ]);
        }
    }

    public function getPublicCV($slug)
    {
        try {
            error_log("getPublicCV called with slug: " . $slug);
            
            $cv = $this->cvModel->getCVBySlug($slug);

            if (!$cv) {
                error_log("CV not found for slug: " . $slug);
                
                ob_start();
                include __DIR__ . '/../views/404.php';
                $content = ob_get_clean();
                
                include __DIR__ . '/../views/layouts/main.php';
                exit;
            }

            error_log("CV found - ID: " . $cv['id'] . ", Title: " . ($cv['title'] ?? 'N/A'));

            $this->cvModel->incrementViewCount($cv['id']);

            $pageTitle = $cv['title'] ?? 'CV';

            // Renderizar con layout principal
            ob_start();
            include __DIR__ . '/../views/public-cv/public-cv.php';
            $content = ob_get_clean();

            // Verificar si es una request AJAX
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo $content;
                exit;
            }

            include __DIR__ . '/../views/layouts/main.php';
        } catch (Exception $e) {
            error_log('Error loading public CV: ' . $e->getMessage());
            http_response_code(404);
            include __DIR__ . '/../views/404.php';
        }
    }

    public function getUserCV()
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authentication required']);
            return;
        }

        try {
            // Buscar el CV del usuario
            $cv = $this->cvModel->getUserCVByUserId($_SESSION['user_id']);

            if ($cv) {
                echo json_encode([
                    'success' => true,
                    'has_cv' => true,
                    'cv' => [
                        'id' => $cv['id'],
                        'title' => $cv['title'],
                        'slug' => $cv['slug'],
                        'cv_data' => $cv['cv_data'],
                        'template_id' => $cv['template_id'],
                        'created_at' => $cv['created_at'],
                        'updated_at' => $cv['updated_at']
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'has_cv' => false
                ]);
            }
        } catch (Exception $e) {
            error_log('Get User CV Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener CV del usuario'
            ]);
        }
    }

    public function saveCV()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Verificar autenticación
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authentication required']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['cv_data'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'CV data is required']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Verificar si el usuario ya tiene un CV
            $existingCV = $this->cvModel->getUserCVByUserId($userId);

            $cvData = [
                'user_id' => $userId,
                'template_id' => $input['template_id'] ?? null,
                'cv_data' => $input['cv_data'],
                'original_json_input' => json_encode($input['cv_data']),
                'title' => $input['cv_data']['basics']['name'] ?? 'Mi CV'
            ];

            if ($existingCV) {
                // UPDATE: El usuario ya tiene un CV
                // Verificar si el nombre cambió para actualizar el slug
                $newName = $input['cv_data']['basics']['name'] ?? '';
                $currentSlug = $existingCV['slug'];
                $newSlug = null;
                
                // Generar nuevo slug basado en el nombre actual
                $expectedSlug = $this->generateSlug($newName);
                
                // Si el slug actual no coincide con el esperado, actualizar
                if (!empty($newName) && strpos($currentSlug, $expectedSlug) !== 0) {
                    $newSlug = $this->generateUniqueSlug($expectedSlug);
                }
                
                $success = $this->cvModel->updateCV($existingCV['id'], $cvData, $newSlug);

                if ($success) {
                    echo json_encode([
                        'success' => true,
                        'cv_id' => $existingCV['id'],
                        'slug' => $newSlug ?? $existingCV['slug'],
                        'message' => 'CV actualizado exitosamente',
                        'action' => 'updated'
                    ]);
                } else {
                    throw new Exception('Failed to update CV');
                }
            } else {
                // INSERT: El usuario no tiene CV, crear uno nuevo
                $cvData['slug'] = $this->generateUniqueSlug($this->generateSlug($input['cv_data']['basics']['name'] ?? 'mi-cv'));

                $cvId = $this->cvModel->createCV($cvData);

                if ($cvId) {
                    echo json_encode([
                        'success' => true,
                        'cv_id' => $cvId,
                        'slug' => $cvData['slug'],
                        'message' => 'CV creado exitosamente',
                        'action' => 'created'
                    ]);
                } else {
                    throw new Exception('Failed to create CV');
                }
            }
        } catch (Exception $e) {
            error_log('Save CV Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar CV'
            ]);
        }
    }

    public function saveTitle()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authentication required']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['title'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Title is required']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];
            $title = trim($input['title']);

            // Verificar si el usuario tiene un CV
            $existingCV = $this->cvModel->getUserCVByUserId($userId);

            if ($existingCV) {
                // Actualizar solo el título
                $success = $this->cvModel->updateCVTitle($existingCV['id'], $title);

                if ($success) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Título actualizado exitosamente'
                    ]);
                } else {
                    throw new Exception('Failed to update title');
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No CV found to update'
                ]);
            }
        } catch (Exception $e) {
            error_log('Save Title Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar título'
            ]);
        }
    }

    private function generateSlug($name)
    {
        // Normalizar y remover acentos/tildes
        $slug = $this->removeAccents($name);
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    private function removeAccents($string)
    {
        $accents = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N',
            'ü' => 'u', 'Ü' => 'U',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O', 'Ü' => 'U',
            'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U',
            'ç' => 'c', 'Ç' => 'C'
        ];
        return strtr($string, $accents);
    }

    private function generateUniqueSlug($baseSlug)
    {
        $slug = $baseSlug;
        $counter = 1;

        while ($this->cvModel->slugExists($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getExampleCV()
    {
        header('Content-Type: application/json');

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/data/example-cv.json';

        if (!file_exists($filePath)) {
            echo json_encode(['success' => false, 'message' => 'Archivo no encontrado']);
            return;
        }

        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        // Si el usuario está autenticado, personalizar la plantilla con sus datos
        session_start();
        if (isset($_SESSION['user_id'])) {
            // Obtener datos del usuario
            require_once __DIR__ . '/../config/database.php';
            $db = new Database();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("SELECT firstName, lastName, email, avatar FROM users WHERE id = ?");
            $stmt->bind_param("s", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $fullName = trim(($user['firstName'] ?? '') . ' ' . ($user['lastName'] ?? ''));
                
                // Personalizar los datos básicos con la información del usuario
                if (!empty($fullName)) {
                    $data['basics']['name'] = $fullName;
                }
                if (!empty($user['email'])) {
                    $data['basics']['email'] = $user['email'];
                }
                if (!empty($user['avatar'])) {
                    $data['basics']['image'] = $user['avatar'];
                }
                $data['basics']['url'] = 'https://github.com/' . strtolower(str_replace(' ', '', $user['firstName'] ?? 'usuario'));
            }
        }

        echo json_encode([
            'success' => true,
            'cv_data' => $data
        ]);
    }

    private function isValidJSON($str)
    {
        if (is_array($str)) {
            return true;
        }
        json_decode($str);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
