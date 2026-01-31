<?php

require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/CV.php';

class OAuthController {
    private $userModel;
    private $cvModel;

    public function __construct() {
        $this->userModel = new User();
        $this->cvModel = new CV();
    }

    public function googleCallback() {
        if (!isset($_GET['code'])) {
            $this->redirectWithError('Authorization code not provided');
            return;
        }

        $code = $_GET['code'];
        $tokenData = $this->exchangeCodeForToken('google', $code);

        if (!$tokenData || !isset($tokenData['access_token'])) {
            $this->redirectWithError('Failed to obtain access token');
            return;
        }

        $userInfo = $this->getUserInfo('google', $tokenData['access_token']);

        if (!$userInfo) {
            $this->redirectWithError('Failed to obtain user information');
            return;
        }

        $this->processOAuthUser('google', $userInfo);
    }

    public function githubCallback() {
        if (!isset($_GET['code'])) {
            $this->redirectWithError('Authorization code not provided');
            return;
        }

        $code = $_GET['code'];
        $tokenData = $this->exchangeCodeForToken('github', $code);

        if (!$tokenData || !isset($tokenData['access_token'])) {
            $this->redirectWithError('Failed to obtain access token');
            return;
        }

        $userInfo = $this->getUserInfo('github', $tokenData['access_token']);

        if (!$userInfo) {
            $this->redirectWithError('Failed to obtain user information');
            return;
        }

        $this->processOAuthUser('github', $userInfo);
    }

    public function facebookCallback() {
        if (!isset($_GET['code'])) {
            $this->redirectWithError('Authorization code not provided');
            return;
        }

        $code = $_GET['code'];
        $tokenData = $this->exchangeCodeForToken('facebook', $code);

        if (!$tokenData || !isset($tokenData['access_token'])) {
            $this->redirectWithError('Failed to obtain access token');
            return;
        }

        $userInfo = $this->getUserInfo('facebook', $tokenData['access_token']);

        if (!$userInfo) {
            $this->redirectWithError('Failed to obtain user information');
            return;
        }

        $this->processOAuthUser('facebook', $userInfo);
    }

    private function exchangeCodeForToken($provider, $code) {
        $config = $this->getOAuthConfig($provider);
        
        switch ($provider) {
            case 'google':
                return $this->exchangeGoogleToken($config, $code);
            case 'github':
                return $this->exchangeGithubToken($config, $code);
            case 'facebook':
                return $this->exchangeFacebookToken($config, $code);
        }
        
        return null;
    }

    private function exchangeGoogleToken($config, $code) {
        $url = 'https://oauth2.googleapis.com/token';
        $data = [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect_uri'],
            'grant_type' => 'authorization_code',
            'code' => $code,
        ];

        return $this->makeHttpRequest($url, $data);
    }

    private function exchangeGithubToken($config, $code) {
        $url = 'https://github.com/login/oauth/access_token';
        $data = [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $code,
        ];

        return $this->makeHttpRequest($url, $data, ['Accept: application/json']);
    }

