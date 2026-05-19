<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
require_auth_page('admin');
$current_role = 'admin';
$current_page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SideKick - Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php require_once '../../includes/sidebar.php'; ?>
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php require_once '../../includes/partials/topbar.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-800 p-6">
                <div class="container mx-auto">
                    <h1 class="text-2xl font-semibold mb-6">Dashboard Summary</h1>
                    <div id="stats-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8"></div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h2 class="text-xl font-bold mb-4">Recent Projects</h2>
                            <div id="projects-list" class="space-y-4"></div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h2 class="text-xl font-bold mb-4">Activity & Tasks</h2>
                            <div id="activity-list" class="space-y-4"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        if (localStorage.getItem('sidekick_theme') === 'dark') document.documentElement.classList.add('dark');
        async function fetchDashboardData() {
            try {
                const response = await fetch('/SideKick/api/dashboard/summary.php');
                const data = await response.json();
                if (data.success) {
                    const stats = data.summary.stats || {};
                    const statsGrid = document.getElementById('stats-grid');
                    if(statsGrid) {
                        statsGrid.innerHTML = Object.entries(stats).map(([k, v]) => \
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border-l-4 border-blue-500">
                                <p class="text-sm text-gray-500 capitalize">\</p>
                                <p class="text-2xl font-bold">\</p>
                            </div>
                        \).join('');
                    }
                    const projList = document.getElementById('projects-list');
                    if(projList) {
                        projList.innerHTML = (data.summary.projects || []).map(p => \
                            <div class="border-b dark:border-gray-600 pb-2">
                                <h3 class="font-semibold">\</h3>
                                <p class="text-sm text-gray-500">\ - \%</p>
                            </div>
                        \).join('') || '<p>No projects found.</p>';
                    }
                    const actList = document.getElementById('activity-list');
                    if(actList) {
                        actList.innerHTML = (data.summary.activity || []).map(a => \
                            <div class="text-sm mb-2"><span class="text-blue-500">·</span> \</div>
                        \).join('') || '<p>No recent activity.</p>';
                    }
                }
            } catch (e) { console.error('Error:', e); }
        }
        document.addEventListener('DOMContentLoaded', fetchDashboardData);
    </script>
</body>
</html>
