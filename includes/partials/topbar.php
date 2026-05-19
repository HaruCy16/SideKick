<?php
/**
 * Top Navigation Bar Component
 * Includes: Search, Notifications, Theme Toggle, User Menu
 */

$current_user_name = $_SESSION['user_first_name'] ?? 'User';
$current_user_role = $_SESSION['user_role'] ?? 'user';
$user_initials = strtoupper(($_SESSION['user_first_name'][0] ?? 'U') . ($_SESSION['user_last_name'][0] ?? 'S'));
?>

<nav class="fixed top-0 left-64 right-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 h-16 flex items-center px-6 gap-4 shadow-sm">
    <!-- Search -->
    <div class="flex-1 max-w-xs">
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                   placeholder="Search projects, tasks..." 
                   class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all">
        </div>
    </div>

    <!-- Spacer -->
    <div class="flex-1"></div>

    <!-- Notification Bell -->
    <div class="relative group">
        <button class="relative p-2 text-gray-500 hover:text-gray-900 transition-colors" 
                id="notificationBell">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
    </div>

    <!-- Theme Toggle -->
    <button id="themeToggle" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 transition-colors" type="button">
        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
        <svg class="w-5 h-5 hidden dark:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 1.636l-.707.707M6.343 6.343l-.707-.707m12.728 0l-.707.707m-12.02 12.02l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </button>

    <script>
        // Initialize theme on page load
        (function() {
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('sidekick_theme') || 'light';
            
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        })();

        // Theme toggle functionality - works on all devices including iOS
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            
            if (themeToggle) {
                // Handle both click and touch events
                themeToggle.addEventListener('click', toggleTheme);
                themeToggle.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    toggleTheme(e);
                });
            }
        });

        function toggleTheme(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('sidekick_theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('sidekick_theme', 'dark');
            }
            
            // Force re-render to ensure all elements update
            html.style.display = 'none';
            setTimeout(() => {
                html.style.display = '';
            }, 0);
        }
    </script>

    <!-- User Menu -->
    <div class="relative group">
        <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors" 
                id="userMenuBtn">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                <?php echo htmlspecialchars($user_initials); ?>
            </div>
            <div class="hidden sm:block text-left">
                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($current_user_name); ?></p>
                <p class="text-xs text-gray-500 capitalize"><?php echo htmlspecialchars($current_user_role); ?></p>
            </div>
        </button>
    </div>
</nav>
