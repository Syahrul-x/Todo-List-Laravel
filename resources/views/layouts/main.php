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

  <!-- Include Header -->
  <?php include __DIR__ . '/header.php'; ?>

  <!-- Main Content Section -->
  <main class="flex justify-center items-center min-h-screen p-4 sm:p-6 md:p-8">
    <div class="w-full max-w-4xl bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
      <?php include $viewFile; ?>
    </div>
  </main>

  <!-- Include Footer -->
  <?php include __DIR__ . '/footer.php'; ?>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('JavaScript is running');
        
        const userProfile = document.getElementById('userProfile');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!userProfile || !profileDropdown) {
            console.error('Dropdown or profile elements not found!');
        }

        // Toggle dropdown visibility when clicking the profile avatar
        userProfile.addEventListener('click', function (e) {
            console.log('Avatar clicked');
            e.stopPropagation(); // Prevent event from propagating to the document
            profileDropdown.classList.toggle('hidden'); // Toggle visibility of dropdown menu
        });

        // Close the dropdown if clicking outside of it
        document.addEventListener('click', function () {
            console.log('Document clicked');
            profileDropdown.classList.add('hidden'); // Hide the dropdown when clicking outside
        });

        // Prevent dropdown from closing when clicking inside the dropdown
        profileDropdown.addEventListener('click', function (e) {
            console.log('Dropdown clicked');
            e.stopPropagation();
        });
    });
</script>

</html>
