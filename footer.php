<?php
/**
 * Footer du thème Mota Photo
 * 
 * Contient :
 * - Menu footer
 * - Modale de contact (disponible sur toutes les pages)
 * - Lightbox (disponible sur toutes les pages)
 */
?>

<footer class="site-footer">
    <div class="footer-container">
        <nav class="footer-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer-menu',
                'menu_class' => 'footer-menu',
                'container' => 'div',
                'container_class' => 'menu-footer-container',
                'fallback_cb' => false
            ));
            ?>
        </nav>
    </div>
</footer>

<?php 
// Inclure la modale de contact (disponible sur toutes les pages)
get_template_part('template-parts/modale-contact');

// Inclure la lightbox (disponible sur toutes les pages)
// get_template_part('template-parts/lightbox');
// Note : La lightbox est générée dynamiquement par JavaScript dans scripts.js
?>

<?php wp_footer(); ?>
</body>
</html>
