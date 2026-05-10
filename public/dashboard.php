<?php
/**
 * Dashboard - Main page after login
 * Protected page - requires authentication
 */

// Load configuration and auth guard
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

// Check if user is authenticated
if (!is_authenticated()) {
    header('Location: /SideKick/public/login.php');
    exit;
}

$user = get_auth_user();
if (!$user || !is_array($user)) {
    session_destroy();
    header('Location: /SideKick/public/login.php');
    exit;
}

// Get user's full name and initial
$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
if (!$fullName) {
    $fullName = $user['email'];
}
$userInitials = substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'S', 0, 1);
$userInitials = strtoupper($userInitials);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sidekick</title>
    <link rel="stylesheet" href="../assets/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white font-sans transition-all duration-250">
    <div class="grid grid-cols-[280px_1fr] min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 w-[280px] h-screen bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-r border-slate-200 dark:border-slate-700 p-6 overflow-y-auto z-40">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8 p-2 rounded-lg">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">SK</div>
                <div class="text-xl font-bold text-indigo-600">Sidekick</div>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col gap-2 mb-4">
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent active:text-indigo-600 active:bg-indigo-100/50 dark:active:bg-indigo-950/30 active:border-indigo-600 active:pl-2">
                    <i class="fas fa-grid-3x3 w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent">
                    <i class="fas fa-briefcase w-5 text-center"></i>
                    <span>Projects</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent">
                    <i class="fas fa-file-lines w-5 text-center"></i>
                    <span>Notes</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent">
                    <i class="fas fa-check-square w-5 text-center"></i>
                    <span>Task</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span>Analytics</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Team</span>
                </a>
            </nav>

            <div class="h-px bg-slate-200 dark:bg-slate-700 my-4"></div>

            <nav class="flex flex-col gap-2 mb-4">
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition-all duration-250 border-l-3 border-transparent">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="h-36"></div>

            <!-- User Card -->
            <div class="fixed bottom-6 left-4 right-4 w-[calc(280px-32px)] p-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-indigo-50/80 dark:hover:bg-indigo-950/20 hover:border-indigo-600 transition-all duration-250 z-50">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-semibold flex-shrink-0"><?php echo htmlspecialchars($userInitials); ?></div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-slate-900 dark:text-white truncate"><?php echo htmlspecialchars($fullName); ?></div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <div class="w-5 text-center text-slate-500 dark:text-slate-400">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-[280px] p-6 bg-slate-50 dark:bg-slate-950 overflow-y-auto lg:ml-0 lg:p-4">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Dashboard</h1>
                <p class="text-base text-slate-600 dark:text-slate-400">Welcome back! Here's what's happening with your projects.</p>
            </div>

            <!-- Overview Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Card 1 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 rounded-xl p-6 hover:shadow-lg dark:hover:shadow-slate-950/50 hover:-translate-y-0.5 transition-all duration-250">
                    <div class="flex items-start justify-between mb-4">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Projects</span>
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-950/30 rounded-lg flex items-center justify-center text-xl text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white mb-2">12</div>
                    <div class="text-xs text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i> +2 from last month
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 rounded-xl p-6 hover:shadow-lg dark:hover:shadow-slate-950/50 hover:-translate-y-0.5 transition-all duration-250">
                    <div class="flex items-start justify-between mb-4">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Tasks Pending</span>
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-950/30 rounded-lg flex items-center justify-center text-xl text-amber-600 dark:text-amber-400">
                            <i class="fas fa-check-square"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white mb-2">8</div>
                    <div class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                        <i class="fas fa-arrow-down"></i> -3 from last month
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 rounded-xl p-6 hover:shadow-lg dark:hover:shadow-slate-950/50 hover:-translate-y-0.5 transition-all duration-250">
                    <div class="flex items-start justify-between mb-4">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Completed</span>
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-950/30 rounded-lg flex items-center justify-center text-xl text-emerald-600 dark:text-emerald-400">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white mb-2">45</div>
                    <div class="text-xs text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i> +5 from last month
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 rounded-xl p-6 hover:shadow-lg dark:hover:shadow-slate-950/50 hover:-translate-y-0.5 transition-all duration-250">
                    <div class="flex items-start justify-between mb-4">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Earnings</span>
                        <div class="w-10 h-10 bg-pink-100 dark:bg-pink-950/30 rounded-lg flex items-center justify-center text-xl text-pink-600 dark:text-pink-400">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white mb-2">$12,450</div>
                    <div class="text-xs text-pink-600 dark:text-pink-400 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i> +15% from last month
                    </div>
                </div>
            </div>

            <!-- Recent Projects Section -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Recent Projects</h2>
                <a href="#" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-all duration-250 hover:-translate-y-0.5 hover:shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>New Project</span>
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 rounded-xl overflow-hidden mb-8">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/50 dark:bg-indigo-950/20 border-b border-slate-200 dark:border-slate-700">
                            <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Project Name</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Client</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Due Date</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/10 transition-colors duration-250">
                            <td class="px-4 py-4 text-sm text-slate-900 dark:text-white">Website Redesign</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Acme Corp</td>
                            <td class="px-4 py-4 text-sm"><span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 rounded-md text-xs font-medium"><i class="fas fa-circle-notch"></i> In Progress</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Mar 14, 2025</td>
                            <td class="px-4 py-4 text-sm"><div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-250" style="width: 65%;"></div></div></td>
                        </tr>
                        <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/10 transition-colors duration-250">
                            <td class="px-4 py-4 text-sm text-slate-900 dark:text-white">Mobile App Development</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Tech Startup</td>
                            <td class="px-4 py-4 text-sm"><span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 rounded-md text-xs font-medium"><i class="fas fa-circle-notch"></i> In Progress</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Apr 20, 2025</td>
                            <td class="px-4 py-4 text-sm"><div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-250" style="width: 40%;"></div></div></td>
                        </tr>
                        <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/10 transition-colors duration-250">
                            <td class="px-4 py-4 text-sm text-slate-900 dark:text-white">Database Optimization</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">E-Commerce Co</td>
                            <td class="px-4 py-4 text-sm"><span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 rounded-md text-xs font-medium"><i class="fas fa-check"></i> Completed</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Mar 1, 2025</td>
                            <td class="px-4 py-4 text-sm"><div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-250" style="width: 100%;"></div></div></td>
                        </tr>
                        <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/10 transition-colors duration-250">
                            <td class="px-4 py-4 text-sm text-slate-900 dark:text-white">Marketing Campaign</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Brand Agency</td>
                            <td class="px-4 py-4 text-sm"><span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 rounded-md text-xs font-medium"><i class="fas fa-clock"></i> Pending</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Mar 20, 2025</td>
                            <td class="px-4 py-4 text-sm"><div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-250" style="width: 0%;"></div></div></td>
                        </tr>
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-950/10 transition-colors duration-250">
                            <td class="px-4 py-4 text-sm text-slate-900 dark:text-white">UI/UX Design System</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Design Studio</td>
                            <td class="px-4 py-4 text-sm"><span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 rounded-md text-xs font-medium"><i class="fas fa-circle-notch"></i> In Progress</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">Apr 5, 2025</td>
                            <td class="px-4 py-4 text-sm"><div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-250" style="width: 75%;"></div></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Theme Toggle Button -->
    <button class="fixed bottom-6 right-6 w-12 h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full flex items-center justify-center text-xl transition-all duration-250 hover:scale-110 hover:shadow-lg z-30" id="theme-toggle" aria-label="Toggle dark mode">
        <i class="fas fa-moon"></i>
    </button>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        
        function initTheme() {
            const theme = localStorage.getItem('theme') || 'light';
            applyTheme(theme);
        }

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-theme', 'dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', 'light');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
            localStorage.setItem('theme', theme);
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
        });

        // Initialize
        initTheme();
    </script>
</body>
</html>
