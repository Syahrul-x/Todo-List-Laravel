<!-- resources/views/auth/login.php -->

<div class="flex justify-center items-center min-h-screen bg-[#121212]">
  <div class="loginform w-full max-w-sm bg-[#1e1e1e] p-6 rounded-lg shadow-lg text-center">
    <form action="?c=auth&m=loginProcess" method="post">
      <div class="inputs text-left mb-4">
        <label for="name" class="block text-sm text-[#e0e0e0] mb-2">Username:</label>
        <input type="text" name="name" class="w-full p-3 bg-[#303030] text-[#e0e0e079] border-b-2 border-[#555] focus:border-[#007AFF]" required>
      </div>

      <div class="inputs text-left mb-4">
        <label for="password" class="block text-sm text-[#e0e0e0] mb-2">Password:</label>
        <input type="password" name="password" class="w-full p-3 bg-[#303030] text-[#e0e0e079] border-b-2 border-[#555] focus:border-[#007AFF]" required>
      </div>

      <button type="submit" class="button w-full bg-[#007AFF] text-white py-3 rounded-lg mt-4 hover:bg-transparent hover:text-[#007AFF] border-2 border-[#555]">
        Login
      </button>
    </form>

    <p class="text-[#e0e0e0] mt-4">Belum punya akun? <a href="?c=auth&m=register" class="text-[#007AFF] hover:text-[#005bb5]">Daftar di sini</a></p>
    <?php if (isset($error)): ?>
      <div class="text-red-500 text-center my-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>
</div>
