<?php
/**
 * Activity Item Component
 * 
 * Usage: include in a loop with $user_name, $initials, $avatar_color, $action, $target, $timestamp
 * Example in a loop:
 * foreach ($activities as $activity):
 *   $user_name = $activity['user_name']; $initials = $activity['initials']; 
 *   $avatar_color = $activity['avatar_color']; $action = $activity['action']; 
 *   $target = $activity['target']; $timestamp = $activity['timestamp'];
 *   include 'activity-item.php';
 * endforeach;
 */

$user_name = $user_name ?? 'User';
$initials = $initials ?? 'US';
$avatar_color = $avatar_color ?? '#3B82F6';
$action = $action ?? 'updated';
$target = $target ?? 'Project';
$timestamp = $timestamp ?? '5m ago';
?>

<div class="flex items-start gap-2.5">
    <!-- Avatar -->
    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0" 
         style="background-color: <?php echo htmlspecialchars($avatar_color); ?>">
        <?php echo htmlspecialchars($initials); ?>
    </div>
    
    <!-- Content -->
    <div class="flex flex-col flex-1">
        <p class="text-xs font-semibold text-white leading-tight">
            <?php echo htmlspecialchars($user_name); ?>
        </p>
        <p class="text-xs text-gray-200 leading-snug">
            <?php echo htmlspecialchars($action); ?>
            <span class="font-semibold text-white"><?php echo htmlspecialchars($target); ?></span>
        </p>
        <p class="text-[11px] text-gray-400 mt-0.5">
            <?php echo htmlspecialchars($timestamp); ?>
        </p>
    </div>
</div>
