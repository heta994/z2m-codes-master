    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="mb-4">
                        <img src="<?php echo BASE_URL; ?>/assets/images/zero2maker-logo.png" alt="<?php echo SITE_NAME; ?>" class="h-12 w-auto mb-4">
                    </div>
                    <p class="text-gray-600 mb-4"><?php echo SITE_DESCRIPTION; ?></p>
                    <p class="text-gray-500 text-sm">
                        A comprehensive collection of Arduino and basic programming codes for makers, 
                        students, and hobbyists. Learn, build, and create amazing projects!
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="<?php echo getHomeUrl(); ?>" class="text-gray-600 hover:text-purple-600 transition">Home</a></li>
                        <li><a href="<?php echo getCodesUrl(); ?>" class="text-gray-600 hover:text-purple-600 transition">Projects</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/submit" class="text-gray-600 hover:text-purple-600 transition">Submit Project</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Categories</h4>
                    <ul class="space-y-2">
                        <?php foreach ($categories as $cat_key => $category): ?>
                        <?php if ($cat_key === 'projects') continue; ?>
                        <li><a href="<?php echo getCategoryUrl($cat_key); ?>" class="text-gray-600 hover:text-purple-600 transition"><?php echo $category['name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-500 text-sm">
                <a href="<?php echo BASE_URL; ?>/admin/" class="hover:text-purple-600">Admin</a>
                <span class="mx-2">·</span>
                <span>© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?></span>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <script>
        // Initialize syntax highlighting
        hljs.highlightAll();
    </script>
</body>
</html>

