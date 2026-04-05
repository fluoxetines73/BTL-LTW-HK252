<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Công Ty ABC') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php if (!empty($extraHead)) echo $extraHead; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">

    <?php include APPROOT . '/Views/layouts/header.php'; ?>

    <main class="mx-auto w-full max-w-6xl px-4 py-6 md:px-6 lg:px-8">
        <?php include APPROOT . '/Views/' . $content . '.php'; ?>
    </main>

    <?php include APPROOT . '/Views/layouts/footer.php'; ?>
    <script src="<?= BASE_URL ?>public/js/app.js"></script>
    <?php if (!empty($extraScripts)) echo $extraScripts; ?>

</body>
</html>
