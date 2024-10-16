<?php
session_start(); 
include '../controler/conexion.php';
// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validación de campos
    if (strlen($password) < 8) {
        echo json_encode(["success" => false, "message" => "La contraseña debe tener al menos 8 caracteres"]);
        exit();
    }

    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden"]);
        exit();
    }

    // Validación del formato del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "El correo electrónico no tiene un formato válido"]);
        exit();
    }

    // Verificar si el correo ya existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está registrado"]);
        exit();
    }

    // Si todo es correcto, inserta el nuevo usuario
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (email, password) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);
    if ($stmt->execute()) {
        // Obtener el ID del usuario recién creado
        $userId = $mysqli->insert_id;

        // Insertar detalles en la tabla "usuario_detalles"
        $profileImageUrl = "https://img.freepik.com/vector-premium/icono-usuario-simple-3d-aislado_169241-6922.jpg"; 
        $ubicacion = "https://www.google.com/maps/place/Abancay,+Apur%C3%ADmac,+Per%C3%BA";
        $portafolio = "https://usuario-portafolio.com";
        $linkedin = "https://www.linkedin.com/in/usuario";
        $github = "https://github.com/usuario";

        $insert_details_query = "INSERT INTO usuario_detalles (user_id, foto_perfil, ubicacion, portafolio, linkedin, github) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_details = $mysqli->prepare($insert_details_query);

        if (!$stmt_details) {
            echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $mysqli->error]);
            exit();
        }

        $stmt_details->bind_param("isssss", $userId, $profileImageUrl, $ubicacion, $portafolio, $linkedin, $github);

        if ($stmt_details->execute()) {
            // Guardar detalles en la sesión
            $_SESSION['user_id'] = $userId;
            $_SESSION['foto_perfil'] = $profileImageUrl;
            $_SESSION['ubicacion'] = $ubicacion;
            $_SESSION['portafolio'] = $portafolio;
            $_SESSION['linkedin'] = $linkedin;
            $_SESSION['github'] = $github;
            $_SESSION['email'] = $email;

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al insertar los detalles del usuario: " . $stmt_details->error]);
        }

        $stmt_details->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar el usuario."]);
    }

    $stmt->close();
}

$mysqli->close();
?>