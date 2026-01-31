<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeOner - Generador de CV Profesional y Plataforma de Autenticación</title>

    <meta name="description" content="Crea CVs profesionales con CodeOner. Plantillas Harvard, Modern Chronological y más. Sistema completo de autenticación social con Google, GitHub y Facebook.">
    <meta name="keywords" content="generador cv, curriculum vitae, plantillas cv, cv profesional, harvard template, autenticación social, login google, github, facebook">
    <meta name="author" content="Cristian Olivera Chávez">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#00e1ff">

    <meta property="og:title" content="CodeOner - Generador de CV Profesional">
    <meta property="og:description" content="Crea CVs profesionales con plantillas Harvard y Modern Chronological. Sistema completo de autenticación y gestión de perfiles.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $_ENV['APP_URL'] ?? 'http://localhost:3000'; ?>">
    <meta property="og:image" content="https://raw.githubusercontent.com/CristianOlivera1/Resources-dev/main/any/preview-codeoner.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="CodeOner">
    <meta property="og:locale" content="es_ES">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="CodeOner - Generador de CV Profesional">
    <meta name="twitter:description" content="Crea CVs profesionales con plantillas Harvard y Modern Chronological.">
    <meta name="twitter:image" content="https://raw.githubusercontent.com/CristianOlivera1/Resources-dev/main/any/preview-codeoner.png">
    <link rel="canonical" href="<?php echo $_ENV['APP_URL'] ?? 'http://localhost:3000'; ?><?php echo $_SERVER['REQUEST_URI']; ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="icon" href="/assets/img/logo-co.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="https://raw.githubusercontent.com/CristianOlivera1/Resources-dev/main/logoCO/logo-codeoner-180.png">

    <link href="https://fonts.googleapis.com/css2?family=STIX+Two+Text:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="/assets/css/style.css" rel="stylesheet">

    <meta name="google-site-verification" content="3TrWGT_VRXdTepYdZK9fOd_ZXWc3B7XN_mQb2Rm1EtU">
</head>

<body class="text-slate-400 selection:bg-[#0099ff] selection:text-white overflow-x-hidden">

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main id="app-content" class="flex-grow container mx-auto p-3">
        <?php echo $content; ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <?php include __DIR__ . '/../partials/auth-modal.php'; ?>

    <?php
    $currentPath = $_SERVER['REQUEST_URI'];
    $isPublicCV = preg_match('/^\/(CV|cv)\/[a-z0-9\-]+/', $currentPath);
    $isGenerator = strpos($currentPath, '/generate-cv') !== false;
    ?>

    <?php if (!$isPublicCV): ?>
        <script src="/assets/js/spa.js"></script>
    <?php endif; ?>

    <script src="/assets/js/auth.js"></script>
    <script src="/assets/js/custom-select.js" defer></script>

    <?php if ($isGenerator): ?>
        <script src="/assets/js/cv-generator.js" defer></script>
    <?php endif; ?>
    <script src="/assets/js/iconify-icon.min.js"></script>
</body>

</html>