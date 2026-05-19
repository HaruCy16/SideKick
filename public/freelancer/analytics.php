<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
$role = 'freelancer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics | SideKick</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
<link href="../../assets/css/output.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 dark:bg-slate-900">
<div class="flex min-h-screen">
<?php $current_role=$role; $current_page='analytics'; require_once '../../includes/sidebar.php'; ?>
<div class="flex-1 flex flex-col pt-16 md:ml-64">
<?php require_once '../../includes/partials/topbar.php'; ?>
<main class="flex-1 overflow-y-auto p-8 bg-slate-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto">
<?php
require_once '../../includes/auth_guard.php';
require_auth_page('freelancer');
$current_page = 'analytics';
require_once '../../includes/partials/topbar.php';
require_once '../../includes/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Analytics</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Project Completion -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
            <h3 class="font-semibold mb-4 dark:text-gray-200">Project Completion</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="projectChart"></canvas>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
            <h3 class="font-semibold mb-4 dark:text-gray-200">Team Performance</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
        <h3 class="font-semibold mb-4 dark:text-gray-200">Task Trends (Weekly)</h3>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="taskTrendChart"></canvas>
        </div>
    </div>
</div>

<script>
// Project Completion Chart
new Chart(document.getElementById('projectChart'), {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Ongoing', 'Delayed'],
        datasets: [{
            data: [12, 19, 3],
            backgroundColor: ['#10b981', '#3b82f6', '#ef4444']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Team Performance Chart
new Chart(document.getElementById('performanceChart'), {
    type: 'bar',
    data: {
        labels: ['Dev Team', 'Design Team', 'Marketing', 'QA'],
        datasets: [{
            label: 'Tasks Completed',
            data: [65, 59, 80, 81],
            backgroundColor: '#8b5cf6'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Task Trend Chart
new Chart(document.getElementById('taskTrendChart'), {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'New Tasks',
            data: [10, 15, 8, 22, 18, 5, 2],
            borderColor: '#3b82f6',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>




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



