<?php
/**
 * Template : Page d'accueil
 *
 * Ce template affiche :
 * - Hero avec image aléatoire
 * - Filtres (catégorie, format, tri)
 * - Grille de photos avec pagination AJAX
 */

get_header();
?>

<!-- ========================= -->
<!-- SECTION HERO (photo aléatoire) -->
<!-- ========================= -->
<section class="hero">

    <?php
    // Récupérer une photo aléatoire pour le hero
    $hero_query = new WP_Query([
        'post_type'      => 'photo',
        'posts_per_page' => 1,
        'orderby'        => 'rand',
        // Optionnel : format paysage
        'tax_query'      => [
            [
                'taxonomy' => 'format',
                'field'    => 'slug',
                'terms'    => 'paysage',
            ],
        ],
    ]);

    if ($hero_query->have_posts()) :
        while ($hero_query->have_posts()) : $hero_query->the_post();

            $hero_url  = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $hero_link = get_permalink();
    ?>

        <a href="<?php echo esc_url($hero_link); ?>">
            <div class="hero-image" style="background-image: url('<?php echo esc_url($hero_url); ?>');">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/titre-accueil.png"
                     alt="<?php bloginfo('name'); ?> - Photographe">
            </div>
        </a>

    <?php
        endwhile;
    endif;

    wp_reset_postdata();
    ?>

</section>

<!-- ========================= -->
<!-- SECTION FILTRES -->
<!-- ========================= -->
<section class="filters-and-sort">

    <!-- Filtre Catégorie -->
    <select id="filter-categorie" name="category-filter">
        <option value="ALL">CATÉGORIES</option>

        <?php
        $categories = get_terms([
            'taxonomy'   => 'categorie-photo',
            'hide_empty' => false,
        ]);

        foreach ($categories as $cat) :
        ?>
            <option value="<?php echo esc_attr($cat->slug); ?>">
                <?php echo esc_html($cat->name); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Filtre Format -->
    <select id="filter-format" name="format-filter">
        <option value="ALL">FORMATS</option>

        <?php
        $formats = get_terms([
            'taxonomy'   => 'format',
            'hide_empty' => false,
        ]);

        foreach ($formats as $format) :
        ?>
            <option value="<?php echo esc_attr($format->slug); ?>">
                <?php echo esc_html($format->name); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Tri par date -->
    <select id="filter-date" name="date-sort">
        <option value="DESC">TRIER PAR</option>
        <option value="DESC">Plus récentes</option>
        <option value="ASC">Plus anciennes</option>
    </select>

</section>

<!-- ========================= -->
<!-- SECTION CATALOGUE -->
<!-- ========================= -->
<section class="catalogue">
    <div class="container">

        <!-- Grille des photos (mise à jour AJAX) -->
        <div class="photo-grid" id="photo-grid">

            <?php
            // 8 premières photos
            $photos_query = new WP_Query([
                'post_type'      => 'photo',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ($photos_query->have_posts()) :
                while ($photos_query->have_posts()) :
                    $photos_query->the_post();

                    // Template part réutilisable
                    get_template_part('template-parts/photo-item');

                endwhile;

            else :
                echo '<p class="no-photos">Aucune photo trouvée.</p>';
            endif;

            wp_reset_postdata();
            ?>

        </div><!-- #photo-grid -->

        <!-- Bouton Charger plus -->
        <div class="view-all-button">
            <?php if ($photos_query->max_num_pages > 1) : ?>
                <button id="load-more" data-page="1">Charger plus</button>
            <?php endif; ?>
        </div>

    </div><!-- .container -->
</section>

<?php get_footer(); ?>
