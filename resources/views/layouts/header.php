<?php
// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil username dari session
$username = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'User';
$userInitials = strtoupper(substr($username, 0, 2));
?>

<header class="flex flex-col lg:flex-row justify-between items-center p-4 bg-[#2c2e31] shadow-lg">
    <!-- Logo -->
<div class="flex items-center space-x-3 mb-4 lg:mb-0">
    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
        <rect width="30" height="30" rx="4" fill="#2684FF"/>
        <path d="M15.0001 5.33331C13.5856 5.33331 12.4445 6.47442 12.4445 7.88887C12.4445 9.30331 13.5856 10.4444 15.0001 10.4444C16.4145 10.4444 17.5556 9.30331 17.5556 7.88887C17.5556 6.47442 16.4145 5.33331 15.0001 5.33331Z" fill="white"/>
        <path d="M15.0001 19.5556C13.5856 19.5556 12.4445 20.6967 12.4445 22.1111C12.4445 23.5256 13.5856 24.6667 15.0001 24.6667C16.4145 24.6667 17.5556 23.5256 17.5556 22.1111C17.5556 20.6967 16.4145 19.5556 15.0001 19.5556Z" fill="white"/>
        <path d="M15.0001 12.4444C13.5856 12.4444 12.4445 13.5855 12.4445 15C12.4445 16.4144 13.5856 17.5555 15.0001 17.5555C16.4145 17.5555 17.5556 16.4144 17.5556 15C17.5556 13.5855 16.4145 12.4444 15.0001 12.4444Z" fill="white"/>
    </svg>
    <a href="?c=dashboard&m=index" class="text-white text-xl font-semibold no-underline hover:text-gray-300 transition-colors">To do list</a>
</div>

    <!-- Navigation and User Profile -->
    <div class="flex items-center space-x-4 w-full lg:w-auto justify-between lg:justify-end">
        
        <!-- Search Bar -->
        <div class="relative mb-4 lg:mb-0 w-full lg:w-auto">
            <input type="text" class="bg-[#303030] text-[#e0e0e0] px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full lg:w-auto" placeholder="Search">
        </div>

        <!-- User Profile Avatar -->
        <div class="relative">
            <!-- Avatar -->
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#ff5630] text-white cursor-pointer" id="userProfile">
                <?php echo $userInitials; ?>
            </div>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu absolute top-10 right-0 bg-[#2c2e31] border border-[#2e3235] rounded-md w-40 hidden text-white shadow-lg" id="profileDropdown">
                <a href="?c=profile&m=index" class="block px-4 py-2 hover:bg-[#34373a]">Profile</a>
                <a href="#" class="block px-4 py-2 hover:bg-[#34373a]">Settings</a>
                <a href="?c=auth&m=logout" class="block px-4 py-2 hover:bg-[#34373a] text-[#ff5630]">Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userProfile = document.getElementById('userProfile');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!userProfile || !profileDropdown) {
            console.error('Dropdown or profile elements not found!');
            return;
        }

        // Toggle dropdown visibility when clicking the profile avatar
        userProfile.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent event from propagating to the document
            profileDropdown.classList.toggle('hidden'); // Toggle visibility of dropdown menu
        });

        // Close the dropdown if clicking outside of it
        document.addEventListener('click', function () {
            profileDropdown.classList.add('hidden'); // Hide the dropdown when clicking outside
        });

        // Prevent dropdown from closing when clicking inside the dropdown
        profileDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
</script>