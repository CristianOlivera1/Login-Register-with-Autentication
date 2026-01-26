<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codeoner</title> 
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="text-slate-400 selection:bg-[#0099ff] selection:text-white overflow-x-hidden">

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main id="app-content" class="flex-grow container mx-auto p-6">
        <?php echo $content; ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
    
    <?php include __DIR__ . '/../partials/auth-modal.php'; ?>

    <script src="/assets/js/spa.js"></script>
    <script src="/assets/js/auth.js"></script>
    <script src="/assets/js/iconify-icon.min.js"></script>
</body>

</html>