    private function exchangeFacebookToken($config, $code) {
        $url = "https://graph.facebook.com/v14.0/oauth/access_token?client_id={$config['client_id']}&redirect_uri={$config['redirect_uri']}&client_secret={$config['client_secret']}&code={$code}";
        
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    private function getUserInfo($provider, $accessToken) {
        switch ($provider) {
            case 'google':
                return $this->getGoogleUserInfo($accessToken);
            case 'github':
                return $this->getGithubUserInfo($accessToken);
            case 'facebook':
                return $this->getFacebookUserInfo($accessToken);
        }
        
        return null;
    }

    private function getGoogleUserInfo($accessToken) {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo';
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer $accessToken\r\n",
                'method' => 'GET',
            ],
        ]);

        $response = file_get_contents($url, false, $context);
        $userInfo = json_decode($response, true);

        return [
            'email' => $userInfo['email'] ?? null,
            'firstName' => $userInfo['given_name'] ?? '',
            'lastName' => $userInfo['family_name'] ?? '',
            'avatar' => $userInfo['picture'] ?? null,
        ];
    }

    private function getGithubUserInfo($accessToken) {
        $url = 'https://api.github.com/user';
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: token $accessToken\r\nUser-Agent: Codeoner-App\r\n",
                'method' => 'GET',
            ],
        ]);

        $response = file_get_contents($url, false, $context);
        $userInfo = json_decode($response, true);

        // Get user email separately (GitHub API quirk)
        $emailUrl = 'https://api.github.com/user/emails';
        $emailContext = stream_context_create([
            'http' => [
                'header' => "Authorization: token $accessToken\r\nUser-Agent: Codeoner-App\r\n",
                'method' => 'GET',
            ],
        ]);
        $emailResponse = file_get_contents($emailUrl, false, $emailContext);
        $emails = json_decode($emailResponse, true);
        $primaryEmail = null;
        
        if (is_array($emails)) {
            foreach ($emails as $email) {
                if ($email['primary'] === true) {
                    $primaryEmail = $email['email'];
                    break;
                }
            }
        }

        $name = $userInfo['name'] ?? '';
        $nameParts = explode(' ', $name, 2);

        return [
            'email' => $primaryEmail ?? $userInfo['email'],
            'firstName' => $nameParts[0] ?? '',
            'lastName' => $nameParts[1] ?? '',
            'avatar' => $userInfo['avatar_url'] ?? null,
        ];
    }

    private function getFacebookUserInfo($accessToken) {
        $url = "https://graph.facebook.com/me?access_token={$accessToken}&fields=id,name,email,picture.width(300).height(300)";
        $response = file_get_contents($url);
        $userInfo = json_decode($response, true);

        $name = $userInfo['name'] ?? '';
        $nameParts = explode(' ', $name, 2);

        return [
            'email' => $userInfo['email'] ?? null,
            'firstName' => $nameParts[0] ?? '',
            'lastName' => $nameParts[1] ?? '',
            'avatar' => $userInfo['picture']['data']['url'] ?? null,
        ];
    }

    private function processOAuthUser($provider, $userInfo) {
        if (!$userInfo['email']) {
            $this->redirectWithError('Email not provided by ' . ucfirst($provider));
            return;
        }

        $existingUser = $this->userModel->findByEmail($userInfo['email']);

        if ($existingUser) {
            // User exists, update auth_provider if it changed and log them in
            if ($existingUser['auth_provider'] !== $provider) {
                $this->userModel->updateAuthProvider($existingUser['id'], $provider);
                $existingUser['auth_provider'] = $provider;
            }
            $this->startUserSession($existingUser);
        } else {
            // Create new user with correct auth_provider
            $userInfo['auth_provider'] = $provider;
            $newUser = $this->userModel->create($userInfo);
            if ($newUser) {
                // Crear CV por defecto para el nuevo usuario OAuth
                $this->cvModel->createDefaultCV(
                    $newUser['id'],
                    $newUser['firstName'],
                    $newUser['lastName'],
                    $newUser['email'],
                    $newUser['avatar'] // Usar imagen de perfil de la red social
                );
                
                $this->startUserSession($newUser);
            } else {
                $this->redirectWithError('Failed to create user account');
                return;
            }
        }

        // Redirect to home page with success parameter
        header('Location: ' . $_ENV['APP_URL'] . '?oauth_success=1');
        exit();
    }

    private function getOAuthConfig($provider) {
        $baseUrl = $_ENV['REDIRECT_URL_BASE'];
        
        return [
            'google' => [
                'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
                'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
                'redirect_uri' => $baseUrl . '/auth/callback/google',
            ],
            'github' => [
                'client_id' => $_ENV['GITHUB_CLIENT_ID'],
                'client_secret' => $_ENV['GITHUB_CLIENT_SECRET'],
                'redirect_uri' => $baseUrl . '/auth/callback/github',
            ],
            'facebook' => [
                'client_id' => $_ENV['FACEBOOK_CLIENT_ID'],
                'client_secret' => $_ENV['FACEBOOK_CLIENT_SECRET'],
                'redirect_uri' => $baseUrl . '/auth/callback/facebook',
            ]
        ][$provider];
    }

    private function makeHttpRequest($url, $data, $headers = []) {
        $defaultHeaders = ['Content-Type: application/x-www-form-urlencoded'];
        $allHeaders = array_merge($defaultHeaders, $headers);

        $options = [
            'http' => [
                'header' => implode("\r\n", $allHeaders) . "\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        return json_decode($response, true);
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

    private function redirectWithError($message) {
        session_start();
        $_SESSION['oauth_error'] = $message;
        header('Location: ' . $_ENV['APP_URL'] . '?error=oauth');
        exit();
    }
}