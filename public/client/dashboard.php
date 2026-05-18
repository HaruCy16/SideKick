<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';

// Check authentication with role verification
$user = require_auth_page('client');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Client | SideKick</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
    <link href="../../assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <?php $current_role = 'client'; $current_page = 'dashboard'; include '../../includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen flex flex-col">
        <!-- Top Navigation Bar -->
        <?php include '../../includes/partials/topbar.php'; ?>

        <!-- Page Content -->
        <main class="flex-1 pt-16 p-6 flex flex-col gap-5">
            <!-- Page Header -->
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Welcome back! Here's your project overview.</p>
            </div>

            <!-- Stats Grid (4 columns) -->
            <div class="grid grid-cols-4 gap-4">
                <?php
                $stats_data = [
                    ['label' => 'Projects', 'color' => '#14B8A6', 'bg' => '#CCFBF1'],
                    ['label' => 'Active', 'color' => '#8B5CF6', 'bg' => '#EDE9FE'],
                    ['label' => 'Pending', 'color' => '#EC4899', 'bg' => '#FCE7F3'],
                    ['label' => 'Budget', 'color' => '#F59E0B', 'bg' => '#FEF3C7'],
                ];

                foreach ($stats_data as $stat):
                    $label = $stat['label'];
                    $color = $stat['color'];
                    $icon_bg = $stat['bg'];
                    $value = '0';
                    include '../../includes/partials/stat-card.php';
                endforeach;
                ?>
            </div>

            <!-- Middle Row: Recent Projects (60%) + Team Activity (40%) -->
            <div class="grid grid-cols-[3fr_2fr] gap-4">
                <!-- Recent Projects Card -->
                <div class="bg-gray-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-semibold text-gray-800">Your Projects</p>
                        <a href="projects.php" class="text-xs text-blue-500 hover:underline">View all</a>
                    </div>
                    <div id="projectsGrid" class="grid grid-cols-2 gap-3">
                        <!-- Skeleton loaders -->
                        <div class="bg-gray-300 h-24 rounded-xl animate-pulse"></div>
                        <div class="bg-gray-300 h-24 rounded-xl animate-pulse"></div>
                        <div class="bg-gray-300 h-24 rounded-xl animate-pulse"></div>
                        <div class="bg-gray-300 h-24 rounded-xl animate-pulse"></div>
                    </div>
                </div>

                <!-- Team Activity Card -->
                <div class="bg-gray-400 rounded-2xl p-5">
                    <p class="text-sm font-semibold text-white text-center mb-4">Updates</p>
                    <div id="activityFeed" class="flex flex-col gap-4">
                        <!-- Skeleton loaders -->
                        <div class="animate-pulse space-y-1">
                            <div class="h-2 bg-gray-500 rounded w-3/4"></div>
                            <div class="h-2 bg-gray-500 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Tasks Section (Full Width) -->
            <div class="bg-gray-200 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold text-gray-800">Pending Tasks</p>
                    <a href="tasks.php" class="text-xs text-blue-500 hover:underline">View all</a>
                </div>
                <div id="tasksContainer" class="flex flex-col divide-y divide-gray-300/50">
                    <div class="text-center py-8 text-gray-500">Loading tasks...</div>
                </div>
            </div>
        </main>
    </div>

    <script>
        async function fetchDashboardData() {
            try {
                const response = await fetch('/SideKick/api/dashboard/summary.php');
                const data = await response.json();

                if (data.success) {
                    if (data.stats) {
                        const stats = Object.entries(data.stats);
                        const statElements = document.querySelectorAll('.grid.grid-cols-4 > div');
                        stats.forEach(([key, value], index) => {
                            if (statElements[index]) {
                                const valueEl = statElements[index].querySelector('.text-3xl');
                                if (valueEl) {
                                    valueEl.textContent = typeof value === 'number' && value >= 1000 
                                        ? value.toLocaleString() 
                                        : value;
                                }
                            }
                        });
                    }

                    const projectsGrid = document.getElementById('projectsGrid');
                    if (projectsGrid && data.projects.length > 0) {
                        projectsGrid.innerHTML = data.projects.map(project => `
                            <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-all">
                                <p class="text-xs font-semibold text-gray-800 mb-2 line-clamp-2">${project.name}</p>
                                <div>
                                    <div class="text-[10px] text-gray-400 mb-1.5">Progress</div>
                                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-1.5 rounded-full transition-all duration-700 ease-out" 
                                             style="width: ${project.progress_percent}%; background-color: ${project.color}; transition: width 0.6s ease-out;"></div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }

                    const activityFeed = document.getElementById('activityFeed');
                    if (activityFeed && data.recent_activity.length > 0) {
                        activityFeed.innerHTML = data.recent_activity.map(activity => `
                            <div class="flex items-start gap-2.5">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0" 
                                     style="background-color: ${activity.avatar_color}">
                                    ${activity.initials}
                                </div>
                                <div class="flex flex-col flex-1">
                                    <p class="text-xs font-semibold text-white leading-tight">${activity.user_name}</p>
                                    <p class="text-xs text-gray-200 leading-snug">
                                        ${activity.action}
                                        <span class="font-semibold text-white">${activity.target}</span>
                                    </p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">${activity.timestamp}</p>
                                </div>
                            </div>
                        `).join('');
                    }

                    const tasksContainer = document.getElementById('tasksContainer');
                    if (tasksContainer && data.my_tasks.length > 0) {
                        tasksContainer.innerHTML = data.my_tasks.map(task => {
                            let priorityClass = '';
                            if (task.priority === 'High') priorityClass = 'bg-red-100 text-red-600';
                            else if (task.priority === 'Medium') priorityClass = 'bg-yellow-100 text-yellow-600';
                            else priorityClass = 'bg-green-100 text-green-600';

                            return `
                                <div class="flex items-center py-3 px-1 gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 flex-shrink-0"></div>
                                    <span class="flex-1 text-sm text-gray-700">${task.name}</span>
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded ${priorityClass}">
                                        ${task.priority}
                                    </span>
                                    <span class="text-sm text-gray-600 w-20 text-right">${task.due_date}</span>
                                </div>
                            `;
                        }).join('');
                    }
                } else {
                    console.error('Error fetching dashboard data:', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', fetchDashboardData);
    </script>
</body>
</html>
    <?php $current_role = 'client'; $current_page = 'dashboard'; include '../../includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen">
        <!-- Top Navigation Bar -->
        <?php include '../../includes/partials/topbar.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400">Your projects and collaboration overview</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <?php
                // Stat card data for client
                $stats_data = [
                    ['label' => 'Projects', 'icon' => 'briefcase', 'color' => '#14B8A6', 'bg' => '#CCFBF1'],
                    ['label' => 'Active', 'icon' => 'play-circle', 'color' => '#8B5CF6', 'bg' => '#EDE9FE'],
                    ['label' => 'Pending Tasks', 'icon' => 'hourglass-half', 'color' => '#EC4899', 'bg' => '#FCE7F3'],
                    ['label' => 'Total Budget', 'icon' => 'credit-card', 'color' => '#F59E0B', 'bg' => '#FEF3C7'],
                ];

                foreach ($stats_data as $stat):
                    $label = $stat['label'];
                    $icon = $stat['icon'];
                    $color = $stat['color'];
                    $icon_bg = $stat['bg'];
                    $value = '0';
                    include '../../includes/partials/stat-card.php';
                endforeach;
                ?>
            </div>

            <!-- Main Grid: Projects and Activity -->
            <div class="grid grid-cols-3 gap-6 mb-8">
                <!-- My Projects -->
                <div class="col-span-2 bg-white dark:bg-[#1A1A2E] rounded-xl p-6 border border-gray-100 dark:border-[#2D2D44] shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">My Projects</h2>
                        <a href="projects.php" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
                    </div>
                    <div id="projectsGrid" class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-100 dark:bg-[#0F0F1A] h-24 rounded-lg animate-pulse"></div>
                        <div class="bg-gray-100 dark:bg-[#0F0F1A] h-24 rounded-lg animate-pulse"></div>
                        <div class="bg-gray-100 dark:bg-[#0F0F1A] h-24 rounded-lg animate-pulse"></div>
                        <div class="bg-gray-100 dark:bg-[#0F0F1A] h-24 rounded-lg animate-pulse"></div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-gray-900 dark:bg-[#111827] rounded-xl p-6 border border-gray-800 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-100 mb-4">Recent Updates</h2>
                    <div id="activityFeed" class="space-y-4">
                        <div class="animate-pulse space-y-2">
                            <div class="bg-gray-700 h-3 rounded w-3/4"></div>
                            <div class="bg-gray-700 h-2 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Section -->
            <div class="bg-white dark:bg-[#1A1A2E] rounded-xl p-6 border border-gray-100 dark:border-[#2D2D44] shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Tasks</h2>
                    <a href="tasks.php" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
                </div>
                <div id="tasksContainer" class="space-y-0">
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">Loading tasks...</div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Fetch dashboard data
        async function fetchDashboardData() {
            try {
                const response = await fetch('/SideKick/api/dashboard/summary.php');
                const data = await response.json();

                if (data.success) {
                    // Update stats
                    const stats = document.querySelectorAll('[class*="stat-value"]');
                    if (data.stats) {
                        const statsArray = Object.entries(data.stats);
                        statsArray.forEach(([key, value], index) => {
                            const statElements = document.querySelectorAll('.grid-cols-4 > div');
                            if (statElements[index]) {
                                const valueEl = statElements[index].querySelector('.text-3xl');
                                if (valueEl) {
                                    valueEl.textContent = typeof value === 'number' && value >= 1000 
                                        ? value.toLocaleString() 
                                        : value;
                                }
                            }
                        });
                    }

                    // Update projects grid
                    const projectsGrid = document.getElementById('projectsGrid');
                    if (projectsGrid && data.projects.length > 0) {
                        projectsGrid.innerHTML = data.projects.map(project => `
                            <div class="bg-white dark:bg-[#1A1A2E] rounded-lg p-4 border border-gray-100 dark:border-[#2D2D44]">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3 line-clamp-2">${project.name}</p>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Progress</span>
                                        <span class="text-xs font-semibold">${project.progress_percent}%</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: ${project.progress_percent}%; background-color: ${project.color};"></div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }

                    // Update activity feed
                    const activityFeed = document.getElementById('activityFeed');
                    if (activityFeed && data.recent_activity.length > 0) {
                        activityFeed.innerHTML = data.recent_activity.map(activity => `
                            <div class="flex gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0" 
                                     style="background-color: ${activity.avatar_color}">
                                    ${activity.initials}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm">
                                        <span class="font-semibold text-gray-100">${activity.user_name}</span>
                                        <span class="text-gray-400">${activity.action}</span>
                                        <span class="font-semibold text-blue-300">${activity.target}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">${activity.timestamp}</p>
                                </div>
                            </div>
                        `).join('');
                    }

                    // Update tasks
                    const tasksContainer = document.getElementById('tasksContainer');
                    if (tasksContainer && data.my_tasks.length > 0) {
                        tasksContainer.innerHTML = data.my_tasks.map(task => {
                            let priorityClass = '';
                            if (task.priority === 'High') priorityClass = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
                            else if (task.priority === 'Medium') priorityClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
                            else priorityClass = 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';

                            return `
                                <div class="py-3 px-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/30 rounded-lg border-b border-gray-100 dark:border-[#2D2D44] last:border-0">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                        <span class="text-sm text-gray-800 dark:text-gray-200">${task.name}</span>
                                    </div>
                                    <div class="flex items-center gap-3 ml-auto">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-md ${priorityClass}">
                                            ${task.priority}
                                        </span>
                                        <span class="text-xs text-gray-600 dark:text-gray-400 min-w-fit">${task.due_date}</span>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    }
                } else {
                    console.error('Error fetching dashboard data:', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', fetchDashboardData);
    </script>
</body>
</html>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">My Projects</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">8</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-2xl">📋</div>
                    </div>
                </div>

                <!-- Active Freelancers -->
                <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Freelancers</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">23</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-2xl">👥</div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Spent</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">$12,450</p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center text-2xl">💰</div>
                    </div>
                </div>

                <!-- Pending Tasks -->
                <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Tasks</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">34</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center text-2xl">⏳</div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: My Projects + Recent Activity -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- My Projects Section -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">My Projects</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Project 1 -->
                            <div class="pb-6 border-b border-slate-200 dark:border-slate-700 last:border-b-0">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white">Mobile App Development</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Posted: 2 weeks ago</p>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-full">In Progress</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">75% complete - 5 freelancers working</p>
                            </div>

                            <!-- Project 2 -->
                            <div class="pb-6 border-b border-slate-200 dark:border-slate-700 last:border-b-0">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white">UI/UX Design Overhaul</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Posted: 1 week ago</p>
                                    </div>
                                    <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs font-semibold rounded-full">In Progress</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                    <div class="bg-amber-500 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">45% complete - 2 freelancers working</p>
                            </div>

                            <!-- Project 3 -->
                            <div>
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white">Backend API Development</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Posted: Today</p>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-semibold rounded-full">New</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                    <div class="bg-slate-400 h-2 rounded-full" style="width: 5%"></div>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">5% complete - Waiting for freelancers</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Freelancers Section -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Freelancers</h2>
                        </div>
                        <div class="divide-y divide-slate-200 dark:divide-slate-700">
                            <!-- Freelancer 1 -->
                            <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-slate-900 dark:text-white">Alex Rivera</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Full Stack Developer • ⭐ 4.9/5</p>
                                    </div>
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-semibold rounded">Active</span>
                                </div>
                            </div>

                            <!-- Freelancer 2 -->
                            <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-slate-900 dark:text-white">Jordan Smith</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">UI/UX Designer • ⭐ 4.8/5</p>
                                    </div>
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-semibold rounded">Active</span>
                                </div>
                            </div>

                            <!-- Freelancer 3 -->
                            <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-slate-900 dark:text-white">Morgan Lee</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">React Developer • ⭐ 4.7/5</p>
                                    </div>
                                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded">Available</span>
                                </div>
                            </div>

                            <!-- Freelancer 4 -->
                            <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-slate-900 dark:text-white">Casey Chen</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Project Manager • ⭐ 5.0/5</p>
                                    </div>
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-semibold rounded">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Project Status & Notifications -->
                <div>
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden sticky top-24">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Notifications</h2>
                        </div>
                        <div class="divide-y divide-slate-200 dark:divide-slate-700">
                            <!-- Notification 1 -->
                            <div class="px-6 py-4">
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">Task Submitted</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Mobile App - Frontend completed</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">30 mins ago</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification 2 -->
                            <div class="px-6 py-4">
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">Milestone Reached</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Backend API - 50% complete</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">2 hours ago</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification 3 -->
                            <div class="px-6 py-4">
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 rounded-full bg-amber-600 mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">Approval Needed</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">UI Designs - Review and approve</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">1 day ago</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification 4 -->
                            <div class="px-6 py-4">
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 rounded-full bg-purple-600 mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">Freelancer Joined</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Backend API project - Alex Rivera joined</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">2 days ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const savedTheme = localStorage.getItem('theme') || 'light';
        
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            themeToggle.textContent = '☀️';
        }

        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            themeToggle.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
</body>
</html>
