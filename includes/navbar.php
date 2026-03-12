<?php
// User auth removed - no session required for public pages
?>
<!-- Navigation Bar -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 gap-3">
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0 min-w-0">
                <a href="<?php echo getHomeUrl(); ?>" class="flex items-center min-h-[2rem] sm:min-h-[2.5rem]">
                    <img src="<?php echo BASE_URL; ?>/assets/images/zero2maker-logo.png" alt="<?php echo SITE_NAME; ?>" class="navbar-logo object-contain object-left">
                </a>
            </div>
            
            <!-- Desktop Navigation - Home, Projects -->
            <div class="hidden md:flex items-center space-x-6" style="display: flex !important; flex-direction: row !important; align-items: center !important;">
                <a href="<?php echo getHomeUrl(); ?>" 
                   id="nav-home"
                   style="order: 1 !important; display: inline-block !important;"
                   class="text-gray-700 hover:text-purple-600 font-medium transition duration-200">
                    Home
                </a>
                <a href="<?php echo getCodesUrl(); ?>" 
                   id="nav-all-codes"
                   style="order: 2 !important; display: inline-block !important;"
                   class="text-gray-700 hover:text-purple-600 font-medium transition duration-200 <?php echo (basename($_SERVER['PHP_SELF']) == 'codes.php' || basename($_SERVER['PHP_SELF']) == 'view-code.php') ? 'text-purple-600' : ''; ?>">
                    Projects
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-purple-600 p-2 rounded-lg hover:bg-purple-50 transition">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-4 space-y-1">
            <a href="<?php echo getHomeUrl(); ?>" class="block px-3 py-2 text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg font-medium transition">Home</a>
            <a href="<?php echo getCodesUrl(); ?>" class="block px-3 py-2 text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg font-medium transition">Projects</a>
        </div>
    </div>
</nav>
