<?php

require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Profile.php';
require_once __DIR__ . '/../src/CV.php';

class ProfileController {
    private $profileModel;
    private $userModel;
    private $cvModel;

    public function __construct() {
        $this->profileModel = new Profile();
        $this->userModel = new User();
        $this->cvModel = new CV();
    }

    public function getProfile() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];
            $profile = $this->profileModel->getUserProfile($userId);
            $topCV = $this->profileModel->getTopPerformingCV($userId);
            
            echo json_encode([
                'success' => true,
                'profile' => $profile,
                'topCV' => $topCV
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error del servidor']);
        }
    }

    public function updateProfile() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];

        try {
            // Validaciones
            $errors = $this->validateProfileData($input);
            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }

            $updated = $this->profileModel->updateUserProfile($userId, $input);
            
            if ($updated) {
                // Actualizar sesión
                if (isset($input['firstName'])) $_SESSION['firstName'] = $input['firstName'];
                if (isset($input['lastName'])) $_SESSION['lastName'] = $input['lastName'];
                if (isset($input['email'])) $_SESSION['email'] = $input['email'];
                
                echo json_encode(['success' => true, 'message' => 'Perfil actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error del servidor']);
        }
    }

    public function uploadAvatar() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        if (!isset($_FILES['avatar'])) {
            echo json_encode(['success' => false, 'message' => 'No se seleccionó archivo']);
            return;
        }

        $file = $_FILES['avatar'];
        $userId = $_SESSION['user_id'];

        try {
            // Validar archivo
            $validation = $this->validateAvatarFile($file);
            if (!$validation['valid']) {
                echo json_encode(['success' => false, 'message' => $validation['message']]);
                return;
            }

            // Subir archivo
            $uploadResult = $this->uploadAvatarFile($file, $userId);
            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                return;
            }

            // Actualizar base de datos
            $updated = $this->profileModel->updateAvatar($userId, $uploadResult['url']);
            
            if ($updated) {
                $_SESSION['avatar'] = $uploadResult['url'];
                echo json_encode(['success' => true, 'avatar' => $uploadResult['url']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en base de datos']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor']);
        }
    }

    public function removeAvatar() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        // Generar avatar por defecto
        $defaultAvatar = 'https://api.dicebear.com/9.x/pixel-art/svg?seed=' . urlencode($user['firstName'] ?: $user['email']);
        
        $updated = $this->profileModel->updateAvatar($userId, $defaultAvatar);
        
        if ($updated) {
            $_SESSION['avatar'] = $defaultAvatar;
            echo json_encode(['success' => true, 'avatar' => $defaultAvatar]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar avatar']);
        }
    }

    public function toggleCVVisibility() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];
        $isPublic = $input['isPublic'] ?? false;

        $updated = $this->profileModel->updateCVVisibility($userId, $isPublic);
        
        if ($updated) {
            echo json_encode(['success' => true, 'isPublic' => $isPublic]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar visibilidad']);
        }
    }

    public function changePassword() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];

        // Validar que no sea usuario OAuth
        $user = $this->userModel->findById($userId);
        if ($user['auth_provider'] !== 'manual') {
            echo json_encode(['success' => false, 'message' => 'No puedes cambiar la contraseña de cuentas OAuth']);
            return;
        }

        $currentPassword = $input['currentPassword'] ?? '';
        $newPassword = $input['newPassword'] ?? '';
        $confirmPassword = $input['confirmPassword'] ?? '';

        // Validaciones
        $errors = [];
        if (!$this->userModel->verifyPassword($user['email'], $currentPassword)) {
            $errors['currentPassword'] = 'Contraseña actual incorrecta';
        }
        if (strlen($newPassword) < 8) {
            $errors['newPassword'] = 'La nueva contraseña debe tener al menos 8 caracteres';
        }
        if ($newPassword !== $confirmPassword) {
            $errors['confirmPassword'] = 'Las contraseñas no coinciden';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }

        $updated = $this->profileModel->updatePassword($userId, $newPassword);
        
        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar contraseña']);
        }
    }

    private function validateProfileData($data) {
        $errors = [];
        
        if (isset($data['firstName']) && (empty($data['firstName']) || strlen($data['firstName']) > 150)) {
            $errors['firstName'] = 'El nombre debe tener entre 1 y 150 caracteres';
        }
        
        if (isset($data['lastName']) && (empty($data['lastName']) || strlen($data['lastName']) > 250)) {
            $errors['lastName'] = 'El apellido debe tener entre 1 y 250 caracteres';
        }
        
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Formato de email inválido';
        }
        
        return $errors;
    }

    private function validateAvatarFile($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/svg+xml'];
        $maxSize = 1024 * 1024; // 1MB
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'message' => 'Error al subir archivo'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'message' => 'El archivo debe ser menor a 1MB'];
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['valid' => false, 'message' => 'Formato no permitido. Use JPG, PNG, WEBP o SVG'];
        }
        
        return ['valid' => true];
    }

    private function uploadAvatarFile($file, $userId) {
        $uploadDir = __DIR__ . '/../public/assets/avatars/';
        
        // Crear directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'success' => true,
                'url' => '/assets/avatars/' . $filename
            ];
        }
        
        return ['success' => false, 'message' => 'Error al mover archivo'];
    }
}