<?php
// Notification button for admin header - shows count when projects are submitted for approval
$adminPendingCount = count(getPendingSubmissions());
?>
<a href="<?php echo BASE_URL; ?>/admin/submissions.php" 
   class="relative inline-flex items-center gap-2 px-3 py-2 rounded-lg font-medium transition <?php echo $adminPendingCount > 0 ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50'; ?>"
   title="<?php echo $adminPendingCount > 0 ? $adminPendingCount . ' project(s) submitted for approval' : 'Pending submissions'; ?>">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m-6 0H9"></path>
    </svg>
    <?php if ($adminPendingCount > 0): ?>
    <span class="absolute -top-0.5 -right-0.5 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-amber-500 text-white text-xs font-bold px-1.5"><?php echo $adminPendingCount > 99 ? '99+' : $adminPendingCount; ?></span>
    <?php endif; ?>
</a>
