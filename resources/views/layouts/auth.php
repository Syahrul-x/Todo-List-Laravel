<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Auth Page') ?></title>
  <!-- Link to Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#121212] text-[#e0e0e0] font-mono">

  <main class="flex justify-center items-center min-h-screen">
    <?php include $viewFile; ?>
  </main>
  
  <!-- Footer -->
  <?php include __DIR__ . '/footer.php'; ?>

</body>
</html>
