<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-container">

        <!-- Logo -->
        <div class="site-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" 
                     alt="<?php bloginfo('name'); ?>">
            </a>
        </div>

        <!-- Navigation Desktop -->
        <nav class="main-navigation" role="navigation">
            <?php wp_nav_menu([
                'theme_location' => 'main-menu',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => false
            ]); ?>

             
        </nav>

        <!-- Menu burger (mobile) -->
        <button class="menu-toggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</header>

<!-- Menu mobile -->
<div class="mobile-menu">
    <?php wp_nav_menu([
        'theme_location' => 'main-menu',
        'menu_class'     => 'mobile-nav-menu',
        'container'      => false
    ]); ?>
</div>


