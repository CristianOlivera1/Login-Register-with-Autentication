<?php

require_once __DIR__ . '/../src/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['email']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El correo electrónico y la contraseña son obligatorios']);
            return;
        }

        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $password = $input['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Formato inválido de correo electrónico']);
            return;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
            return;
        }

        $user = $this->userModel->verifyPassword($email, $password);

        if ($user) {
            $this->startUserSession($user);
            echo json_encode([
                'success' => true, 
                'message' => 'Inicio de sesión exitoso',
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'firstName' => $user['firstName'],
                    'lastName' => $user['lastName'],
                    'avatar' => $user['avatar']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        }
    }

    public function register() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['email']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El correo electrónico y la contraseña son obligatorios']);
            return;
        }

        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $password = $input['password'];
        $confirmPassword = $input['confirmPassword'] ?? '';
        $firstName = $input['firstName'] ?? '';
        $lastName = $input['lastName'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Formato de correo electrónico inválido']);
            return;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
            return;
        }

        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
            return;
        }

        $userData = [
            'email' => $email,
            'password' => $password,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'avatar'    => 'https://api.dicebear.com/9.x/pixel-art/svg?seed=' . urlencode($firstName ?: $email),
            'auth_provider' => 'manual'
        ];

        $newUser = $this->userModel->create($userData);

        if ($newUser) {
            $this->startUserSession($newUser);
            echo json_encode([
                'success' => true, 
                'message' => 'Registro exitoso',
                'user' => [
                    'id' => $newUser['id'],
                    'email' => $newUser['email'],
                    'firstName' => $newUser['firstName'],
                    'lastName' => $newUser['lastName'],
                    'avatar' => $newUser['avatar']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en el registro']);
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Cierre de sesión exitoso']);
    }

    public function checkAuth() {
        session_start();
        
        header('Content-Type: application/json');
        
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'authenticated' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'email' => $_SESSION['email'],
                    'firstName' => $_SESSION['firstName'],
                    'lastName' => $_SESSION['lastName'],
                    'avatar' => $_SESSION['avatar']
                ]
            ]);
        } else {
            echo json_encode(['authenticated' => false]);
        }
    }

    private function startUserSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];
        $_SESSION['avatar'] = $user['avatar'];
        $_SESSION['username'] = explode('@', $user['email'])[0];
    }
}