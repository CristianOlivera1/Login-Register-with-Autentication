<?php

require_once __DIR__ . '/../config/database.php';

class CV {
    private $db;
    public $connection; 

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->getConnection();
    }

    public function getAllTemplates() {
        $stmt = $this->connection->prepare("SELECT * FROM cv_templates WHERE is_active = TRUE ORDER BY name");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $templates = [];
        while ($row = $result->fetch_assoc()) {
            $row['template_data'] = json_decode($row['template_data'], true);
            $templates[] = $row;
        }
        
        return $templates;
    }

    public function getTemplate($id) {
        $stmt = $this->connection->prepare("SELECT * FROM cv_templates WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $template = $result->fetch_assoc();
            $template['template_data'] = json_decode($template['template_data'], true);
            return $template;
        }
        return null;
    }

    public function createCV($data) {
        $id = $this->generateUUID();
        $userId = $data['user_id'];
        $templateId = $data['template_id'];
        $cvData = json_encode($data['cv_data']);
        $originalJson = $data['original_json_input'];
        $slug = $data['slug'];
        $title = $data['title'] ?? 'Mi CV';

        $stmt = $this->connection->prepare("
            INSERT INTO user_cvs (id, user_id, title, slug, template_id, cv_data, original_json_input) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("sssssss", $id, $userId, $title, $slug, $templateId, $cvData, $originalJson);
        
        if ($stmt->execute()) {
            $this->insertCVSections($id, $data['cv_data']);
            return $id;
        }
        return false;
    }

    public function updateCV($cvId, $data, $newSlug = null) {
        $templateId = $data['template_id'];
        $cvData = json_encode($data['cv_data']);
        $originalJson = $data['original_json_input'];
        $title = $data['title'] ?? 'Mi CV';

        if ($newSlug) {
            $stmt = $this->connection->prepare("
                UPDATE user_cvs 
                SET template_id = ?, cv_data = ?, original_json_input = ?, title = ?, slug = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmt->bind_param("ssssss", $templateId, $cvData, $originalJson, $title, $newSlug, $cvId);
        } else {
            $stmt = $this->connection->prepare("
                UPDATE user_cvs 
                SET template_id = ?, cv_data = ?, original_json_input = ?, title = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmt->bind_param("sssss", $templateId, $cvData, $originalJson, $title, $cvId);
        }
        
        if ($stmt->execute()) {
            $this->deleteCVSections($cvId);
            $this->insertCVSections($cvId, $data['cv_data']);
            return true;
        }
        return false;
    }

    public function updateCVTitle($cvId, $title) {
        $stmt = $this->connection->prepare("
            UPDATE user_cvs 
            SET title = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->bind_param("ss", $title, $cvId);
        return $stmt->execute();
    }

    public function getUserCVByUserId($userId) {
        $stmt = $this->connection->prepare("
            SELECT * FROM user_cvs 
            WHERE user_id = ? 
            ORDER BY updated_at DESC 
            LIMIT 1
        ");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    private function deleteCVSections($cvId) {
        $tables = ['cv_personal_info', 'cv_work_experience', 'cv_projects', 'cv_education', 'cv_skills'];
        
        foreach ($tables as $table) {
            $stmt = $this->connection->prepare("DELETE FROM {$table} WHERE cv_id = ?");
            $stmt->bind_param("s", $cvId);
            $stmt->execute();
        }
    }

    public function getCVBySlug($slug) {
        $stmt = $this->connection->prepare("
            SELECT cv.*, u.firstName, u.lastName, u.avatar, 
                   COALESCE(t.name, 'Default') as template_name
            FROM user_cvs cv
            LEFT JOIN users u ON cv.user_id = u.id
            LEFT JOIN cv_templates t ON cv.template_id = t.id
            WHERE cv.slug = ?
        ");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function findSimilarCV($slug) {
        // Buscar CVs similares basados en el slug
        $stmt = $this->connection->prepare("
            SELECT cv.*, u.firstName, u.lastName, u.avatar, 
                   COALESCE(t.name, 'Default') as template_name
            FROM user_cvs cv
            LEFT JOIN users u ON cv.user_id = u.id
            LEFT JOIN cv_templates t ON cv.template_id = t.id
            WHERE cv.is_public = 1 AND cv.slug LIKE ?
            ORDER BY cv.view_count DESC, cv.updated_at DESC
            LIMIT 1
        ");
        $similarSlug = $slug . '%';
        $stmt->bind_param("s", $similarSlug);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    public function getMostPopularCV() {
        // Obtener el CV público con más vistas
        $stmt = $this->connection->prepare("
            SELECT cv.*, u.firstName, u.lastName, u.avatar, 
                   COALESCE(t.name, 'Default') as template_name
            FROM user_cvs cv
            LEFT JOIN users u ON cv.user_id = u.id
            LEFT JOIN cv_templates t ON cv.template_id = t.id
            WHERE cv.is_public = 1
            ORDER BY cv.view_count DESC, cv.updated_at DESC
            LIMIT 1
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    public function getRandomPopularCV() {
        // Obtener un CV aleatorio de los 5 más populares
        $stmt = $this->connection->prepare("
            SELECT cv.*, u.firstName, u.lastName, u.avatar, 
                   COALESCE(t.name, 'Default') as template_name
            FROM user_cvs cv
            LEFT JOIN users u ON cv.user_id = u.id
            LEFT JOIN cv_templates t ON cv.template_id = t.id
            WHERE cv.is_public = 1
            ORDER BY cv.view_count DESC, cv.updated_at DESC
            LIMIT 5
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cvs = [];
        while ($row = $result->fetch_assoc()) {
            $cvs[] = $row;
        }
        
        if (!empty($cvs)) {
            return $cvs[array_rand($cvs)];
        }
        
        return null;

        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    public function incrementViewCount($cvId) {
        $stmt = $this->connection->prepare("
            UPDATE user_cvs 
            SET view_count = view_count + 1, last_viewed_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $stmt->bind_param("s", $cvId);
        return $stmt->execute();
    }

    public function slugExists($slug) {
        $stmt = $this->connection->prepare("SELECT id FROM user_cvs WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    private function insertCVSections($cvId, $cvData) {
        if (isset($cvData['basics'])) {
            $this->insertPersonalInfo($cvId, $cvData['basics']);
        }

        if (isset($cvData['work']) && is_array($cvData['work'])) {
            foreach ($cvData['work'] as $index => $work) {
                $this->insertWorkExperience($cvId, $work, $index);
            }
        }

        if (isset($cvData['projects']) && is_array($cvData['projects'])) {
            foreach ($cvData['projects'] as $index => $project) {
                $this->insertProject($cvId, $project, $index);
            }
        }

        if (isset($cvData['education']) && is_array($cvData['education'])) {
            foreach ($cvData['education'] as $index => $education) {
                $this->insertEducation($cvId, $education, $index);
            }
        }

        if (isset($cvData['skills']) && is_array($cvData['skills'])) {
            foreach ($cvData['skills'] as $index => $skill) {
                $this->insertSkill($cvId, $skill, $index);
            }
        }
    }

    private function insertPersonalInfo($cvId, $basics) {
        $id = $this->generateUUID();
        $fullName = $basics['name'] ?? '';
        $professionalTitle = $basics['label'] ?? '';
        $email = $basics['email'] ?? '';
        $phone = $basics['phone'] ?? '';
        $websiteUrl = $basics['url'] ?? '';
        $summary = $basics['summary'] ?? '';
        $profileImage = $basics['image'] ?? '';
        
        $location = '';
        if (isset($basics['location'])) {
            if (is_array($basics['location'])) {
                $location = ($basics['location']['city'] ?? '') . ', ' . ($basics['location']['region'] ?? '');
            } else {
                $location = $basics['location'];
            }
        }

        $stmt = $this->connection->prepare("
            INSERT INTO cv_personal_info 
            (id, cv_id, full_name, professional_title, email, phone, location, website_url, summary, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("ssssssssss", $id, $cvId, $fullName, $professionalTitle, $email, $phone, $location, $websiteUrl, $summary, $profileImage);
        $stmt->execute();
    }

    private function insertWorkExperience($cvId, $work, $order) {
        $id = $this->generateUUID();
        $companyName = $work['name'] ?? '';
        $position = $work['position'] ?? '';
        $location = $work['location'] ?? '';
        $startDate = $this->formatDate($work['startDate'] ?? null);
        $endDate = $this->formatDate($work['endDate'] ?? null);
        $isCurrent = empty($work['endDate']) ? 1 : 0;
        $description = $work['description'] ?? '';
        $achievements = json_encode($work['highlights'] ?? []);
        $technologies = json_encode($work['technologies'] ?? []);

        $stmt = $this->connection->prepare("
            INSERT INTO cv_work_experience 
            (id, cv_id, company_name, position, location, start_date, end_date, is_current, description, achievements, technologies, display_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("sssssssisssi", $id, $cvId, $companyName, $position, $location, $startDate, $endDate, $isCurrent, $description, $achievements, $technologies, $order);
        $stmt->execute();
    }

    private function insertProject($cvId, $project, $order) {
        $id = $this->generateUUID();
        $projectName = $project['name'] ?? '';
        $role = $project['role'] ?? '';
        $projectType = $project['type'] ?? 'personal';
        $startDate = $this->formatDate($project['startDate'] ?? null);
        $endDate = $this->formatDate($project['endDate'] ?? null);
        $description = $project['description'] ?? '';
        $achievements = json_encode($project['highlights'] ?? []);
        $technologies = json_encode($project['technologies'] ?? []);
        $projectUrl = $project['url'] ?? '';

        $stmt = $this->connection->prepare("
            INSERT INTO cv_projects 
            (id, cv_id, project_name, role, project_type, start_date, end_date, description, achievements, technologies, project_url, display_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("sssssssssssi", $id, $cvId, $projectName, $role, $projectType, $startDate, $endDate, $description, $achievements, $technologies, $projectUrl, $order);
        $stmt->execute();
    }

    private function insertEducation($cvId, $education, $order) {
        $id = $this->generateUUID();
        $institution = $education['institution'] ?? '';
        $degree = $education['studyType'] ?? '';
        $fieldOfStudy = $education['area'] ?? '';
        $location = $education['location'] ?? '';
        $startDate = $this->formatDate($education['startDate'] ?? null);
        $endDate = $this->formatDate($education['endDate'] ?? null);
        $gpa = $education['gpa'] ?? '';
        $honors = $education['honors'] ?? '';
        $description = $education['description'] ?? '';

        $stmt = $this->connection->prepare("
            INSERT INTO cv_education 
            (id, cv_id, institution, degree, field_of_study, location, start_date, end_date, gpa, honors, description, display_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("sssssssssssi", $id, $cvId, $institution, $degree, $fieldOfStudy, $location, $startDate, $endDate, $gpa, $honors, $description, $order);
        $stmt->execute();
    }

    private function insertSkill($cvId, $skill, $order) {
        $id = $this->generateUUID();
        $skillCategory = $skill['name'] ?? 'General';
        $skills = json_encode($skill['keywords'] ?? []);
        $proficiencyLevel = $skill['level'] ?? 'intermediate';

        $stmt = $this->connection->prepare("
            INSERT INTO cv_skills 
            (id, cv_id, skill_category, skills, proficiency_level, display_order) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("sssssi", $id, $cvId, $skillCategory, $skills, $proficiencyLevel, $order);
        $stmt->execute();
    }

    private function formatDate($dateString) {
        if (empty($dateString)) {
            return null;
        }
        
        try {
            $date = new DateTime($dateString);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }

    private function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getPublicCVs($limit = 20, $offset = 0) {
        $stmt = $this->connection->prepare("
            SELECT cv.*, u.firstName, u.lastName, u.avatar, t.name as template_name
            FROM user_cvs cv
            INNER JOIN users u ON cv.user_id = u.id
            LEFT JOIN cv_templates t ON cv.template_id = t.id
            WHERE cv.is_public = 1
            ORDER BY cv.view_count DESC, cv.updated_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cvs = [];
        while ($row = $result->fetch_assoc()) {
            $cvs[] = $row;
        }
        
        return $cvs;
    }

    public function getPublicCVsCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM user_cvs WHERE is_public = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function createDefaultCV($userId, $userFirstName = '', $userLastName = '', $userEmail = '', $userImage = '') {
        // Obtener template por defecto (generalmente el primero)
        $templates = $this->getAllTemplates();
        if (empty($templates)) {
            return false;
        }
        
        $defaultTemplate = $templates[0];
        
        $defaultCVData = [
            "basics" => [
                "name" => trim($userFirstName . ' ' . $userLastName) ?: "Tu Nombre Completo",
                "label" => "Título Profesional",
                "email" => $userEmail ?: "tu.email@ejemplo.com",
                "image" => $userImage ?: "",
                "phone" => "Tu teléfono",
                "url" => "https://tu-portafolio.com",
                "summary" => "Breve descripción profesional que destaque tus fortalezas, experiencia y objetivos de carrera.",
                "location" => [
                    "address" => "",
                    "postalCode" => "",
                    "city" => "Tu Ciudad",
                    "countryCode" => "TU",
                    "region" => "Tu País"
                ],
                "profiles" => [
                    [
                        "network" => "LinkedIn",
                        "username" => "tu-usuario",
                        "url" => "https://linkedin.com/in/tu-usuario"
                    ]
                ]
            ],
            "work" => [
                [
                    "name" => "Nombre de la Empresa",
                    "position" => "Tu Cargo",
                    "location" => "Ciudad, País",
                    "startDate" => "2023-01",
                    "endDate" => "",
                    "description" => "Descripción del puesto y responsabilidades principales.",
                    "highlights" => [
                        "Logro destacado 1",
                        "Logro destacado 2"
                    ],
                    "technologies" => []
                ]
            ],
            "education" => [
                [
                    "institution" => "Universidad/Instituto",
                    "studyType" => "Grado/Título",
                    "area" => "Área de estudio",
                    "location" => "Ciudad, País",
                    "startDate" => "2020",
                    "endDate" => "2024",
                    "gpa" => "",
                    "honors" => "",
                    "description" => ""
                ]
            ],
            "projects" => [
                [
                    "name" => "Proyecto Destacado",
                    "role" => "Tu Rol",
                    "type" => "personal",
                    "description" => "Descripción del proyecto y tecnologías utilizadas.",
                    "highlights" => [
                        "Característica principal 1",
                        "Característica principal 2"
                    ],
                    "technologies" => ["Tecnología 1", "Tecnología 2"],
                    "startDate" => "2024-01",
                    "endDate" => "2024-06",
                    "url" => ""
                ]
            ],
            "skills" => [
                [
                    "name" => "Lenguajes de Programación",
                    "level" => "Intermedio",
                    "keywords" => ["JavaScript", "Python", "PHP"]
                ],
                [
                    "name" => "Frameworks",
                    "level" => "Intermedio", 
                    "keywords" => ["React", "Laravel", "Express"]
                ],
                [
                    "name" => "Herramientas",
                    "level" => "Intermedio",
                    "keywords" => ["Git", "Docker", "VS Code"]
                ]
            ],
            "languages" => [
                [
                    "language" => "Español",
                    "fluency" => "Nativo"
                ],
                [
                    "language" => "Inglés", 
                    "fluency" => "Intermedio"
                ]
            ]
        ];

        $userName = trim($userFirstName . ' ' . $userLastName) ?: 'usuario';
        $slug = $this->generateSlug($userName . '-cv');
        
        // Verificar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data = [
            'user_id' => $userId,
            'template_id' => $defaultTemplate['id'],
            'cv_data' => $defaultCVData,
            'original_json_input' => json_encode($defaultCVData, JSON_PRETTY_PRINT),
            'slug' => $slug,
            'title' => 'Mi CV'
        ];

        return $this->createCV($data);
    }

    private function generateSlug($text) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    }
}