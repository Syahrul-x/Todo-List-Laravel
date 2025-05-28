<!-- resources/views/auth/register.php -->

<div class="flex justify-center items-center min-h-screen bg-[#121212]">
  <div class="loginform w-full max-w-sm bg-[#1e1e1e] p-6 rounded-lg shadow-lg text-center">
    <form action="?c=auth&m=registerProcess" method="post">
      <!-- Username Field -->
      <div class="inputs text-left mb-4">
        <label for="name" class="block text-sm text-[#e0e0e0] mb-2">Username:</label>
        <input type="text" name="name" class="w-full p-3 bg-[#303030] text-[#e0e0e079] border-b-2 border-[#555] focus:border-[#007AFF]" required value="<?= htmlspecialchars($name ?? '') ?>">
      </div>

      <!-- Email Field -->
      <div class="inputs text-left mb-4">
        <label for="email" class="block text-sm text-[#e0e0e0] mb-2">Email:</label>
        <input type="email" name="email" class="w-full p-3 bg-[#303030] text-[#e0e0e079] border-b-2 border-[#555] focus:border-[#007AFF]" required value="<?= htmlspecialchars($email ?? '') ?>">
      </div>

      <!-- Password Field -->
      <div class="inputs text-left mb-4">
        <label for="password" class="block text-sm text-[#e0e0e0] mb-2">Password:</label>
        <input type="password" name="password" class="w-full p-3 bg-[#303030] text-[#e0e0e079] border-b-2 border-[#555] focus:border-[#007AFF]" required>
      </div>

      <!-- Confirm Password Field -->
      <div class="inputs text-left mb-4">
        <label for="confirm_password" class="block text-sm text-[#e0e0e0] mb-2">Confirm Password:</label>
        <input type="password" name="confirm_password" class="w-full p-3 bg-[#303030] text-[#e0e0e079] border-b-2 border-[#555] focus:border-[#007AFF]" required>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="button w-full bg-[#007AFF] text-white py-3 rounded-lg mt-4 hover:bg-transparent hover:text-[#007AFF] border-2 border-[#555]">
        Register
      </button>
    </form>

    <p class="text-[#e0e0e0] mt-4">Sudah punya akun? <a href="?c=auth&m=login" class="text-[#007AFF] hover:text-[#005bb5]">Login di sini</a></p>
    <?php if (isset($error)): ?>
      <div class="text-red-500 text-center my-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>
</div>
