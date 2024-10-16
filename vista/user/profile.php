<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Usuario';
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Nombre Completo Aqui'; 
$foto_perfil = isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : 'foto perfil';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <span class="t-menu">Menu de usuario<br></span>
                        <span><?php echo htmlspecialchars($nombre); ?> <br></span> 
                        <span class="t-correo">@<?php echo htmlspecialchars($username); ?></span>
                    </div>
                    <!-- Menú desplegable -->
                    <div class="dropdown-menu" id="dropdown-menu">
                          <div class="perfil-dropdown">
                                  <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : '../../resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil">
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

    <!-- Sección de perfil -->
    <img class="blob" src="../../resources/img/blob-profile.svg" alt="adorno">
    <div class="encabezado-perfil">
         <h1>Mi perfil</h1>
        <div class="encabezado">
            <p>¡Hola, <span class="azul"><?php echo htmlspecialchars($nombre); ?>!</span>, ¡Bienvenido a tu perfil! Aquí puedes ver toda la información sobre ti.</p>
            <div class="controles">
              <button id="editar-perfil" onclick="abrirModal()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>Editar</button>
            </div>
        </div>
    </div>

    <div class="perfil-container">  
    <div class="column">
        <div class="perfil-pagina">
            <h1 id="nombre-completo">
                <?php echo htmlspecialchars($nombre); ?>
            </h1>
            <h2 id="profesion">
                <?php echo isset($_SESSION['profesion']) ? $_SESSION['profesion'] : 'Nombre de Profesión'; ?>
            </h2>
            <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : '../../resources/icons/user-defecto.png'; ?>" alt="Foto de Perfil" class="foto-perfil" id="foto-perfil">
        </div>

        <div class="info-adicional">
            <div class="icon">
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
                <p id="perfil-desc">
                    <?php echo isset($_SESSION['perfil_desc']) ? $_SESSION['perfil_desc'] : 'Aquí puedes agregar una breve descripción o resumen sobre ti, tus intereses, y cualquier otra información relevante que quieras compartir.'; ?>
                </p>
            </div>
            <div class="experiencia">
                <h3><i class="fas fa-briefcase"></i>Experiencia</h3>

                <p id="experiencia">
                    <?php echo isset($_SESSION['experiencia']) ? $_SESSION['experiencia'] : 'Ingresa la Información relevante sobre la empresa laborada. 
                    - Tecnologías: Ingresa las tecnologías que utilizadas. 
                    - Responsabilidades: Describe tu rol que tuviste en la empresa. 
                    - Logros: Menciona los diferentes logros que ayudaron a la empresa y/o logros que influyeron en ti mismo.'; ?>
                </p>
                <p id="experiencia2">
                    <?php echo isset($_SESSION['experiencia_2']) ? $_SESSION['experiencia_2'] :'Ingresa la Información relevante sobre la empresa laborada. 
                    - Tecnologías: Ingresa las tecnologías que utilizadas. 
                    - Responsabilidades: Describe tu rol que tuviste en la empresa. 
                    - Logros: Menciona los diferentes logros que ayudaron a la empresa y/o logros que influyeron en ti mismo.'; ?>
                </p>
                <p id="experiencia3">
                    <?php echo isset($_SESSION['experiencia_3']) ? $_SESSION['experiencia_3'] : 'Ingresa la Información relevante sobre la empresa laborada. 
                    - Tecnologías: Ingresa las tecnologías que utilizadas. 
                    - Responsabilidades: Describe tu rol que tuviste en la empresa. 
                    - Logros: Menciona los diferentes logros que ayudaron a la empresa y/o logros que influyeron en ti mismo.'; ?>
                </p>

            </div>
        </div>
    </div>

    <div class="column">
        <div class="skills">
            <h3><svg xmlns="http://www.w3.org/2000/svg" width="22px" viewBox="0 0 24 24"><path fill="black" d="m19 1l-1.26 2.75L15 5l2.74 1.26L19 9l1.25-2.74L23 5l-2.75-1.25M9 4L6.5 9.5L1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5M19 15l-1.26 2.74L15 19l2.74 1.25L19 23l1.25-2.75L23 19l-2.75-1.26"/></svg>Habilidades</h3>
            <ul id="habilidades">
            <li><?php echo isset($_SESSION['habilidades']) ? $_SESSION['habilidades'] : 'HTML, CSS, JavaScript'; ?></li>
                <!-- Puedes repetir para los otros ítems de habilidades -->
            </ul>
        </div>

        <div class="proyectos">
            <h3><svg xmlns="http://www.w3.org/2000/svg" width="22px" viewBox="0 0 24 24"><path fill="black" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8H4v10l2.4-8h17.1l-2.575 8.575q-.2.65-.737 1.038T19 20z"/></svg>Proyectos</h3>
           
            <p id="proyectos"><?php echo isset($_SESSION['proyectos']) ? $_SESSION['proyectos'] : 'Nombre del Proyecto - Breve descripción del proyecto (tecnologías utilizadas, objetivo del proyecto). <a href="#">Ver Proyecto</a>'; ?></p>
            <p id="proyectos2"><?php echo isset($_SESSION['proyecto_2']) ? $_SESSION['proyecto_2'] : 'Nombre del Proyecto - Breve descripción del proyecto (tecnologías utilizadas, objetivo del proyecto). <a href="#">Ver Proyecto</a>'; ?></p>
            <p id="proyectos3"><?php echo isset($_SESSION['proyecto_3']) ? $_SESSION['proyecto_3'] : 'Nombre del Proyecto - Breve descripción del proyecto (tecnologías utilizadas, objetivo del proyecto). <a href="#">Ver Proyecto</a>'; ?></p>

        </div>

        <div class="educacion">
            <h3><svg xmlns="http://www.w3.org/2000/svg" width="22px" viewBox="0 0 20 20"><path fill="black" d="M3.33 8L10 12l10-6l-10-6L0 6h10v2zM0 8v8l2-2.22V9.2zm10 12l-5-3l-2-1.2v-6l7 4.2l7-4.2v6z"/></svg>Educación</h3>
            <p id="educacion">
            <?php echo isset($_SESSION['educacion']) ? $_SESSION['educacion'] : 'Título del grado, especialización - Nombre de la universidad (año de graduación)'; ?>
            </p>
            <p id="educacion">
            <?php echo isset($_SESSION['educacion_2']) ? $_SESSION['educacion_2'] : 'Título del grado, especialización - Nombre de la universidad (año de graduación)'; ?>
            </p>
            <p id="educacion">
            <?php echo isset($_SESSION['educacion_3']) ? $_SESSION['educacion_3'] : 'Título del grado, especialización - Nombre de la universidad (año de graduación)'; ?>
            </p>

        </div>
    </div>
