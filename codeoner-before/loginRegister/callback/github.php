<?php 
session_start(); 
require_once '../../vendor/autoload.php';

// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); 
$dotenv->load();

include '../../controler/conexion.php';

$client_id = $_ENV['GITHUB_CLIENT_ID'];
$client_secret = $_ENV['GITHUB_CLIENT_SECRET'];
$redirect_uri = 'http://localhost:3000/loginRegister/callback/github.php'; 
$code = $_GET['code'];

// Solicitar el token de acceso
$url = 'https://github.com/login/oauth/access_token';
$data = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $code,
];

$options = [
    'http' => [
        'header' => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$response = json_decode($result, true);

if (isset($response['access_token'])) {
    $access_token = $response['access_token'];

    // Obtener la información del usuario
    $user_info_url = 'https://api.github.com/user';
    $user_info_context = stream_context_create([
        'http' => [
            'header' => "Authorization: token $access_token\r\nUser-Agent: MyApp\r\n",
            'method' => 'GET',
        ],
    ]);
    $user_info_result = file_get_contents($user_info_url, false, $user_info_context);
    $user_info = json_decode($user_info_result, true);

    // Comprobar si el usuario ya existe en la base de datos
    $email = $mysqli->real_escape_string($user_info['email']);
    $profileImageUrl = $user_info['avatar_url'];
    $query = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        // El usuario ya existe, iniciar sesión
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; 

        // Muestra la imagen de perfil y correo
        $_SESSION['profile_image'] = $profileImageUrl;
        $_SESSION['email'] = $email;
        $username = explode('@', $email)[0];
        $_SESSION['username'] = $username; 

        // Ahora, realizar una consulta adicional para obtener datos de la tabla "usuario_detalles"
        $userId = $_SESSION['user_id'];
        $detallesQuery = "SELECT * FROM usuario_detalles WHERE user_id='$userId'";
        $detallesResult = $mysqli->query($detallesQuery);

        $detalles = $detallesResult->fetch_assoc();
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
        $_SESSION['perfil_desc2'] = $detalles['perfil_desc2']; 
        $_SESSION['experiencia2'] = $detalles['experiencia2']; 
        $_SESSION['experiencia3'] = $detalles['experiencia3']; 
        $_SESSION['habilidades2'] = $detalles['habilidades2']; 
        $_SESSION['habilidades3'] = $detalles['habilidades3']; 
        $_SESSION['proyectos_link'] = $detalles['proyectos_link']; 
        $_SESSION['proyectos2'] = $detalles['proyectos2']; 
        $_SESSION['proyectos_link2'] = $detalles['proyectos_link2']; 
        $_SESSION['proyectos3'] = $detalles['proyectos3']; 
        $_SESSION['proyectos_link3'] = $detalles['proyectos_link3'];  
        $_SESSION['educacion2'] = $detalles['educacion2']; 
        $_SESSION['educacion3'] = $detalles['educacion3']; 

        header('Location: ../../index.php');
    } else {
        // El usuario no existe, registrarlo
        $name = $mysqli->real_escape_string($user_info['login']); 
        $insert_query = "INSERT INTO usuarios (email, password) VALUES ('$email', '')";

        if ($mysqli->query($insert_query) === TRUE) {
            $userId = $mysqli->insert_id; // Este es el ID del usuario recién creado
            // Almacenar el ID del usuario en la sesión
            $_SESSION['user_id'] = $userId; 

            // Insertar detalles en la tabla "usuario_detalles"
            $ubicacion = "https://www.google.com/maps/place/Abancay,+Apur%C3%ADmac,+Per%C3%BA"; 
            $portafolio = "https://usuario-portafolio.com"; 
            $linkedin = "https://www.linkedin.com/in/usuario"; 
            $github = "https://github.com/usuario"; 
            $perfil_desc="Aquí puedes agregar una breve descripción o resumen sobre ti, tus intereses, y cualquier otra información relevante que quieras compartir.";
            $perfil_desc2 = " ";
            $experiencia="Ingresa la Información relevante sobre la empresa laborada. 
                    - Tecnologías: Ingresa las tecnologías que utilizadas. 
                    - Responsabilidades: Describe tu rol que tuviste en la empresa. 
                    - Logros: Menciona los diferentes logros que ayudaron a la empresa y/o logros que influyeron en ti mismo.";
            $experiencia2 = " ";
            $experiencia3 = " ";
            $habilidades="JavaScript, HTML, CSS, TypeScript, React";
            $habilidades2 = " ";
            $habilidades3 = " ";
            $proyectos = "Nombre del Proyecto - Breve descripción del proyecto (tecnologías utilizadas, objetivo del proyecto).";
            $proyectos_link = "https://github.com/CristianOlivera1/Login-Register-with-Autentication";
            $proyectos2 = " ";
            $proyectos_link2 = " ";
            $proyectos3 = " ";
            $proyectos_link3 = " ";
            $educacion="Título del grado, especialización - Nombre de la universidad (año de graduación)";
            $educacion2 = " ";
            $educacion3 = " ";
            $insert_details_query = "INSERT INTO usuario_detalles (user_id, nombre, foto_perfil, ubicacion, portafolio, linkedin, github, perfil_desc, experiencia, perfil_desc2, experiencia2, experiencia3, habilidades, habilidades2, habilidades3, proyectos, proyectos_link, proyectos2, proyectos_link2, proyectos3, proyectos_link3, educacion, educacion2, educacion3) VALUES ('$userId', '$name', '$profileImageUrl', '$ubicacion', '$portafolio', '$linkedin', '$github', '$perfil_desc', '$experiencia', '$perfil_desc2', '$experiencia2', '$experiencia3', '$habilidades', '$habilidades2', '$habilidades3', '$proyectos', '$proyectos_link', '$proyectos2', '$proyectos_link2', '$proyectos3', '$proyectos_link3', '$educacion', '$educacion2', '$educacion3')";

            if ($mysqli->query($insert_details_query) === TRUE) {
                $_SESSION['nombre'] = $name; 
                $_SESSION['foto_perfil'] = $profileImageUrl; 
                $_SESSION['ubicacion'] = $ubicacion; 
                $_SESSION['portafolio'] = $portafolio; 
                $_SESSION['linkedin'] = $linkedin; 
                $_SESSION['github'] = $github; 
                $_SESSION['perfil_desc'] = $perfil_desc; 
                $_SESSION['experiencia'] = $experiencia; 
                $_SESSION['perfil_desc2'] = $perfil_desc2; 
                $_SESSION['experiencia2'] = $experiencia2; 
                $_SESSION['experiencia3'] = $experiencia3; 
                $_SESSION['habilidades'] = $habilidades; 
                $_SESSION['habilidades2'] = $habilidades2; 
                $_SESSION['habilidades3'] = $habilidades3;
                $_SESSION['proyectos'] = $proyectos;
                $_SESSION['proyectos_link'] = $proyectos_link;
                $_SESSION['proyectos2'] = $proyectos2;
                $_SESSION['proyectos_link2'] = $proyectos_link2;
                $_SESSION['proyectos3'] = $proyectos3;
                $_SESSION['proyectos_link3'] = $proyectos_link3;
                $_SESSION['educacion'] = $educacion; 
                $_SESSION['educacion2'] = $educacion2; 
                $_SESSION['educacion3'] = $educacion3; 
            } else {
                echo "Error al insertar los detalles del usuario: " . $mysqli->error;
            }
        
            // Para mostrar el nombre del usuario en la página de agradecimiento
            $username = explode('@', $email)[0];
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['profile_image'] = $profileImageUrl; 
            
            header('Location: ../registerSuccessful.php');
        } else {
            echo "Error al registrar el usuario: ". $mysqli->error;
        }
    }

    $mysqli->close();
} else {
    echo 'Error al autenticar con GitHub o cancelaste la operación.';
    header('Location: ../../index.php');
}
?>
