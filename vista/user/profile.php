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
                    <a href="profile.php"><img src="../../resources/img/log-CO-minimalist.png" alt="logo" class="logo-co"></a>                 
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

    <!-- Sección de perfil -->
    <img class="blob" src="../../resources/img/blob-profile.svg" alt="adorno">
    <div class="encabezado-perfil">
         <h1><img src="../../resources/icons/user.png" alt="icono-profile"> Mi perfil</h1>
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
                    <label class="name-ft">Foto de perfil:</label>
                    <div class="image-edit-container">
                    <div class="perfil-background-edit">
                        <div class="profile-image">
                            <img class="circulo-claro" src="../../resources/img/circle-clar.svg" alt="circulo claro">
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
                    </div>

                    <br>
                    <div class="form__div">
                    <input type="text" class="form__input" id="ubicacion-input" name="ubicacion" placeholder=" " spellcheck="false">
                    <label for="ubicacion-input" class="form__label">Ubicación</label>
                    </div>

                    <div class="form__div">
                        <input type="text" class="form__input" id="portafolio-input" name="portafolio" placeholder=" " spellcheck="false">
                        <label for="portafolio-input" class="form__label">Portafolio</label>
                    </div>

                    <div class="form__div">
                        <input type="text" class="form__input" id="linkedin-input" name="linkedin" placeholder=" " spellcheck="false">
                        <label for="linkedin-input" class="form__label">LinkedIn</label>
                    </div>

                    <div class="form__div">
                        <input type="text" class="form__input" id="github-input" name="github" placeholder=" " spellcheck="false">
                        <label for="github-input" class="form__label">GitHub</label>
                    </div>

                    <label for="perfil-desc-input">Descripción del Perfil:
                        <button aria-label="agregar otro parrafo" title="Agregar otro parrafo" class="button-mas" id="agregar-desc" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.5 20a1.5 1.5 0 0 0 3 0v-6.5H20a1.5 1.5 0 0 0 0-3h-6.5V4a1.5 1.5 0 0 0-3 0v6.5H4a1.5 1.5 0 0 0 0 3h6.5z"/></g></svg>
                        </button>
                    </label>
                    <textarea id="perfil-desc-input" name="perfil_desc" placeholder="Breve descripción sobre ti" spellcheck="false"></textarea>
                    <textarea  id="perfil-desc-input2" name="perfil_desc2" placeholder="Descripción adicional" style="display:none;" spellcheck="false"></textarea>
                    
                </div>
                <div class="form-column">            
                    <label for="experiencia-input">Experiencia:
                        <button aria-label="agregar otro parrafo" title="Agregar otro parrafo" class="button-mas" id="agregar-exp" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.5 20a1.5 1.5 0 0 0 3 0v-6.5H20a1.5 1.5 0 0 0 0-3h-6.5V4a1.5 1.5 0 0 0-3 0v6.5H4a1.5 1.5 0 0 0 0 3h6.5z"/></g></svg>
                        </button>
                    </label>
                    <textarea id="experiencia-input" name="experiencia" placeholder="Resumen de tu experiencia laboral" spellcheck="false"></textarea>
                    <textarea id="experiencia-input2" name="experiencia2" placeholder="Resumen de tu experiencia laboral" spellcheck="false" style="display:none;"></textarea>
                    <textarea id="experiencia-input3" name="experiencia3" placeholder="Resumen de tu experiencia laboral" spellcheck="false" style="display:none;"></textarea>

                    <label for="habilidades-input">Habilidades:
                        <button aria-label="agregar otro parrafo" title="Agregar otro parrafo" class="button-mas" id="agregar-habil" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.5 20a1.5 1.5 0 0 0 3 0v-6.5H20a1.5 1.5 0 0 0 0-3h-6.5V4a1.5 1.5 0 0 0-3 0v6.5H4a1.5 1.5 0 0 0 0 3h6.5z"/></g></svg>
                        </button>
                    </label>
                    <input type="text" id="habilidades-input" name="habilidades" placeholder="Ej. HTML, CSS, JS">
                    <input type="text" id="habilidades-input2" name="habilidades" placeholder="Ej. PHP, Java, C#" style="display: none;">
                    <input type="text" id="habilidades-input3" name="habilidades" placeholder="Ej. Figma, Canva, GitHub" style="display: none;">

                    <label for="proyectos-input">Proyectos:<button aria-label="agregar otro parrafo" title="Agregar otro parrafo" class="button-mas" id="agregar-proyecto" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.5 20a1.5 1.5 0 0 0 3 0v-6.5H20a1.5 1.5 0 0 0 0-3h-6.5V4a1.5 1.5 0 0 0-3 0v6.5H4a1.5 1.5 0 0 0 0 3h6.5z"/></g></svg>
                        </button></label>

                    <div class="textarea-container">
                        <textarea type="text" id="proyectos-input" name="proyectos" placeholder="Proyecto 1 ..." spellcheck="false"></textarea>
                        <input type="text" id="proyectos-input-link" placeholder="Enlace del proyecto (opcional)">
                    </div>

                    <div class="textarea-container" id="container-proyecto2" style="display:none;">
                        <textarea type="text" id="proyectos-input2" name="proyectos2" placeholder="Proyecto 2 ..." spellcheck="false"></textarea>
                        <input type="text" id="proyectos-input-link2" placeholder="Enlace del proyecto 2 (opcional)">
                    </div>

                    <div class="textarea-container" id="container-proyecto3" style="display:none;">
                        <textarea type="text" id="proyectos-input3" name="proyectos3" placeholder="Proyecto 3 ..." spellcheck="false"></textarea>
                        <input type="text" id="proyectos-input-link3" placeholder="Enlace del proyecto 3 (opcional)">
                    </div>

                    <label for="educacion-input">Educación: <button aria-label="agregar otro parrafo" title="Agregar otro parrafo" class="button-mas" id="agregar-educacion" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.5 20a1.5 1.5 0 0 0 3 0v-6.5H20a1.5 1.5 0 0 0 0-3h-6.5V4a1.5 1.5 0 0 0-3 0v6.5H4a1.5 1.5 0 0 0 0 3h6.5z"/></g></svg>
                    </button></label>

                     <input type="text" id="educacion-input" name="educacion" placeholder="Ej. Licenciatura en Computación" spellcheck="false">

                     <input type="text" id="educacion-input2" name="educacion" placeholder="Ej. PHP, Java, C#" style="display: none;">

                     <input type="text" id="educacion-input3" name="educacion" placeholder="Ej. PHP, Java, C#" style="display: none;">
                </div>
            </div>
            <button class="button-save-edit" id="saveChangesButton" type="button">Guardar Cambios</button>
            </form>
    </div>
</div>
<div id="updateSuccessModal" class="modal-update-success">
    <div class="modal-update-content">
        <span class="modal-update-close">&times;</span>
        <div class="modal-update-body">
        <h2>Datos actualizados correctamente</h2>
        <img src="../../resources/gif/check-animation.gif" alt="Cargando..." class="modal-update-gif">
        <button id="continueUpdateBtn" class="modal-update-button">Continuar</button>
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


