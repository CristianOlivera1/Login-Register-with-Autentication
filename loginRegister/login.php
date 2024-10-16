<?php
session_start(); // Inicia sesión
include '../controler/conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Login tradicional
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Buscar al usuario en la base de datos
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Inicio de sesión exitoso
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['email'] = $email;
                $username = explode('@', $email)[0];
                $_SESSION['username'] = $username; 

                     // Realizar consulta adicional para obtener detalles del usuario de la tabla "usuario_detalles"
                $userId = $_SESSION['user_id'];
                $detallesQuery = "SELECT * FROM usuario_detalles WHERE user_id = ?";
                $stmtDetalles = $mysqli->prepare($detallesQuery);
                $stmtDetalles->bind_param("i", $userId);
                $stmtDetalles->execute();
                $detallesResult = $stmtDetalles->get_result();

                if ($detallesResult->num_rows > 0) {
                    $detalles = $detallesResult->fetch_assoc();
                    // Actualiza los datos de la sesión con los detalles del usuario
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
                }
                
                echo json_encode(["success" => true]);
                $stmtDetalles->close();
                $stmt->close();
                $mysqli->close();
                exit();
            } else {
                // Contraseña incorrecta
                echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
                $stmt->close();
                $mysqli->close();
                exit();
            }
        } else {
            // Usuario no encontrado
            echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
            $stmt->close();
            $mysqli->close();
            exit();
        }
    }
}

// Aquí se manejará la autenticación con Google, GitHub, Facebook
if (isset($_SESSION['oauth_user'])) {
    // Obtener el usuario de OAuth
    $oauth_user = $_SESSION['oauth_user']; // Asegúrate de establecer este valor en tu callback

    // Verifica si el usuario ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $oauth_user['email']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado, inicia sesión
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_image'] = $userProfileImageUrl; 
     
    } else {
        // Usuario no encontrado, puedes crear uno nuevo si lo deseas
        $sql = "INSERT INTO usuarios (email, password) VALUES (?, '')"; // Contraseña vacía o generar una
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $oauth_user['email']);
        $stmt->execute();
        
        // Obtener el ID del nuevo usuario
        $new_user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['email'] = $oauth_user['email'];
    }

    // Limpia las variables temporales y redirige si es necesario
    unset($_SESSION['oauth_user']);
    echo json_encode(["success" => true]);
    $stmt->close();
    $mysqli->close();
    exit();
}

// Cerrar la conexión si no se ha cerrado ya
if (isset($stmt)) {
    $stmt->close();
}
$mysqli->close();
?>
