<?php
session_start(); 

// Verifica si el nombre de usuario está disponible
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Usuario";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agradecimiento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="container-logo">
            <img src="https://github.com/CristianOlivera1/Resources-dev/raw/main/logoCO/log-CO-minimalist.png" alt="Logo de la Plataforma" class="logo">
        </div>
        <h1>¡Gracias por registrarte, <span class="azul"><?php echo htmlspecialchars($username); ?></span>!</h1>
        <img src="../resources/gif/check-animation.gif" alt="Imagen de Agradecimiento" class="thank-you-image">
        <p>Tu registro ha sido exitoso. Ahora ya puedes acceder a la Plataforma.</p>
        <a href="../index.php" class="btn">
          Volver a inicio
        </a>
    </div>

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
</body>
</html>
