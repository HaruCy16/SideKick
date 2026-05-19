<?php
/**
 * Task Row Component
 * 
 * Usage: include in a loop with $task_name, $priority, $due_date
 * Example:
 * foreach ($tasks as $task):
 *   $task_name = $task['name']; $priority = $task['priority']; $due_date = $task['due_date'];
 *   include 'task-row.php';
 * endforeach;
 */

$task_name = $task_name ?? 'Task';
$priority = $priority ?? 'Medium';
$due_date = $due_date ?? 'Today';

// Priority badge styling
$priority_styles = [
    'High' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
    'Medium' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
    'Low' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
];

$style = $priority_styles[$priority] ?? $priority_styles['Medium'];
?>

<div class="flex items-center py-3 px-1 gap-3 border-b border-gray-300/50 last:border-0">
    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 flex-shrink-0"></div>
    <span class="flex-1 text-sm text-gray-700">
        <?php echo htmlspecialchars($task_name); ?>
    </span>
    <span class="text-[11px] font-semibold px-2 py-0.5 rounded <?php echo $style['bg'] . ' ' . $style['text']; ?>">
        <?php echo htmlspecialchars($priority); ?>
    </span>
    <span class="text-sm text-gray-600 w-20 text-right">
        <?php 
            // Handle DateTime objects or string dates
            $date_str = ($due_date instanceof DateTime) ? $due_date->format('M d') : (string)$due_date;
            echo htmlspecialchars($date_str); 
        ?>
    </span>
</div>
