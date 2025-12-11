<?php
/**
 * Template : Pages standards
 * 
 * Affiche les pages WordPress classiques (À propos, Mentions légales, etc.)
 *
 * @package Mota_Photo
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="page-content">
                    <?php the_content(); ?>
                </div>
                
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>
