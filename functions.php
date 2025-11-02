<?php
/**
 * Mota Photo Theme Functions
 */

// Sécurité - empêche l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue des styles et scripts
 */
function motaphoto_enqueue_assets() {
    // Styles
    wp_enqueue_style('motaphoto-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('motaphoto-custom', get_template_directory_uri() . '/css/style.css', array(), '1.0.0');
    
    // Scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('motaphoto-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0', true);
    
    // Données pour AJAX
    wp_localize_script('motaphoto-scripts', 'motaphoto_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('motaphoto_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_assets');

/**
 * Support du thème
 */
function motaphoto_setup() {
    // Support des images à la une
    add_theme_support('post-thumbnails');
    
    // Support du titre dynamique
    add_theme_support('title-tag');
    
    // Support HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'motaphoto_setup');

/**
 * Enregistrement des menus
 */
function motaphoto_register_menus() {
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'motaphoto'),
        'footer-menu' => __('Menu Footer', 'motaphoto')
    ));
}
add_action('init', 'motaphoto_register_menus');

/**
 * Tailles d'images personnalisées
 */
add_image_size('photo-thumbnail', 564, 564, true); // Carré pour la grille
add_image_size('photo-large', 1440, 1440, false); // Grande taille pour single