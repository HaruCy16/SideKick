<?php
/**
 * Reusable Stat Card Component
 * 
 * Usage: include with $label, $value, $color, $icon_bg
 * Example: 
 * $label = 'Projects'; $value = 24; $color = '#14B8A6'; $icon_bg = '#CCFBF1';
 * include 'stat-card.php';
 */

$label = $label ?? 'Label';
$value = $value ?? '0';
$color = $color ?? '#3B82F6';
$icon_bg = $icon_bg ?? '#DBEAFE';
?>

<div class="bg-white rounded-3xl p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div class="flex flex-col">
            <span class="text-xs font-medium text-gray-500 mb-1"><?php echo htmlspecialchars($label); ?></span>
            <div class="text-3xl font-bold" style="color: <?php echo htmlspecialchars($color); ?>">
                <?php 
                    if (is_numeric($value) && $value >= 1000) {
                        echo number_format($value);
                    } else {
                        echo htmlspecialchars($value);
                    }
                ?>
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: <?php echo htmlspecialchars($icon_bg); ?>">
            <svg class="w-5 h-5" style="color: <?php echo htmlspecialchars($color); ?>;" fill="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="8" height="8"/>
                <rect x="13" y="3" width="8" height="8"/>
                <rect x="3" y="13" width="8" height="8"/>
                <rect x="13" y="13" width="8" height="8"/>
            </svg>
        </div>
    </div>
</div>
