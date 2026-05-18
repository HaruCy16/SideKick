
<?php
// Sidebar component for dashboard pages
// Accepts: $current_role (admin, manager, freelancer, client)
// Accepts: $current_page (dashboard, projects, notes, tasks, analytics, team, settings)

$role = isset($current_role) ? $current_role : $_SESSION['user_role'] ?? 'freelancer';
$page = isset($current_page) ? $current_page : 'dashboard';

// Define standard navigation items for all roles
// Each role has the same base menu structure for consistency
$base_urls = [
    'admin' => '../admin',
    'manager' => '../project_manager',
    'freelancer' => '../freelancer',
    'client' => '../client'
];

$base_url = $base_urls[$role] ?? $base_urls['freelancer'];

// Standardized navigation for all roles
$items = [
    ['label' => 'Dashboard', 'icon' => 'chart-pie', 'url' => $base_url . '/dashboard.php', 'key' => 'dashboard'],
    ['label' => 'Projects', 'icon' => 'briefcase', 'url' => $base_url . '/projects.php', 'key' => 'projects'],
    ['label' => 'Notes', 'icon' => 'document', 'url' => $base_url . '/notes.php', 'key' => 'notes'],
    ['label' => 'Tasks', 'icon' => 'clipboard-list', 'url' => $base_url . '/tasks.php', 'key' => 'tasks'],
    ['label' => 'Analytics', 'icon' => 'chart-bar', 'url' => $base_url . '/analytics.php', 'key' => 'analytics'],
    ['label' => 'Team', 'icon' => 'users', 'url' => $base_url . '/team.php', 'key' => 'team'],
    ['label' => 'Settings', 'icon' => 'cog', 'url' => $base_url . '/settings.php', 'key' => 'settings'],
];

// Helper function to get Font Awesome icon
function get_icon($icon_name) {
    $icons = [
        'chart-pie' => 'fas fa-chart-pie',
        'briefcase' => 'fas fa-briefcase',
        'document' => 'fas fa-file-lines',
        'clipboard-list' => 'fas fa-list-check',
        'chart-bar' => 'fas fa-chart-bar',
        'users' => 'fas fa-users',
        'cog' => 'fas fa-cog',
        'dashboard' => 'fas fa-gauge',
        'tasks' => 'fas fa-tasks',
        'analytics' => 'fas fa-chart-line',
    ];
    return $icons[$icon_name] ?? 'fas fa-gauge';
}
?>

<!-- Sidebar Toggle Button (Mobile) -->
<button id="sidebarToggle" type="button" class="inline-flex items-center p-2 mt-3 ms-3 text-sm text-slate-900 dark:text-white rounded-lg sm:hidden hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:focus:ring-slate-600">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
</button>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 transform -translate-x-full sm:translate-x-0 transition-all sm:transition-none">
    <!-- Sidebar Header with Logo -->
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">SK</div>
            <span class="font-bold text-slate-900 dark:text-white">SideKick</span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="px-4 py-6 overflow-y-auto">
        <ul class="space-y-2">
            <?php foreach ($items as $item): ?>
                <li>
                    <a href="<?php echo htmlspecialchars($item['url']); ?>" class="flex items-center px-4 py-2.5 rounded-lg transition-colors <?php echo ($page === $item['key']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'; ?>">
                        <i class="<?php echo get_icon($item['icon']); ?> w-5 text-slate-500 dark:text-slate-400 <?php echo ($page === $item['key']) ? 'text-blue-600 dark:text-blue-400' : ''; ?>"></i>
                        <span class="ms-3 font-medium"><?php echo htmlspecialchars($item['label']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Sidebar Footer with User Profile -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <!-- User Profile Card -->
        <div class="flex items-center gap-3 px-4 py-3 rounded-lg bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 mb-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                <?php
                    $first_initial = isset($_SESSION['user_first_name']) ? strtoupper($_SESSION['user_first_name'][0]) : 'U';
                    $last_initial = isset($_SESSION['user_last_name']) ? strtoupper($_SESSION['user_last_name'][0]) : 'S';
                    echo $first_initial . $last_initial;
                ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                    <?php echo htmlspecialchars(($_SESSION['user_first_name'] ?? 'User') . ' ' . ($_SESSION['user_last_name'] ?? 'Account')); ?>
                </p>
                <p class="text-xs text-slate-600 dark:text-slate-400 truncate">
                    <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'user@example.com'); ?>
                </p>
            </div>
        </div>

        <!-- Logout Button -->
        <a href="../../api/auth/logout.php" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 rounded-lg hover:bg-red-100 dark:hover:bg-red-950/50 transition-colors border border-red-200 dark:border-red-900/50">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Logout
        </a>
    </div>
</aside>

<!-- JavaScript for Sidebar Toggle (Mobile) -->
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
    
    // Close sidebar when a link is clicked on mobile
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 640) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    });
</script>
