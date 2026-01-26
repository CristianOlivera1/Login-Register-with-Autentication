<?php
session_start();
include('../../controler/conexion.php'); 

try {
    if ($mysqli->connect_errno) {
        echo "Error de conexión a la base de datos: " . $mysqli->connect_error;
        exit();
    }

        // Obtén el correo electrónico desde el parámetro de la URL
        $email = isset($_GET['email']) ? $_GET['email'] : null;

        // Verifica si el correo está presente
        if (!$email) {
            echo "No se ha especificado un usuario.";
            exit();
        }

    // Consulta principal para obtener la información del usuario
    $stmt = $mysqli->prepare("
        SELECT ud.nombre, ud.profesion, ud.ubicacion, 
               ud.portafolio, ud.linkedin, ud.github, ud.perfil_desc, ud.experiencia, 
               ud.habilidades, ud.proyectos, ud.educacion, ud.foto_perfil, 
               ud.perfil_desc2, ud.experiencia2, ud.experiencia3, ud.habilidades2, 
               ud.habilidades3, ud.educacion2, ud.educacion3, ud.proyectos_link, 
               ud.proyectos2, ud.proyectos_link2, ud.proyectos3, ud.proyectos_link3
        FROM usuarios u
        LEFT JOIN usuario_detalles ud ON u.id = ud.user_id
        WHERE u.email = ?
    ");
    
    // Verifica si la consulta se preparó correctamente
    if ($stmt === false) {
        echo "Error en la preparación de la consulta: " . $mysqli->error;
        exit();
    }

     // Asigna el parámetro y ejecuta la consulta
     $stmt->bind_param("s", $email);
     $stmt->execute();
     $result = $stmt->get_result();

    // Obtiene el resultado
    if ($user = $result->fetch_assoc()) {
        // Guarda los datos en la sesión
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['profesion'] = $user['profesion'];
        $_SESSION['ubicacion'] = $user['ubicacion'];
        $_SESSION['portafolio'] = $user['portafolio'];
        $_SESSION['linkedin'] = $user['linkedin'];
        $_SESSION['github'] = $user['github'];
        $_SESSION['perfil_desc'] = $user['perfil_desc'];
        $_SESSION['experiencia'] = $user['experiencia'];
        $_SESSION['habilidades'] = $user['habilidades'];
        $_SESSION['proyectos'] = $user['proyectos'];
        $_SESSION['educacion'] = $user['educacion'];
        $_SESSION['foto_perfil'] = $user['foto_perfil'];
        $_SESSION['perfil_desc2'] = $user['perfil_desc2'];
        $_SESSION['experiencia2'] = $user['experiencia2'];
        $_SESSION['experiencia3'] = $user['experiencia3'];
        $_SESSION['habilidades2'] = $user['habilidades2'];
        $_SESSION['habilidades3'] = $user['habilidades3'];
        $_SESSION['educacion2'] = $user['educacion2'];
        $_SESSION['educacion3'] = $user['educacion3'];
        $_SESSION['proyectos_link'] = $user['proyectos_link'];
        $_SESSION['proyectos2'] = $user['proyectos2'];
        $_SESSION['proyectos_link2'] = $user['proyectos_link2'];
        $_SESSION['proyectos3'] = $user['proyectos3'];
        $_SESSION['proyectos_link3'] = $user['proyectos_link3'];
    } else {
        echo "No se encontró información para el usuario buscado.";
    }

    $stmt->close();
} catch (Exception $e) {
    echo "Ocurrió un error: " . $e->getMessage();
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeOner-Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../style.css">
</head>
<body class="body-profile">
     <!-- Header -->
     <header class="header">
        <nav class="nav">
        <div class="logo-amburguer">
            <div class="hamburger-menu" id="hamburger-menu">
                <!-- Ícono de menú hamburguesa -->
                <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu">
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
                
                <!-- Ícono de "X" -->
                <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide" style="display: none;">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </div>
                <div class="logo">
                    <a href="#"><img src="../../resources/img/log-CO-minimalist.png" alt="logo" class="logo-co"></a>                 
                    <a href="#" class="solo-autenticado"><span class="azul">C</span>ode<span class="celeste">O</span>ner</a>
                    <style>
                        @media (max-width: 480px) {
                            .solo-autenticado {
                                display: none;
                            }
                        }
                    </style>
                </div>
           </div>
            <ul class="nav_items" id="nav-items">
                <li class="nav_item">
                    <a href="../../index.php" class="nav_link">Inicio</a>
                    <a href="#" class="nav_link">Productos</a>
                    <a href="#" class="nav_link">Servicios</a>
                    <a href="#" class="nav_link">Contacto</a>
                </li>
            </ul>
                <!-- Mostrar botón de inicio de sesión si no está autenticado -->
                 <div class="btn-header">
                 <a href="../../index.php"><button class="button-login">Iniciar sesión</button></a>
                 <a href="../../index.php"><button class="button-register-header">Registrarse</button></a>
                </div>
        </nav>
    </header>

    <!-- Sección de perfil -->
    <img class="blob" src="../../resources/img/blob-profile.svg" alt="adorno">
    <div class="encabezado-perfil">
         <h1><img src="../../resources/icons/user.png" alt="icono-profile"> Mi perfil</h1>
        <div class="encabezado">
            <p>¡Hola, Visitante, este es mi perfil generado automaticamente al registrarme en <b>CodeOner</b>.</p>
            <div class="controles">
            <a href="../../index.php"><button>Registrarse</button></a>
            </div>
        </div>
    </div>

    <div class="perfil-container">  
    <div class="column">
        <div class="perfil-pagina">
            <h1 id="nombre-completo">
            <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Nombre completo'; ?>
            </h1>
            <h2 id="profesion">
                <?php echo isset($_SESSION['profesion']) ? $_SESSION['profesion'] : 'Nombre de Profesión'; ?>
            </h2>
            <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : '../../resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil" class="foto-perfil" id="foto-perfil">
        </div>

        <div class="info-adicional">
            <div class="icon-links">
                <?php if (!empty($_SESSION['ubicacion'])): ?>
                    <p>
                        <a href="<?php echo isset($_SESSION['ubicacion']) ? $_SESSION['ubicacion'] : 'https://github.com/CristianOlivera1'; ?>" id="ubicacion" target="_blank">
                            <i class="fas fa-map-marker-alt"></i>
                         Ubicación
                        </a>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($_SESSION['portafolio'])): ?>
                    <p>
                        <a href="<?php echo isset($_SESSION['portafolio']) ? $_SESSION['portafolio'] : 'https://github.com/CristianOlivera1'; ?>" id="portafolio" target="_blank">
                            <i class="fas fa-link"></i>
                          Portafolio
                        </a>
                    </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['linkedin'])): ?>
                    <p>
                        <a href="<?php echo isset($_SESSION['linkedin']) ? $_SESSION['linkedin'] : 'https://github.com/CristianOlivera1'; ?>" id="linkedin" target="_blank">
                            <i class="fab fa-linkedin"></i>
                          LinkedIn
                        </a>
                    </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['github'])): ?>
                    <p>
                        <a href="<?php echo isset($_SESSION['github']) ? $_SESSION['github'] : 'https://github.com/CristianOlivera1'; ?>" id="github" target="_blank">
                            <i class="fab fa-github"></i>
                           GitHub
                        </a>
                    </p>
                <?php endif; ?>
            </div>

            <div class="perfil">
                <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="black" fill-rule="evenodd" d="M4 4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm10 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1m0 3a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1m0 3a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1m-8-5a3 3 0 1 1 6 0a3 3 0 0 1-6 0m1.942 4a3 3 0 0 0-2.847 2.051l-.044.133l-.004.012c-.042.126-.055.167-.042.195c.006.013.02.023.038.039c.032.025.08.064.146.155A1 1 0 0 0 6 17h6a1 1 0 0 0 .811-.415a.7.7 0 0 1 .146-.155c.019-.016.031-.026.038-.04c.014-.027 0-.068-.042-.194l-.004-.012l-.044-.133A3 3 0 0 0 10.059 14z" clip-rule="evenodd"/></svg>Perfil</h3>

                <?php if (!empty($_SESSION['perfil_desc'])): ?>
                    <p id="perfil-desc">
                        <?php echo $_SESSION['perfil_desc']; ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['perfil_desc2'])): ?>
                    <p id="perfil-desc2">
                        <?php echo $_SESSION['perfil_desc2']; ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="experiencia">
                <h3><i class="fas fa-briefcase"></i>Experiencia</h3>

                <?php if (!empty($_SESSION['experiencia'])): ?>
                <p id="experiencia">
                    <?php echo $_SESSION['experiencia'];?>
                </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['experiencia2'])): ?>
                <p id="experiencia2"> 
                    <?php echo $_SESSION['experiencia2'];?>
                </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['experiencia3'])): ?>
                <p id="experiencia3">
                    <?php echo $_SESSION['experiencia3'];?>
                </p>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="column">
        <div class="skills">
            <h3><svg xmlns="http://www.w3.org/2000/svg" width="22px" viewBox="0 0 24 24"><path fill="black" d="m19 1l-1.26 2.75L15 5l2.74 1.26L19 9l1.25-2.74L23 5l-2.75-1.25M9 4L6.5 9.5L1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5M19 15l-1.26 2.74L15 19l2.74 1.25L19 23l1.25-2.75L23 19l-2.75-1.26"/></svg>Habilidades</h3>
            <ul class="habilidades">
                <?php if (!empty($_SESSION['habilidades'])): ?> 
                <li id="habilidades">
                    <?php echo $_SESSION['habilidades'];?>
                </li>
                <?php endif; ?>

                <?php if (!empty($_SESSION['habilidades2'])): ?> 
                <li id="habilidades2"> 
                    <?php echo $_SESSION['habilidades2'];?>
                </li>
                <?php endif; ?>

                <?php if (!empty($_SESSION['habilidades3'])): ?> 
                <li id="habilidades3"> 
                    <?php echo $_SESSION['habilidades3'];?>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="proyectos">
            <h3><svg xmlns="http://www.w3.org/2000/svg" width="22px" viewBox="0 0 24 24"><path fill="black" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8H4v10l2.4-8h17.1l-2.575 8.575q-.2.65-.737 1.038T19 20z"/></svg>Proyectos</h3>
            
                <?php if (!empty($_SESSION['proyectos'])): ?> 
                <p id="proyectos"><?php echo $_SESSION['proyectos']; ?>
                    <?php if (!empty(trim($_SESSION['proyectos_link']))): ?> 
                        <a href="<?php echo $_SESSION['proyectos_link']; ?>" id="proyectos-link" target="_blank">Ver Proyecto</a>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($_SESSION['proyectos2'])): ?> 
                <p id="proyectos2"><?php echo $_SESSION['proyectos2']; ?>
                    <?php if (!empty(trim($_SESSION['proyectos_link2']))): ?> 
                        <a href="<?php echo $_SESSION['proyectos_link2']; ?>" id="proyectos-link2" target="_blank">Ver Proyecto</a>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($_SESSION['proyectos3'])): ?> 
                <p id="proyectos3"><?php echo $_SESSION['proyectos3']; ?>
                    <?php if (!empty(trim($_SESSION['proyectos_link3']))): ?> 
                        <a href="<?php echo $_SESSION['proyectos_link3']; ?>" id="proyectos-link3" target="_blank">Ver Proyecto</a>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

        </div>

        <div class="educacion">
            <h3><svg xmlns="http://www.w3.org/2000/svg" width="22px" viewBox="0 0 20 20"><path fill="black" d="M3.33 8L10 12l10-6l-10-6L0 6h10v2zM0 8v8l2-2.22V9.2zm10 12l-5-3l-2-1.2v-6l7 4.2l7-4.2v6z"/></svg>Educación</h3>

            <?php if (!empty($_SESSION['educacion'])): ?>
            <p id="educacion">
                <?php echo $_SESSION['educacion'];?>
            </p>
            <?php endif; ?>

            <?php if (!empty($_SESSION['educacion2'])): ?>
            <p id="educacion2"> 
                <?php echo $_SESSION['educacion2'];?>
            </p>
            <?php endif; ?>

            <?php if (!empty($_SESSION['educacion3'])): ?>
            <p id="educacion3">
                <?php echo $_SESSION['educacion3'];?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>
   <!-- Sección de perfil -->

      <!--footer seccion-->
      <footer class="footer">
        <div class="contact">
        <a href="mailto:221181@unamba.edu.pe">221181@unamba.edu.pe</a>
        </div>

        <div class="social-media">
        <a href="https://www.instagram.com/" aria-label="instagram" title="Instagram" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram">
            <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
            </svg>
        </a>
        <a href="mailto:221181@unamba.edu.pe" aria-label="correo" title="Correo" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail">
            <rect width="20" height="16" x="2" y="4" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            </svg>
        </a>
        <a href="https://github.com/CristianOlivera1" aria-label="github" title="GitHub" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-github">
            <path
                d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4" />
            <path d="M9 18c-4.51 2-5-2-7-2" />
            </svg>
        </a>
        <a href="https://maps.app.goo.gl/9k7tmKApCFtAYfvJ9" aria-label="ubicación" title="Ubicación" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin">
            <path
                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
            <circle cx="12" cy="10" r="3" />
            </svg>
        </a>
        </div>

        <div class="footer-links">
        <ul>
            <li><a href="#">Sobre mí</a></li>
            <li><a href="#">Proyectos Destacados</a></li>
            <li><a href="#">Recursos Útiles</a></li>
        </ul>
        </div>

        <div class="template-by">
        <p> <span class="diseñadoPor">TEMPLATE BY</span><span class="your-name">CRISTIAN</span></p>
        </div>
    </footer>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.4/randomColor.min.js'></script>
    <script src="../../script.js"></script>
    <script src="script.js"></script>
</body>
</html>


