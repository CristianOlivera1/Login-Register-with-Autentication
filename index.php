<?php
session_start();
$isAuthenticated = isset($_SESSION['user_id']);
// Capturar el mensaje de error y eliminarlo de la sesión
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Limpiar el mensaje de error después de mostrarlo
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Usuario";
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Nombre completo aqui'; 
$foto_perfil = isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : 'foto perfil';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeOner</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
   <div class="port">
    <img class="portada" src="resources/img/Animated Shape.svg" alt="portada">
    <img class="circle-lines" src="resources/img/circle-lines.svg" alt="pattern-lines">
   </div>
       <!-- Header -->
    <header class="header">
        <nav class="nav">
            <a href="index.php" class="nav_logo">CodeOner</a>

            <ul class="nav_items">
                <li class="nav_item">
                    <a href="../../index.php" class="nav_link">Inicio</a>
                    <a href="#" class="nav_link">Productos</a>
                    <a href="#" class="nav_link">Servicios</a>
                    <a href="#" class="nav_link">Contacto</a>
                </li>
            </ul>
            
            <?php if (!$isAuthenticated): ?>
                <!-- Mostrar botón de inicio de sesión si no está autenticado -->
                <button class="button-login" id="form-open">Iniciar sesión</button>
            <?php else: ?>
                 <!-- Mostrar el perfil si está autenticado -->
                <div class="profile-container">
                <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : 'resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil" class="profile-icon" id="profile-icon">
                    <div class="tooltip-user">
                        <span class="t-menu">Menu de usuario <br></span>
                        <span><?php echo htmlspecialchars($nombre); ?> <br></span> 
                        <span class="t-correo">@<?php echo htmlspecialchars($username); ?></span>
                    </div>
                    <!-- Menú desplegable -->
                    <div class="dropdown-menu" id="dropdown-menu">
                        <div class="perfil-dropdown">
                                <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] :  'resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil">
                                <div class="name-correo">
                                <div class="user-status">
                                    <span class="user-active"></span>
                                </div>
                                    <span><?php echo htmlspecialchars($nombre); ?></span> 
                                    <span>@<?php echo htmlspecialchars($username); ?></span>
                                </div>
                        </div>

                        <ul>
                            <li><a href="vista/user/profile.php"><img src="resources/icons/user.png" alt="icono-user-dropdown">Mi Perfil</a></li>
                            <li><a href="vista/user/configuration.php"><img src="resources/icons/settings.png" alt="icono-settings-dropdown">Configuración</a></li>
                            <li>
                                <button id="logoutButton" class="logout-button">
                                    <img src="resources/icons/logout.png" alt="icono-salir-dropdown">Cerrar sesión
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div id="logoutConfirmationModal" class="modal-confirmation">
                        <span class="close-button-logout" id="closeModalLogout">&times;</span>
                        <div class="modal-confirmation-content">
                        <img src="resources/icons/advertencia.png" alt="advertencia-salir">
                            <h1>¿ESTÁS A PUNTO DE CERRAR SESIÓN?</h1>
                            <p>!Si desea puede permanecer en la página!</p>
                            <div class="logout-control">
                                <button id="confirmLogoutButton" class="button-confirm">Salir</button>
                                <button id="cancelLogoutButton" class="button-cancel">Quedarme</button>
                            </div>
                        </div>
                    </div>
                </div>
             <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="hero">
            <img class="puntos" src="resources/img/circulo-puntos-med.png" alt="circle-lines">
            <img src="resources/img/Glow-frio.svg" alt="circle-port">
         <?php if (!$isAuthenticated): ?>
            <h2 class="titulo-main">Bienvenido, aquí encontrarás</h2>
            <p>Explora las diferentes opciones de <span class="bold">Inicio de sesión</span> y <span class="bold">Registro de usuario</span>, incluyendo métodos de autenticación tradicionales y modernos.</p>
            <button class="button-register" id="button-register">Regístrate ahora</button>
            <?php else: ?>
            <!-- Contenido predeterminado o una sección inicial para los usuarios autenticados -->
            <h2 class="titulo-main">Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h2>
            <p>Te has registrado exitosamente y ahora estás autenticado.</p>
        <?php endif; ?>
        </section>
    </main>

    <!-- Home -->
       <!-- Mostrar solo si no está autenticado -->
    <?php if (!$isAuthenticated): ?>
    <section class="home">
        <div class="form_container">
            <i class="uil uil-times form_close"></i>
            <!-- Login Form -->
            <div class="form login_form">
                <form id="login-form" action="login.php" method="POST">
                    <h2>Iniciar sesión</h2>
                    <div class="input_box">
                        <input type="email" name="email" placeholder="Ingresa tu email" required />
                        <i class="uil uil-envelope-alt email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" placeholder="Ingresa tu contraseña" required />
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide"></i>
                    </div>
                    <div class="option_field">
                        <div class="error-message" id="error-message" style="display: none;">
                            <p id="error-text"></p>
                        </div>
                        <a href="#" class="forgot_pw">Olvidaste tu contraseña?</a>
                    </div>
                    <button class="button">Iniciar sesión ahora</button>
                    <div class="login_signup">No tienes una cuenta? <a href="#" id="signup">Registrarse</a></div>
                    <div class="social_login">
                        <p>O inicia sesión con:</p>
                        <div class="social_icons">
                        <a href="https://accounts.google.com/o/oauth2/auth?client_id=906096618070-j70f5kdj3ra1ucfppfum9m6285drd9rb.apps.googleusercontent.com&redirect_uri=http://localhost:3000/loginRegister/callback/google.php&response_type=code&scope=email%20profile" data-tooltip="Iniciar sesión con Google">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 128 128"><path fill="#fff" d="M44.59 4.21a63.28 63.28 0 0 0 4.33 120.9a67.6 67.6 0 0 0 32.36.35a57.13 57.13 0 0 0 25.9-13.46a57.44 57.44 0 0 0 16-26.26a74.3 74.3 0 0 0 1.61-33.58H65.27v24.69h34.47a29.72 29.72 0 0 1-12.66 19.52a36.2 36.2 0 0 1-13.93 5.5a41.3 41.3 0 0 1-15.1 0A37.2 37.2 0 0 1 44 95.74a39.3 39.3 0 0 1-14.5-19.42a38.3 38.3 0 0 1 0-24.63a39.25 39.25 0 0 1 9.18-14.91A37.17 37.17 0 0 1 76.13 27a34.3 34.3 0 0 1 13.64 8q5.83-5.8 11.64-11.63c2-2.09 4.18-4.08 6.15-6.22A61.2 61.2 0 0 0 87.2 4.59a64 64 0 0 0-42.61-.38"/><path fill="#e33629" d="M44.59 4.21a64 64 0 0 1 42.61.37a61.2 61.2 0 0 1 20.35 12.62c-2 2.14-4.11 4.14-6.15 6.22Q95.58 29.23 89.77 35a34.3 34.3 0 0 0-13.64-8a37.17 37.17 0 0 0-37.46 9.74a39.25 39.25 0 0 0-9.18 14.91L8.76 35.6A63.53 63.53 0 0 1 44.59 4.21"/><path fill="#f8bd00" d="M3.26 51.5a63 63 0 0 1 5.5-15.9l20.73 16.09a38.3 38.3 0 0 0 0 24.63q-10.36 8-20.73 16.08a63.33 63.33 0 0 1-5.5-40.9"/><path fill="#587dbd" d="M65.27 52.15h59.52a74.3 74.3 0 0 1-1.61 33.58a57.44 57.44 0 0 1-16 26.26c-6.69-5.22-13.41-10.4-20.1-15.62a29.72 29.72 0 0 0 12.66-19.54H65.27c-.01-8.22 0-16.45 0-24.68"/><path fill="#319f43" d="M8.75 92.4q10.37-8 20.73-16.08A39.3 39.3 0 0 0 44 95.74a37.2 37.2 0 0 0 14.08 6.08a41.3 41.3 0 0 0 15.1 0a36.2 36.2 0 0 0 13.93-5.5c6.69 5.22 13.41 10.4 20.1 15.62a57.13 57.13 0 0 1-25.9 13.47a67.6 67.6 0 0 1-32.36-.35a63 63 0 0 1-23-11.59A63.7 63.7 0 0 1 8.75 92.4"/>
                            </svg>
                        </a>
                        <a href="https://github.com/login/oauth/authorize?client_id=Ov23liNvUKZQGJqi2by3&redirect_uri=http://localhost:3000/loginRegister/callback/github.php" data-tooltip="Iniciar sesión con GitHub">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill="black" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59c.4.07.55-.17.55-.38c0-.19-.01-.82-.01-1.49c-2.01.37-2.53-.49-2.69-.94c-.09-.23-.48-.94-.82-1.13c-.28-.15-.68-.52-.01-.53c.63-.01 1.08.58 1.23.82c.72 1.21 1.87.87 2.33.66c.07-.52.28-.87.51-1.07c-1.78-.2-3.64-.89-3.64-3.95c0-.87.31-1.59.82-2.15c-.08-.2-.36-1.02.08-2.12c0 0 .67-.21 2.2.82c.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82c.44 1.1.16 1.92.08 2.12c.51.56.82 1.27.82 2.15c0 3.07-1.87 3.75-3.65 3.95c.29.25.54.73.54 1.48c0 1.07-.01 1.93-.01 2.2c0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/v14.0/dialog/oauth?client_id=539492412104990&redirect_uri=http://localhost:3000/loginRegister/callback/facebook.php&scope=email,public_profile" data-tooltip="Iniciar sesión con Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="#1877f2" d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/><path fill="#fff" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z"/>
                            </svg>
                        </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Signup Form -->
            <div class="form signup_form">
                <form id="signup-form" action="register.php" method="POST">
                    <h2>Registrarse</h2>
                    <div class="input_box">
                        <input type="email" name="email" placeholder="Ingresa tu email" required />
                        <i class="uil uil-envelope-alt email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" placeholder="Crea una contraseña" required />
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="confirm_password" placeholder="Confirma tu contraseña" required />
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide"></i>
                    </div>
                  <!-- Mostrar el mensaje de error si existe aquí -->
                    <div class="error-message" id="signup-error-message" style="display: none;">
                        <p id="signup-error-text"></p>
                    </div>
                    <button class="button">Registrarse ahora</button>
                    <div class="login_signup">Ya tienes una cuenta? <a href="#" id="login">Iniciar sesión</a></div>
                    <div class="social_login">
                        <p>O regístrate con:</p>
                        <div class="social_icons">
                            <a href="https://accounts.google.com/o/oauth2/auth?client_id=906096618070-j70f5kdj3ra1ucfppfum9m6285drd9rb.apps.googleusercontent.com&redirect_uri=http://localhost:3000/loginRegister/callback/google.php&response_type=code&scope=email%20profile" data-tooltip="Registrarse con Google">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 128 128"><path fill="#fff" d="M44.59 4.21a63.28 63.28 0 0 0 4.33 120.9a67.6 67.6 0 0 0 32.36.35a57.13 57.13 0 0 0 25.9-13.46a57.44 57.44 0 0 0 16-26.26a74.3 74.3 0 0 0 1.61-33.58H65.27v24.69h34.47a29.72 29.72 0 0 1-12.66 19.52a36.2 36.2 0 0 1-13.93 5.5a41.3 41.3 0 0 1-15.1 0A37.2 37.2 0 0 1 44 95.74a39.3 39.3 0 0 1-14.5-19.42a38.3 38.3 0 0 1 0-24.63a39.25 39.25 0 0 1 9.18-14.91A37.17 37.17 0 0 1 76.13 27a34.3 34.3 0 0 1 13.64 8q5.83-5.8 11.64-11.63c2-2.09 4.18-4.08 6.15-6.22A61.2 61.2 0 0 0 87.2 4.59a64 64 0 0 0-42.61-.38"/><path fill="#e33629" d="M44.59 4.21a64 64 0 0 1 42.61.37a61.2 61.2 0 0 1 20.35 12.62c-2 2.14-4.11 4.14-6.15 6.22Q95.58 29.23 89.77 35a34.3 34.3 0 0 0-13.64-8a37.17 37.17 0 0 0-37.46 9.74a39.25 39.25 0 0 0-9.18 14.91L8.76 35.6A63.53 63.53 0 0 1 44.59 4.21"/><path fill="#f8bd00" d="M3.26 51.5a63 63 0 0 1 5.5-15.9l20.73 16.09a38.3 38.3 0 0 0 0 24.63q-10.36 8-20.73 16.08a63.33 63.33 0 0 1-5.5-40.9"/><path fill="#587dbd" d="M65.27 52.15h59.52a74.3 74.3 0 0 1-1.61 33.58a57.44 57.44 0 0 1-16 26.26c-6.69-5.22-13.41-10.4-20.1-15.62a29.72 29.72 0 0 0 12.66-19.54H65.27c-.01-8.22 0-16.45 0-24.68"/><path fill="#319f43" d="M8.75 92.4q10.37-8 20.73-16.08A39.3 39.3 0 0 0 44 95.74a37.2 37.2 0 0 0 14.08 6.08a41.3 41.3 0 0 0 15.1 0a36.2 36.2 0 0 0 13.93-5.5c6.69 5.22 13.41 10.4 20.1 15.62a57.13 57.13 0 0 1-25.9 13.47a67.6 67.6 0 0 1-32.36-.35a63 63 0 0 1-23-11.59A63.7 63.7 0 0 1 8.75 92.4"/>
                                </svg>
                            </a>
                            <a href="https://github.com/login/oauth/authorize?client_id=Ov23liNvUKZQGJqi2by3&redirect_uri=http://localhost:3000/loginRegister/callback/github.php" data-tooltip="Registrarse con GitHub">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill="black" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59c.4.07.55-.17.55-.38c0-.19-.01-.82-.01-1.49c-2.01.37-2.53-.49-2.69-.94c-.09-.23-.48-.94-.82-1.13c-.28-.15-.68-.52-.01-.53c.63-.01 1.08.58 1.23.82c.72 1.21 1.87.87 2.33.66c.07-.52.28-.87.51-1.07c-1.78-.2-3.64-.89-3.64-3.95c0-.87.31-1.59.82-2.15c-.08-.2-.36-1.02.08-2.12c0 0 .67-.21 2.2.82c.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82c.44 1.1.16 1.92.08 2.12c.51.56.82 1.27.82 2.15c0 3.07-1.87 3.75-3.65 3.95c.29.25.54.73.54 1.48c0 1.07-.01 1.93-.01 2.2c0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/v14.0/dialog/oauth?client_id=539492412104990&redirect_uri=http://localhost:3000/loginRegister/callback/facebook.php&scope=email,public_profile" data-tooltip="Registrarse con Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="#1877f2" d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/><path fill="#fff" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
    <script src="script.js"></script>
</body>
</html>
