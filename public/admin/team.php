<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
$role = 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Team Management | SideKick</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
<link href="../../assets/css/output.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 dark:bg-slate-900">
<div class="flex min-h-screen">
<?php $current_role=$role; $current_page='team'; require_once '../../includes/sidebar.php'; ?>
<div class="flex-1 flex flex-col pt-16 md:ml-64">
<?php require_once '../../includes/partials/topbar.php'; ?>
<main class="flex-1 overflow-y-auto p-8 bg-slate-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto">
<?php
require_once '../../includes/auth_guard.php';
require_auth_page('admin');
$current_page = 'team';
require_once '../../includes/partials/topbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Team Overview</h1>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500">Total Members</p>
            <p class="text-2xl font-bold dark:text-white">24</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500">Online Now</p>
            <p class="text-2xl font-bold text-green-500">8</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500">Active Projects</p>
            <p class="text-2xl font-bold dark:text-white">12</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500">Tasks Pending</p>
            <p class="text-2xl font-bold dark:text-white">45</p>
        </div>
    </div>

    <!-- Team Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Member Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <span class="text-xl font-bold text-blue-600">JD</span>
            </div>
            <h3 class="font-bold dark:text-white text-lg">John Doe</h3>
            <p class="text-sm text-gray-500 mb-4">Senior Developer</p>
            <div class="flex justify-center space-x-2">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Online</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
            <div class="w-20 h-20 bg-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <span class="text-xl font-bold text-purple-600">AS</span>
            </div>
            <h3 class="font-bold dark:text-white text-lg">Alice Smith</h3>
            <p class="text-sm text-gray-500 mb-4">UI Designer</p>
            <div class="flex justify-center space-x-2">
                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Offline</span>
            </div>
        </div>
    </div>
</div>


</div>
</main>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  if(localStorage.getItem('sidekick_theme')==='dark') document.documentElement.classList.add('dark');
});
</script>
</body>
</html>



