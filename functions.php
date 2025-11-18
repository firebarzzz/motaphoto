<?php
/**
 * Mota Photo Theme Functions
 * 
 * Ce fichier contient :
 * - Enqueue des styles et scripts
 * - Configuration du thème
 * - Enregistrement des menus
 * - Tailles d'images personnalisées
 * - Fonctions AJAX pour filtres et pagination
 */

// Sécurité - empêche l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/* ===========================================
   ENQUEUE DES STYLES ET SCRIPTS
   =========================================== */

function motaphoto_enqueue_assets() {
    
    // === STYLES ===
    
    // Google Fonts
    wp_enqueue_style(
        'google-fonts', 
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap', 
        array(), 
        null
    );
    
    // Font Awesome (pour les icônes)
    wp_enqueue_style(
        'font-awesome', 
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', 
        array(), 
        '5.15.4'
    );
    
    // Style principal du thème (style.css à la racine)
    wp_enqueue_style(
        'motaphoto-style', 
        get_stylesheet_uri(), 
        array(), 
        '1.0.0'
    );
    
    // Styles personnalisés
    wp_enqueue_style(
        'motaphoto-theme', 
        get_template_directory_uri() . '/css/theme.css', 
        array('motaphoto-style'), 
        '1.0.0'
    );
    
    wp_enqueue_style(
        'motaphoto-photo-block', 
        get_template_directory_uri() . '/css/photo-block.css', 
        array('motaphoto-theme'), 
        '1.0.0'
    );
    
    // CSS spécifique à la page single-photo
    if (is_singular('photo')) {
        wp_enqueue_style(
            'motaphoto-single-photo', 
            get_template_directory_uri() . '/css/single-photo.css', 
            array('motaphoto-theme'), 
            '1.0.0'
        );
    }
    
    // === SCRIPTS ===
    
    // jQuery (inclus dans WordPress)
    wp_enqueue_script('jquery');
    
    // Script principal
    wp_enqueue_script(
        'motaphoto-scripts', 
        get_template_directory_uri() . '/js/scripts.js', 
        array('jquery'), 
        '1.0.0', 
        true // Charger dans le footer
    );
    
    // Passer des données PHP au JavaScript (pour AJAX)
    wp_localize_script('motaphoto-scripts', 'motaphoto_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('motaphoto_nonce'),
        'theme_url' => get_template_directory_uri()
    ));
}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_assets');

/* ===========================================
   CONFIGURATION DU THÈME
   =========================================== */

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
    
    // Support du logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ));
}
add_action('after_setup_theme', 'motaphoto_setup');

/* ===========================================
   ENREGISTREMENT DES MENUS
   =========================================== */

function motaphoto_register_menus() {
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'motaphoto'),
        'footer-menu' => __('Menu Footer', 'motaphoto')
    ));
}
add_action('init', 'motaphoto_register_menus');

/* ===========================================
   TAILLES D'IMAGES PERSONNALISÉES
   =========================================== */

add_image_size('photo-thumbnail', 564, 495, true);  // Carré pour la grille
add_image_size('photo-large', 1440, 960, false);    // Grande taille pour single

/* ===========================================
   FONCTIONS AJAX - FILTRES ET PAGINATION
   =========================================== */

/**
 * Fonction AJAX pour filtrer et charger les photos
 */
function motaphoto_filter_photos() {
    
    // Vérifier le nonce pour la sécurité
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'motaphoto_nonce')) {
        wp_send_json_error('Nonce invalide');
        wp_die();
    }
    
    // Récupérer les paramètres
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    // Nombre de photos par page
    $posts_per_page = 8;
    
    // Arguments de la requête
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => $order,
    );
    
    // Ajouter les filtres de taxonomie
    $tax_query = array();
    
    if (!empty($category) && $category !== 'ALL') {
        $tax_query[] = array(
            'taxonomy' => 'categorie-photo',
            'field' => 'slug',
            'terms' => $category,
        );
    }
    
    if (!empty($format) && $format !== 'ALL') {
        $tax_query[] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }
    
    // Si plusieurs filtres, les combiner avec AND
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    // Exécuter la requête
    $query = new WP_Query($args);
    
    // Générer le HTML
    $html = '';
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/photo-item');
        }
        
        $html = ob_get_clean();
        wp_reset_postdata();
        
        // Vérifier s'il y a plus de pages
        $has_more = $page < $query->max_num_pages;
        
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $has_more,
            'total_pages' => $query->max_num_pages,
            'current_page' => $page
        ));
        
    } else {
        wp_send_json_success(array(
            'html' => '<p class="no-photos">Aucune photo trouvée.</p>',
            'has_more' => false
        ));
    }
    
    wp_die();
}

// Enregistrer les actions AJAX (pour utilisateurs connectés et non connectés)
add_action('wp_ajax_filter_photos', 'motaphoto_filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'motaphoto_filter_photos');

/* ===========================================
   FONCTION AJAX - CHARGER PLUS DE PHOTOS
   =========================================== */

/**
 * Fonction AJAX pour la pagination infinie
 */
function motaphoto_load_more() {
    
    // Vérifier le nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'motaphoto_nonce')) {
        wp_send_json_error('Nonce invalide');
        wp_die();
    }
    
    // Récupérer la page
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    // Arguments de la requête
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $query = new WP_Query($args);
    
    $html = '';
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/photo-item');
        }
        
        $html = ob_get_clean();
        wp_reset_postdata();
        
        $has_more = $page < $query->max_num_pages;
        
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $has_more
        ));
        
    } else {
        wp_send_json_success(array(
            'html' => '',
            'has_more' => false
        ));
    }
    
    wp_die();
}

add_action('wp_ajax_load_more', 'motaphoto_load_more');
add_action('wp_ajax_nopriv_load_more', 'motaphoto_load_more');

/* ===========================================
   HELPERS - FONCTIONS UTILITAIRES
   =========================================== */

/**
 * Récupérer toutes les catégories de photos
 */
function motaphoto_get_categories() {
    return get_terms(array(
        'taxonomy' => 'categorie-photo',
        'hide_empty' => false,
    ));
}

/**
 * Récupérer tous les formats
 */
function motaphoto_get_formats() {
    return get_terms(array(
        'taxonomy' => 'format',
        'hide_empty' => false,
    ));
}

/**
 * Afficher les options de select pour les catégories
 */
function motaphoto_category_options() {
    $categories = motaphoto_get_categories();
    foreach ($categories as $cat) {
        echo '<option value="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</option>';
    }
}

/**
 * Afficher les options de select pour les formats
 */
function motaphoto_format_options() {
    $formats = motaphoto_get_formats();
    foreach ($formats as $format) {
        echo '<option value="' . esc_attr($format->slug) . '">' . esc_html($format->name) . '</option>';
    }
}