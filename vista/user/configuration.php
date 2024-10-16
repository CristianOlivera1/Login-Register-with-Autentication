<?php
session_start();
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Nombre Completo Aqui'; 
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Usuario';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Usuario no encontrado';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../style.css">
</head>
<body class="body-profile">
     <!-- Header -->
     <header class="header">
        <nav class="nav">
            <a href="../../index.php" class="nav_logo">CodeOner</a>

            <ul class="nav_items">
                <li class="nav_item">
                    <a href="../../index.php" class="nav_link">Inicio</a>
                    <a href="#" class="nav_link">Productos</a>
                    <a href="#" class="nav_link">Servicios</a>
                    <a href="#" class="nav_link">Contacto</a>
                </li>
            </ul>

                 <!-- Mostrar el perfil si está autenticado -->
                <div class="profile-container">
                <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : '../../resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil" class="profile-icon" id="profile-icon">
                    <div class="tooltip-user">
                        <span class="t-menu">Menu de usuario <br></span>
                        <span><?php echo htmlspecialchars($nombre); ?> <br></span> 
                        <span class="t-correo">@<?php echo htmlspecialchars($username); ?></span>
                    </div>
                    <!-- Menú desplegable -->
                    <div class="dropdown-menu" id="dropdown-menu">
                        <div class="perfil-dropdown">
                                <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] :  '../../resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil">
                                <div class="name-correo">
                                <div class="user-status">
                                    <span class="user-active"></span>
                                </div>
                                    <span><?php echo htmlspecialchars($nombre); ?></span> 
                                    <div class="correo-active">
                                    <span>@<?php echo htmlspecialchars($username); ?></span>
                                    </div>
                                </div>
                        </div>
                        <ul>
                            <li><a href="profile.php"><img src="../../resources/icons/user.png" alt="icono-user-dropdown">Mi Perfil</a></li>
                            <li><a href="configuration.php"><img src="../../resources/icons/settings.png" alt="icono-settings-dropdown">Configuración</a></li>
                            <li>
                                <button id="logoutButton" class="logout-button">
                                    <img src="../../resources/icons/logout.png" alt="icono-salir-dropdown">Cerrar sesión
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div id="logoutConfirmationModal" class="modal-confirmation">
                        <div class="modal-confirmation-content">
                        <span class="close-button-logout" id="closeModalLogout">&times;</span>
                            <img src="../../resources/icons/advertencia.png" alt="advertencia-salir">
                            <h1>¿ESTÁS A PUNTO DE CERRAR SESIÓN?</h1>
                            <p>!Si desea puede permanecer en la página!</p>
                            <div class="logout-control">
                                <button id="confirmLogoutButton" class="button-confirm">Salir</button>
                                <button id="cancelLogoutButton" class="button-cancel">Quedarme</button>
                            </div>
                        
                        </div>
                    </div>
                </div>
        </nav>
    </header>

    <!-- Sección de configuration -->
    <img class="blob-configuration" src="../../resources/img/blob-configuration.svg" alt="adorno">
    <div class="title-configuration">
        <h1>Configuración</h1>
        <p>Bienvenido, <span class="azul"><?php echo htmlspecialchars($nombre); ?></span>. Esta es la sección de tu configuracion.</p>
    </div>

        <div class="tabs-menu">
        <button class="tab-link active" data-tab="profile">Seguridad</button>
        <button class="tab-link" data-tab="theme">Tema</button>
        <button class="tab-link" data-tab="notifications">Notificaciones</button>
        <button class="tab-link" data-tab="social">Redes Sociales</button>
        <button class="tab-link" data-tab="delete-account">Eliminar Cuenta</button>
    </div>

    <!-- Tabs Content -->
    <div class="tabs-content">

        <!-- Seguridad Tab -->
        <div id="profile" class="tab-content active">
            <h2>Seguridad</h2>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="seguridad">
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="********">
                </div>

                <button type="submit" class="save-button">Guardar Cambios</button>
            </form>
        </div>

        <!-- Tema Tab -->
        <div id="theme" class="tab-content">
            <h2>Preferencias de Tema</h2>
            <label class="switch">
                <input type="checkbox" id="theme-toggle">
                <span class="slider round"></span>
            </label>
            <p>Cambiar a modo oscuro</p>
        </div>

        <!-- Notificaciones Tab -->
        <div id="notifications" class="tab-content">
            <h2>Notificaciones</h2>
            <label>
                <input type="checkbox" name="email_notifications" checked> Notificaciones por correo
            </label>
            <label>
                <input type="checkbox" name="push_notifications"> Notificaciones push
            </label>
        </div>

        <!-- Redes Sociales Tab -->
        <div id="social" class="tab-content">
            <h2>Conectar Redes Sociales</h2>
            <a href="#" class="social-button facebook">Conectar con Facebook</a>
            <a href="#" class="social-button twitter">Conectar con Twitter</a>
            <a href="#" class="social-button google">Conectar con Google</a>
        </div>

        <!-- Eliminar Cuenta Tab -->
        <div id="delete-account" class="tab-content">
            <h2>Eliminar Cuenta</h2>
            <p>Si decides eliminar tu cuenta, perderás todos tus datos y no podrás recuperarlos.</p>
            <button class="delete-button">Eliminar mi cuenta</button>
        </div>

    </div>
   <!-- Sección de configuration -->

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
