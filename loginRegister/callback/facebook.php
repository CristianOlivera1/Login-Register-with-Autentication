<?php
session_start();
require_once '../../vendor/autoload.php'; 

// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); 
$dotenv->load();

include '../../controler/conexion.php';

$client_id = $_ENV['FACEBOOK_CLIENT_ID']; 
$client_secret = $_ENV['FACEBOOK_CLIENT_SECRET']; 
$code = $_GET['code'];
$redirect_uri = 'http://localhost:3000/loginRegister/callback/facebook.php'; // URL de redirección

// Intercambiar el código de autorización por un token de acceso
$token_url = "https://graph.facebook.com/v14.0/oauth/access_token?client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}&code={$code}";

$response = file_get_contents($token_url);
$response_data = json_decode($response, true);

if (isset($response_data['access_token'])) {
    $access_token = $response_data['access_token'];

    // Obtener la información del usuario
    $user_info_url = "https://graph.facebook.com/me?access_token={$access_token}&fields=id,name,email,picture.width(300).height(300)";
    $user_info = file_get_contents($user_info_url);
    $user_info_data = json_decode($user_info, true);

    if (isset($user_info_data['email'])) {
        $email = $mysqli->real_escape_string($user_info_data['email']);
        $name = $mysqli->real_escape_string($user_info_data['name']);
        $profileImageUrl = $user_info_data['picture']['data']['url'];

        // Comprobar si el usuario ya existe en la base de datos
        $query = "SELECT * FROM usuarios WHERE email='$email'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            // El usuario ya existe, iniciar sesión
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];

            // Guardar datos en sesión
            $_SESSION['profile_image'] = $profileImageUrl;
            $_SESSION['email'] = $email;
            $username = explode('@', $email)[0];
            $_SESSION['username'] = $username;

            // Obtener detalles adicionales del usuario
            $userId = $_SESSION['user_id'];
            $detallesQuery = "SELECT * FROM usuario_detalles WHERE user_id='$userId'";
            $detallesResult = $mysqli->query($detallesQuery);
            $detalles = $detallesResult->fetch_assoc();

            // Almacenar detalles en la sesión
            $_SESSION['nombre'] = $detalles['nombre'];
            $_SESSION['profesion'] = $detalles['profesion'];
            $_SESSION['ubicacion'] = $detalles['ubicacion'];
            $_SESSION['portafolio'] = $detalles['portafolio'];
            $_SESSION['linkedin'] = $detalles['linkedin'];
            $_SESSION['github'] = $detalles['github'];
            $_SESSION['perfil_desc'] = $detalles['perfil_desc'];
            $_SESSION['experiencia'] = $detalles['experiencia'];
            $_SESSION['habilidades'] = $detalles['habilidades'];
            $_SESSION['proyectos'] = $detalles['proyectos'];
            $_SESSION['educacion'] = $detalles['educacion'];
            $_SESSION['foto_perfil'] = $detalles['foto_perfil'];

            header('Location: ../../index.php');
            exit();
        } else {
            // El usuario no existe, registrarlo
            $insert_query = "INSERT INTO usuarios (email, password) VALUES ('$email', '')";
            if ($mysqli->query($insert_query) === TRUE) {
                $userId = $mysqli->insert_id;
                $_SESSION['user_id'] = $userId;

                // Insertar detalles en la tabla usuario_detalles
                $ubicacion = "https://www.google.com/maps/place/Abancay,+Apur%C3%ADmac,+Per%C3%BA";
                $portafolio = "https://usuario-portafolio.com";
                $linkedin = "https://www.linkedin.com/in/usuario";
                $github = "https://github.com/usuario";
                $insert_details_query = "INSERT INTO usuario_detalles (user_id, nombre, foto_perfil, ubicacion, portafolio, linkedin, github) VALUES ('$userId', '$name', '$profileImageUrl', '$ubicacion', '$portafolio', '$linkedin', '$github')";

                if ($mysqli->query($insert_details_query) === TRUE) {
                    $_SESSION['nombre'] = $name;
                    $_SESSION['foto_perfil'] = $profileImageUrl;
                    $_SESSION['ubicacion'] = $ubicacion;
                    $_SESSION['portafolio'] = $portafolio;
                    $_SESSION['linkedin'] = $linkedin;
                    $_SESSION['github'] = $github;

                    $username = explode('@', $email)[0];
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    header('Location: ../registerSuccessful.php');
                    exit();
                } else {
                    echo "Error al insertar los detalles del usuario: " . $mysqli->error;
                }
            } else {
                echo "Error al registrar el usuario: " . $mysqli->error;
            }
        }
    } else {
        echo 'Error al obtener la información del usuario.';
    }
} else {
    echo 'Error al autenticar con Facebook.';
    header('Location: ../../index.php');
    exit();
}
?>