</div>
    
<!-- Modal para editar perfil -->
<div id="modal-editar" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2><img src="../../resources/icons/user-pen.png" alt="icono-titulo-edit"> <span class="azul">Editar Perfil</span></h2>
        <form id="editar-form">
            <div class="form-row">
                <div class="form-column">   
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez" spellcheck="false">

                    <label for="profesion-input">Profesión:</label>
                    <input type="text" id="profesion-input" name="profesion" placeholder="Ej. Desarrollador Web" spellcheck="false">

                    <!-- Sección para editar la foto de perfil -->
                    <div class="image-edit-container">
                        <div class="profile-image">
                        <label class="name-ft">Foto de perfil:</label>
                            <!-- Imagen de perfil con ícono de lápiz -->
                            <img src="<?php echo isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : '../../resources/icons/user-defecto.png'; ?>" 
                                alt="Foto de Perfil" class="profile-icon-edit" id="profile-icon-edit">
                                <div class="svg-overlay">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="13" r="3"/>
                                            <path stroke-linecap="round" d="M2 13.364c0-3.065 0-4.597.749-5.697a4.4 4.4 0 0 1 1.226-1.204c.72-.473 1.622-.642 3.003-.702c.659 0 1.226-.49 1.355-1.125A2.064 2.064 0 0 1 10.366 3h3.268c.988 0 1.839.685 2.033 1.636c.129.635.696 1.125 1.355 1.125c1.38.06 2.282.23 3.003.702c.485.318.902.727 1.226 1.204c.749 1.1.749 2.632.749 5.697s0 4.596-.749 5.697a4.4 4.4 0 0 1-1.226 1.204C18.904 21 17.343 21 14.222 21H9.778c-3.121 0-4.682 0-5.803-.735A4.4 4.4 0 0 1 2.75 19.06A3.4 3.4 0 0 1 2.277 18M19 10h-1"/>
                                        </g>
                                    </svg>
                                </div>
                        
                            <label for="profileImage" class="edit-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/>
                                </svg>
                                <span class="tooltip-edit">Subir nueva imagen de perfil</span>
                            </label>
                            <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none;">
                        </div>
                    </div>
                    <br>
                    <label for="ubicacion-input">Ubicación:</label>
                    <input type="text" id="ubicacion-input" name="ubicacion" placeholder="Ej. https://maps.google.com/?q=Ciudad,País" spellcheck="false">

                    <label for="portafolio-input">Portafolio:</label>
                    <input type="text" id="portafolio-input" name="portafolio" placeholder="Ej. www.miportafolio.com" spellcheck="false">

                    <label for="linkedin-input">LinkedIn:</label>
                    <input type="text" id="linkedin-input" name="linkedin" placeholder="Ej. linkedin.com/in/usuario" spellcheck="false">

                    <label for="github-input">GitHub:</label>
                    <input type="text" id="github-input" name="github" placeholder="Ej. github.com/usuario" spellcheck="false">
                </div>
                <div class="form-column">
                    <label for="perfil-desc-input">Descripción del Perfil:</label>
                    <textarea id="perfil-desc-input" name="perfil-desc" placeholder="Breve descripción sobre ti" spellcheck="false"></textarea>

                    <label for="experiencia-input">Experiencia:</label>
                    <textarea id="experiencia-input" name="experiencia" placeholder="Resumen de tu experiencia laboral" spellcheck="false"></textarea>

                    <label for="habilidades-input">Habilidades:</label>
                    <input type="text" id="habilidades-input" name="habilidades" placeholder="Ej. HTML, CSS, JS">

                    <label for="proyectos-input">Proyectos:</label>
                    <textarea type="text" id="proyectos-input" name="proyectos" placeholder="Ej. Proyecto 1, Proyecto 2" spellcheck="false"></textarea>
                </div>
            </div>

            <div class="form-row full-width">
                <label for="educacion-input">Educación:</label>
                <input type="text" id="educacion-input" name="educacion" placeholder="Ej. Licenciatura en Computación" spellcheck="false">
            </div>

            <button type="button" onclick="guardarCambios()">Guardar Cambios</button>
        </form>
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


