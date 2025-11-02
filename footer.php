<footer class="site-footer">
    <div class="footer-container">
        <nav class="footer-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer-menu',
                'menu_class' => 'footer-menu',
                'container' => false,
                'fallback_cb' => false
            ));
            ?>
        </nav>
         
    </div>
</footer>

<?php 
// Inclure la modale de contact
  
?>

<?php wp_footer(); ?>
</body>
</html>