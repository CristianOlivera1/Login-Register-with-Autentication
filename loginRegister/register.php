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

        $insert_details_query = "INSERT INTO usuario_detalles (user_id, foto_perfil, ubicacion, portafolio, linkedin, github, perfil_desc, perfil_desc2, experiencia, experiencia2, experiencia3, habilidades, habilidades2, habilidades3, proyectos, proyectos_link, proyectos2, proyectos_link2, proyectos3, proyectos_link3, educacion, educacion2, educacion3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_details = $mysqli->prepare($insert_details_query);

        if (!$stmt_details) {
            echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $mysqli->error]);
            exit();
        }

        $stmt_details->bind_param("issssssssssssssssssssss", $userId, $profileImageUrl, $ubicacion, $portafolio, $linkedin, $github, $perfil_desc, $perfil_desc2, $experiencia, $experiencia2, $experiencia3, $habilidades, $habilidades2, $habilidades3, $proyectos, $proyectos_link, $proyectos2, $proyectos_link2, $proyectos3, $proyectos_link3, $educacion, $educacion2, $educacion3);

        if ($stmt_details->execute()) {
            // Guardar detalles en la sesión
            $_SESSION['user_id'] = $userId;
            $_SESSION['foto_perfil'] = $profileImageUrl;
            $_SESSION['ubicacion'] = $ubicacion;
            $_SESSION['portafolio'] = $portafolio;
            $_SESSION['linkedin'] = $linkedin;
            $_SESSION['github'] = $github;
            $_SESSION['email'] = $email;

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