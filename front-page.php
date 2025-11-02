<?php get_header(); ?>

<section class="hero">
    <?php
    // Récupérer une photo aléatoire
    $hero_query = new WP_Query(array(
        'post_type' => 'photo',
        'posts_per_page' => 1,
        'orderby' => 'rand'
    ));
    if ($hero_query->have_posts()) : 
        while ($hero_query->have_posts()) : $hero_query->the_post();
            if (has_post_thumbnail()) {
                the_post_thumbnail('full');
            }
        endwhile;
    endif;
    wp_reset_postdata();
    ?>
</section>

<section class="catalogue">
    <div class="container">
        <div class="filters">
            <select id="filter-categorie">
                <option value="">Toutes les catégories</option>
                <?php
                $categories = get_terms(array('taxonomy' => 'categorie-photo', 'hide_empty' => false));
                foreach ($categories as $cat) {
                    echo '<option value="' . $cat->slug . '">' . $cat->name . '</option>';
                }
                ?>
            </select>
            
            <select id="filter-format">
                <option value="">Tous les formats</option>
                <?php
                $formats = get_terms(array('taxonomy' => 'format', 'hide_empty' => false));
                foreach ($formats as $format) {
                    echo '<option value="' . $format->slug . '">' . $format->name . '</option>';
                }
                ?>
            </select>
            
            <select id="filter-date">
                <option value="DESC">Plus récentes</option>
                <option value="ASC">Plus anciennes</option>
            </select>
        </div>
        
        <div class="photo-grid" id="photo-grid">
            <?php
            $photos = new WP_Query(array(
                'post_type' => 'photo',
                'posts_per_page' => 8,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($photos->have_posts()) :
                while ($photos->have_posts()) : $photos->the_post();
                    get_template_part('template-parts/photo-item');
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>
        
        <button id="load-more" data-page="1">Charger plus</button>
    </div>
</section>

<?php get_template_part('template-parts/modale-contact'); ?>
<?php get_template_part('template-parts/lightbox'); ?>
<?php get_footer(); ?>