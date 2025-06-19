<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'User';
$userInitials = strtoupper(substr($username, 0, 2));
?>

<header class="fixed top-0 left-0 w-full z-50 bg-[#2c2e31] shadow-lg p-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap">
        <a href="?c=dashboard&m=index" class="flex items-center mr-4">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                <rect width="30" height="30" rx="4" fill="#2684FF"/>
                <path d="M15.0001 5.33331C13.5856 5.33331 12.4445 6.47442 12.4445 7.88887C12.4445 9.30331 13.5856 10.4444 15.0001 10.4444C16.4145 10.4444 17.5556 9.30331 17.5556 7.88887C17.5556 6.47442 16.4145 5.33331 15.0001 5.33331Z" fill="white"/>
                <path d="M15.0001 19.5556C13.5856 19.5556 12.4445 20.6967 12.4445 22.1111C12.4445 23.5256 13.5856 24.6667 15.0001 24.6667C16.4145 24.6667 17.5556 23.5256 17.5556 22.1111C17.5556 20.6967 16.4145 19.5556 15.0001 19.5556Z" fill="white"/>
                <path d="M15.0001 12.4444C13.5856 12.4444 12.4445 13.5855 12.4445 15C12.4445 16.4144 13.5856 17.5555 15.0001 17.5555C16.4145 17.5555 17.5556 16.4144 17.5556 15C17.5556 13.5855 16.4145 12.4444 15.0001 12.4444Z" fill="white"/>
            </svg>
        </a>

        <nav class="hidden lg:flex space-x-4 flex-grow justify-center">
            <a href="?c=dashboard&m=index" class="text-white text-lg font-medium hover:bg-blue-700 hover:text-white px-3 py-2 border border-[#2e3235] rounded-md shadow-lg text-white">
                Dashboard
            </a>
            <a href="?c=category&m=index" class="text-white text-lg font-medium hover:bg-blue-700 hover:text-white px-3 py-2 border border-[#2e3235] rounded-md shadow-lg text-white">
                Category Management
            </a>
            <a href="?c=priority&m=index" class="text-white text-lg font-medium hover:bg-blue-700 hover:text-white px-3 py-2 border border-[#2e3235] rounded-md shadow-lg text-white">
                Priority Management
            </a>
        </nav>

        <select onchange="if(this.value) window.location.href=this.value" class="block lg:hidden bg-[#2c2e31] border border-gray-600 text-white rounded px-3 py-2 w-40">
            <option value="">Menu</option>
            <option value="?c=dashboard&m=index">Dashboard</option>
            <option value="?c=category&m=index">Category Management</option>
            <option value="?c=priority&m=index">Priority Management</option>
        </select>

        <div class="relative ml-4 mt-3 lg:mt-0 flex-shrink-0">
            <div id="userProfile" class="flex items-center justify-center w-10 h-10 rounded-full bg-[#ff5630] text-white cursor-pointer select-none">
                <?= $userInitials ?>
            </div>
            <div id="profileDropdown" class="dropdown-menu absolute right-0 mt-2 w-40 bg-[#2c2e31] border border-[#2e3235] rounded-md shadow-lg text-white hidden z-50">
                <a href="?c=profile&m=index" class="block px-4 py-2 hover:bg-[#34373a]">Profile</a>
                <a href="#" class="block px-4 py-2 hover:bg-[#34373a]">Settings</a>
                <a href="?c=auth&m=logout" class="block px-4 py-2 hover:bg-[#34373a] text-[#ff5630]">Logout</a>
            </div>
        </div>

    </div>
</header>