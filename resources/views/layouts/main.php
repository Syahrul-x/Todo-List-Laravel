<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Auth Page') ?></title>
  <!-- Link to Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
  <body class="bg-[#121212] text-[#e0e0e0] font-mono pt-20">

    <!-- Include Header -->
    <?php include __DIR__ . '/header.php'; ?>

    <!-- Main Content Section -->
    <main class="flex justify-center items-center min-h-screen p-4 sm:p-6 md:p-8">
      <div class="w-full bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
        <?php include $viewFile; ?>
      </div>
    </main>

    <!-- Include Footer -->
    <?php include __DIR__ . '/footer.php'; ?>

  </body>
</html>
