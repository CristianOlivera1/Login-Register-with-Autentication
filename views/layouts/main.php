<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codeoner</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="/assets/img/logo-co.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=STIX+Two+Text:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="text-slate-400 selection:bg-[#0099ff] selection:text-white overflow-x-hidden">

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main id="app-content" class="flex-grow container mx-auto p-6">
        <?php echo $content; ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <?php include __DIR__ . '/../partials/auth-modal.php'; ?>

    <?php
    $currentPath = $_SERVER['REQUEST_URI'];
    $isPublicCV = preg_match('/^\/CV\/[a-z0-9\-]+$/', $currentPath);
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