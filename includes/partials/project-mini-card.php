<?php
/**
 * Project Mini Card Component
 * 
 * Usage: include with $project_name, $progress_percent, $progress_color
 * Example:
 * $project_name = 'Website Redesign'; $progress_percent = 65; $progress_color = '#8B5CF6';
 * include 'project-mini-card.php';
 */

$project_name = $project_name ?? 'Project';
$progress_percent = $progress_percent ?? 0;
$progress_color = $progress_color ?? '#3B82F6';
?>

<div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-all">
    <p class="text-xs font-semibold text-gray-800 mb-2 line-clamp-2">
        <?php echo htmlspecialchars($project_name); ?>
    </p>
    <div>
        <div class="text-[10px] text-gray-400 mb-1.5">Progress</div>
        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-1.5 rounded-full transition-all duration-700 ease-out" 
                 style="width: <?php echo (int)$progress_percent; ?>%; background-color: <?php echo htmlspecialchars($progress_color); ?>; transition: width 0.6s ease-out;"></div>
        </div>
    </div>
</div>
