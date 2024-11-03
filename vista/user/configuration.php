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
    <title>CodeOner-Configuración</title>
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
                    <a href="configuration.php"><img src="../../resources/img/log-CO-minimalist.png" alt="logo" class="logo-co"></a>                 
                    <a href="profile.php" class="solo-autenticado"><span class="azul">C</span>ode<span class="celeste">O</span>ner</a>
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
                            <li class="separator"><hr class="dropdown-separator"></li>
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
        <h1> <img src="../../resources/icons/settings.png" alt=""> Configuración</h1>
        <p>Bienvenido, <span class="azul"><?php echo htmlspecialchars($nombre); ?></span>. Esta es la sección de tu configuracion.</p>
    </div>

    <div class="tabs-menu">
    <button class="tab-link active" data-tab="profile">
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="icon">
            <path d="m11.19 1.36l-7 3.11C3.47 4.79 3 5.51 3 6.3V11c0 5.55 3.84 10.74 9 12c5.16-1.26 9-6.45 9-12V6.3c0-.79-.47-1.51-1.19-1.83l-7-3.11c-.51-.23-1.11-.23-1.62 0M12 11.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11z" class="path"></path>
        </svg> Seguridad
    </button>

    <button class="tab-link" data-tab="theme">
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="icon">
            <g fill="none">
                <path fill="black" d="M2.75 12A9.25 9.25 0 0 0 12 21.25V2.75A9.25 9.25 0 0 0 2.75 12"/>
                <path stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21.25a9.25 9.25 0 0 0 0-18.5m0 18.5a9.25 9.25 0 0 1 0-18.5m0 18.5V2.75"/>
            </g>
        </svg> Tema
    </button>

    <button class="tab-link" data-tab="notifications">
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="icon">
            <path fill="black" d="M5 9a7 7 0 0 1 14 0v3.764l1.822 3.644A1.1 1.1 0 0 1 19.838 18h-3.964a4.002 4.002 0 0 1-7.748 0H4.162a1.1 1.1 0 0 1-.984-1.592L5 12.764zm5.268 9a2 2 0 0 0 3.464 0zM12 4a5 5 0 0 0-5 5v3.764a2 2 0 0 1-.211.894L5.619 16h12.763l-1.17-2.342a2 2 0 0 1-.212-.894V9a5 5 0 0 0-5-5"/>
        </svg> Notificaciones
    </button>

    <button class="tab-link" data-tab="social">
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="icon">
            <path fill="none" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5a2 2 0 1 0 4 0a2 2 0 1 0-4 0M3 19a2 2 0 1 0 4 0a2 2 0 1 0-4 0m14 0a2 2 0 1 0 4 0a2 2 0 1 0-4 0m-8-5a3 3 0 1 0 6 0a3 3 0 1 0-6 0m3-7v4m-5.3 6.8l2.8-2m7.8 2l-2.8-2"/>
        </svg> Redes Sociales
    </button>

    <button class="tab-link" data-tab="delete-account">
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="icon">
            <path fill="black" d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4zm2 2h6V4H9zM6.074 8l.857 12H17.07l.857-12zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1"/>
        </svg> Eliminar Cuenta
        </button>
    </div>
    <!-- Tabs Content -->
    <div class="tabs-content">

        <!-- Seguridad Tab -->
        <div id="profile" class="tab-content active">
            <h2>Cambiar contraseña</h2>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="seguridad">
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
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
            <div class="notification-content">
                <label>
                    <input type="checkbox" name="email_notifications" checked> Notificaciones por correo
                </label>
                <label>
                    <input type="checkbox" name="push_notifications"> Notificaciones push
                </label>
            </div>
        </div>

        <!-- Redes Sociales Tab -->
        <div id="social" class="tab-content">
            <h2>Conectar Redes Sociales</h2>
            <a href="#" class="social-button google"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 128 128"><path fill="#fff" d="M44.59 4.21a63.28 63.28 0 0 0 4.33 120.9a67.6 67.6 0 0 0 32.36.35a57.13 57.13 0 0 0 25.9-13.46a57.44 57.44 0 0 0 16-26.26a74.3 74.3 0 0 0 1.61-33.58H65.27v24.69h34.47a29.72 29.72 0 0 1-12.66 19.52a36.2 36.2 0 0 1-13.93 5.5a41.3 41.3 0 0 1-15.1 0A37.2 37.2 0 0 1 44 95.74a39.3 39.3 0 0 1-14.5-19.42a38.3 38.3 0 0 1 0-24.63a39.25 39.25 0 0 1 9.18-14.91A37.17 37.17 0 0 1 76.13 27a34.3 34.3 0 0 1 13.64 8q5.83-5.8 11.64-11.63c2-2.09 4.18-4.08 6.15-6.22A61.2 61.2 0 0 0 87.2 4.59a64 64 0 0 0-42.61-.38"></path><path fill="#e33629" d="M44.59 4.21a64 64 0 0 1 42.61.37a61.2 61.2 0 0 1 20.35 12.62c-2 2.14-4.11 4.14-6.15 6.22Q95.58 29.23 89.77 35a34.3 34.3 0 0 0-13.64-8a37.17 37.17 0 0 0-37.46 9.74a39.25 39.25 0 0 0-9.18 14.91L8.76 35.6A63.53 63.53 0 0 1 44.59 4.21"></path><path fill="#f8bd00" d="M3.26 51.5a63 63 0 0 1 5.5-15.9l20.73 16.09a38.3 38.3 0 0 0 0 24.63q-10.36 8-20.73 16.08a63.33 63.33 0 0 1-5.5-40.9"></path><path fill="#587dbd" d="M65.27 52.15h59.52a74.3 74.3 0 0 1-1.61 33.58a57.44 57.44 0 0 1-16 26.26c-6.69-5.22-13.41-10.4-20.1-15.62a29.72 29.72 0 0 0 12.66-19.54H65.27c-.01-8.22 0-16.45 0-24.68"></path><path fill="#319f43" d="M8.75 92.4q10.37-8 20.73-16.08A39.3 39.3 0 0 0 44 95.74a37.2 37.2 0 0 0 14.08 6.08a41.3 41.3 0 0 0 15.1 0a36.2 36.2 0 0 0 13.93-5.5c6.69 5.22 13.41 10.4 20.1 15.62a57.13 57.13 0 0 1-25.9 13.47a67.6 67.6 0 0 1-32.36-.35a63 63 0 0 1-23-11.59A63.7 63.7 0 0 1 8.75 92.4"></path>
            </svg>Conectar con Google</a>
            <a href="#" class="social-button github"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 64 64"><path fill="currentColor" d="M32 0C14 0 0 14 0 32c0 21 19 30 22 30c2 0 2-1 2-2v-5c-7 2-10-2-11-5c0 0 0-1-2-3c-1-1-5-3-1-3c3 0 5 4 5 4c3 4 7 3 9 2c0-2 2-4 2-4c-8-1-14-4-14-15q0-6 3-9s-2-4 0-9c0 0 5 0 9 4c3-2 13-2 16 0c4-4 9-4 9-4c2 7 0 9 0 9q3 3 3 9c0 11-7 14-14 15c1 1 2 3 2 6v8c0 1 0 2 2 2c3 0 22-9 22-30C64 14 50 0 32 0"/></svg>Conectar con GitHub</a>
            <a href="#" class="social-button facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 32 32"><path fill="currentColor" d="M32 16c0-8.839-7.167-16-16-16C7.161 0 0 7.161 0 16c0 7.984 5.849 14.604 13.5 15.803V20.626H9.437v-4.625H13.5v-3.527c0-4.009 2.385-6.223 6.041-6.223c1.751 0 3.584.312 3.584.312V10.5h-2.021c-1.984 0-2.604 1.235-2.604 2.5v3h4.437l-.713 4.625H18.5v11.177C26.145 30.603 32 23.983 32 15.999z"/></svg>Conectar con Facebook</a>
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
