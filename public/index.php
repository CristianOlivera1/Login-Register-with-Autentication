<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = trim($requestUri, '/') ?: 'home';


// Manejar rutas de autenticación
if (str_starts_with($route, 'auth/')) {
    $authRoute = substr($route, 5); 
    
    error_log("Processing auth route: " . $authRoute);
    
    switch ($authRoute) {
        case 'login':
            require_once __DIR__ . '/../controllers/AuthController.php';
            $auth = new AuthController();
            $auth->login();
            exit;
            
        case 'register':
            require_once __DIR__ . '/../controllers/AuthController.php';
            $auth = new AuthController();
            $auth->register();
            exit;
            
        case 'logout':
            require_once __DIR__ . '/../controllers/AuthController.php';
            $auth = new AuthController();
            $auth->logout();
            exit;
            
        case 'check':
            require_once __DIR__ . '/../controllers/AuthController.php';
            $auth = new AuthController();
            $auth->checkAuth();
            exit;
            
        case 'config':
            header('Content-Type: application/json');
            echo json_encode([
                'google_client_id' => $_ENV['GOOGLE_CLIENT_ID'],
                'github_client_id' => $_ENV['GITHUB_CLIENT_ID'],
                'facebook_client_id' => $_ENV['FACEBOOK_CLIENT_ID'],
                'base_url' => $_ENV['REDIRECT_URL_BASE']
            ]);
            exit;
            
        case 'callback/google':
            error_log("Processing Google OAuth callback");
            require_once __DIR__ . '/../controllers/OAuthController.php';
            $oauth = new OAuthController();
            $oauth->googleCallback();
            exit;
            
        case 'callback/github':
            error_log("Processing GitHub OAuth callback");
            require_once __DIR__ . '/../controllers/OAuthController.php';
            $oauth = new OAuthController();
            $oauth->githubCallback();
            exit;
            
        case 'callback/facebook':
            error_log("Processing Facebook OAuth callback");
            require_once __DIR__ . '/../controllers/OAuthController.php';
            $oauth = new OAuthController();
            $oauth->facebookCallback();
            exit;
            
        default:
            http_response_code(404);
            echo "Auth route not found: " . $authRoute;
            exit;
    }
}

$routes = [
    'home'    => 'home/home.php',
    'profile' => 'profile/profile.php'
];

$viewPath = __DIR__ . '/../views/' . ($routes[$route] ?? '404.php');

ob_start();
include $viewPath;
$content = ob_get_clean();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    echo $content;
    exit;
}

include __DIR__ . '/../views/layouts/main.php';