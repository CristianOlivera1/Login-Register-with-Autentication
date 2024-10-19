<?php
session_start();
include '../../controler/conexion.php';

// Obtener los datos enviados
$data = $_POST;
$userId = $_SESSION['user_id'];

// Verificar si se recibió una imagen
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    // Procesar la imagen y guardarla en el servidor
    $nombreArchivo = $_FILES['foto_perfil']['name'];
    $tmpName = $_FILES['foto_perfil']['tmp_name'];
    $carpetaDestino = '../../resources/profilePictures/';
    $rutaArchivo = $carpetaDestino . $userId . '_' . basename($nombreArchivo);

    if (move_uploaded_file($tmpName, $rutaArchivo)) {
        // Actualizar la foto de perfil en la base de datos
        $sqlUpdateFoto = "UPDATE usuario_detalles SET foto_perfil = '$rutaArchivo' WHERE user_id = '$userId'";
        if ($mysqli->query($sqlUpdateFoto) === TRUE) {
            $_SESSION['foto_perfil'] = $rutaArchivo;
            $fotoPerfilActualizada = true;
        }
    }
}

if ($data) {
    $nombre = $mysqli->real_escape_string($data['nombre']);
    $profesion = $mysqli->real_escape_string($data['profesion']);
    $ubicacion = $mysqli->real_escape_string($data['ubicacion']);
    $portafolio = $mysqli->real_escape_string($data['portafolio']);
    $linkedin = $mysqli->real_escape_string($data['linkedin']);
    $github = $mysqli->real_escape_string($data['github']);
    $perfil_desc = $mysqli->real_escape_string($data['perfil_desc']);
    $experiencia = $mysqli->real_escape_string($data['experiencia']);
    $habilidades = $mysqli->real_escape_string($data['habilidades']);
    $proyectos = $mysqli->real_escape_string($data['proyectos']);
    $educacion = $mysqli->real_escape_string($data['educacion']);
    $perfil_desc2 = $mysqli->real_escape_string($data['perfil_desc2']);
    $experiencia2 = $mysqli->real_escape_string($data['experiencia2']);
    $experiencia3 = $mysqli->real_escape_string($data['experiencia3']);
    $habilidades2 = $mysqli->real_escape_string($data['habilidades2']);
    $habilidades3 = $mysqli->real_escape_string($data['habilidades3']);
    $educacion2 = $mysqli->real_escape_string($data['educacion2']);
    $educacion3 = $mysqli->real_escape_string($data['educacion3']);

    // Comprobar si el usuario ya tiene un registro
    $checkQuery = "SELECT user_id FROM usuario_detalles WHERE user_id = '$userId'";
    $checkResult = $mysqli->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Si el registro ya existe, actualizar
        // Asignar un espacio en blanco si están vacíos (!importante para componentes ocultos)
        $perfil_desc2 = !empty($perfil_desc2) ? $perfil_desc2 : " ";
        $experiencia2 = !empty($experiencia2) ? $experiencia2 : " ";
        $experiencia3 = !empty($experiencia3) ? $experiencia3 : " ";
        $habilidades2 = !empty($habilidades2) ? $habilidades2 : " ";
        $habilidades3 = !empty($habilidades3) ? $habilidades3 : " ";
        $educacion2 = !empty($educacion2) ? $educacion2 : " ";
        $educacion3 = !empty($educacion3) ? $educacion3 : " ";


        $sqlUpdate = "UPDATE usuario_detalles SET 
                        nombre = '$nombre', 
                        profesion = '$profesion',
                        ubicacion = '$ubicacion',
                        portafolio = '$portafolio',
                        linkedin = '$linkedin',
                        github = '$github',
                        perfil_desc = '$perfil_desc',
                        experiencia = '$experiencia',
                        habilidades = '$habilidades',
                        proyectos = '$proyectos',
                        educacion = '$educacion',
                        perfil_desc2 = '$perfil_desc2',
                        experiencia2 = '$experiencia2',
                        experiencia3 = '$experiencia3',
                        habilidades2 = '$habilidades2',
                        habilidades3 = '$habilidades3',
                        educacion2 = '$educacion2',
                        educacion3 = '$educacion3'
                    WHERE user_id = '$userId'";
                    
        if ($mysqli->query($sqlUpdate) === TRUE) {
            // Actualizar las variables de sesión
            $_SESSION['nombre'] = $nombre;
            $_SESSION['profesion'] = $profesion;
            $_SESSION['ubicacion'] = $ubicacion;
            $_SESSION['portafolio'] = $portafolio;
            $_SESSION['linkedin'] = $linkedin;
            $_SESSION['github'] = $github;
            $_SESSION['perfil_desc'] = $perfil_desc;
            $_SESSION['experiencia'] = $experiencia;
            $_SESSION['habilidades'] = $habilidades;
            $_SESSION['proyectos'] = $proyectos;
            $_SESSION['educacion'] = $educacion;
            $_SESSION['perfil_desc2'] = $perfil_desc2;
            $_SESSION['experiencia2'] = $experiencia2;
            $_SESSION['experiencia3'] = $experiencia3;
            $_SESSION['habilidades2'] = $habilidades2;
            $_SESSION['habilidades3'] = $habilidades3;
            $_SESSION['educacion2'] = $educacion2;
            $_SESSION['educacion3'] = $educacion3;

            $response = ['success' => true, 'message' => 'Datos actualizados exitosamente'];
            
            if (isset($fotoPerfilActualizada) && $fotoPerfilActualizada) {
                $response['foto_perfil_url'] = $rutaArchivo;
            }

            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos: ' . $mysqli->error]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}

$mysqli->close();

?>
