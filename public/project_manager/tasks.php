<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
require_auth_page('manager');
$role = $_SESSION['user_role'] ?? 'manager';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tasks - Kanban | SideKick</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
<link href="../../assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-slate-50 dark:bg-slate-900">
<div class="flex min-h-screen">

<?php $current_role=$role; $current_page='tasks'; require_once '../../includes/sidebar.php'; ?>

<div class="flex-1 flex flex-col pt-16 md:ml-64">
<?php require_once '../../includes/partials/topbar.php'; ?>

<main class="flex-1 overflow-y-auto p-8">
  <div class="max-w-7xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Tasks</h1>
      <p class="text-slate-600 dark:text-slate-400">Manage your tasks with Kanban board</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">To Do</h2>
        <div class="space-y-4" id="todo-tasks" ondrop="drop(event, 'todo')" ondragover="allowDrop(event)">
          <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg border-l-4 border-blue-500 cursor-move hover:shadow-md transition" draggable="true" ondragstart="drag(event)">
            <p class="font-medium text-slate-900 dark:text-white">Design System Update</p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">High Priority</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">In Progress</h2>
        <div class="space-y-4" id="progress-tasks" ondrop="drop(event, 'progress')" ondragover="allowDrop(event)">
          <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg border-l-4 border-yellow-500 cursor-move hover:shadow-md transition" draggable="true" ondragstart="drag(event)">
            <p class="font-medium text-slate-900 dark:text-white">API Integration</p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Due: Tomorrow</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Done</h2>
        <div class="space-y-4" id="done-tasks" ondrop="drop(event, 'done')" ondragover="allowDrop(event)">
          <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg border-l-4 border-green-500 cursor-move hover:shadow-md transition" draggable="true" ondragstart="drag(event)">
            <p class="font-medium text-slate-900 dark:text-white">User Testing</p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Completed</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
</div>
</div>

<script>
function allowDrop(event) { event.preventDefault(); }
function drag(event) { event.dataTransfer.effectAllowed = 'move'; }
function drop(event, target) { event.preventDefault(); }
document.addEventListener('DOMContentLoaded', () => {
  if(localStorage.getItem('sidekick_theme')==='dark') document.documentElement.classList.add('dark');
});
</script>
</body>
</html>







