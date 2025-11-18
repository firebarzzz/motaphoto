<?php
/**
 * Template : Page individuelle d'une photo
 * 
 * Ce template affiche :
 * - La photo en grand
 * - Les métadonnées (référence, catégorie, format, type)
 * - Navigation précédent/suivant
 * - Photos apparentées (même catégorie)
 * - Bouton contact avec référence préremplie
 */

get_header();

// Démarrer la boucle WordPress
while (have_posts()) : the_post();

    // Récupération des données
    $photo_id = get_the_ID();
    $photo_title = get_the_title();
    $photo_date = get_the_date('Y'); // Année de publication
    
    // Image à la une
    $photo_full_url = get_the_post_thumbnail_url($photo_id, 'full');
    $photo_large_url = get_the_post_thumbnail_url($photo_id, 'large');
    
    // Custom fields (via SCF ou ACF)
    $reference = get_field('reference', $photo_id);
    $type = get_field('type', $photo_id); // Ex: "Art", "Nature", etc.
    
    // Taxonomies
    $categories = get_the_terms($photo_id, 'categorie-photo');
    $categorie_name = '';
    $categorie_slug = '';
    if ($categories && !is_wp_error($categories)) {
        $categorie_name = $categories[0]->name;
        $categorie_slug = $categories[0]->slug;
    }
    
    $formats = get_the_terms($photo_id, 'format');
    $format_name = '';
    if ($formats && !is_wp_error($formats)) {
        $format_name = $formats[0]->name;
    }
?>

<main class="single-photo-page">
    
    <!-- Section principale : Image + Infos -->
    <section class="photo-main">
        <div class="container">
            <div class="photo-main-grid">
                
                <!-- Colonne gauche : Image -->
                <div class="photo-image-container">
                    <img src="<?php echo esc_url($photo_large_url); ?>" 
                         alt="<?php echo esc_attr($photo_title); ?>"
                         class="photo-main-image">
                </div>
                
                <!-- Colonne droite : Informations -->
                <div class="photo-info-container">
                    
                    <!-- Titre de la photo -->
                    <h1 class="photo-title"><?php echo esc_html($photo_title); ?></h1>
                    
                    <!-- Métadonnées -->
                    <div class="photo-meta">
                        
                        <!-- Référence -->
                        <div class="meta-item">
                            <span class="meta-label">Référence</span>
                            <span class="meta-value"><?php echo esc_html($reference); ?></span>
                        </div>
                        
                        <!-- Catégorie -->
                        <div class="meta-item">
                            <span class="meta-label">Catégorie</span>
                            <span class="meta-value"><?php echo esc_html($categorie_name); ?></span>
                        </div>
                        
                        <!-- Format -->
                        <div class="meta-item">
                            <span class="meta-label">Format</span>
                            <span class="meta-value"><?php echo esc_html($format_name); ?></span>
                        </div>
                        
                        <!-- Type (si renseigné) -->
                        <?php if ($type) : ?>
                        <div class="meta-item">
                            <span class="meta-label">Type</span>
                            <span class="meta-value"><?php echo esc_html($type); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Année -->
                        <div class="meta-item">
                            <span class="meta-label">Année</span>
                            <span class="meta-value"><?php echo esc_html($photo_date); ?></span>
                        </div>
                        
                    </div><!-- .photo-meta -->
                    
                    <!-- Actions -->
                    <div class="photo-actions">
                        
                        <!-- Bouton Contact avec référence préremplie -->
                        <button class="btn-contact-photo" 
                                data-reference="<?php echo esc_attr($reference); ?>">
                            Cette photo vous intéresse ?
                        </button>
                        
                        <!-- Bouton voir en plein écran -->
                        <button class="btn-fullscreen icon-fullscreen"
                                data-photo-url="<?php echo esc_url($photo_full_url); ?>"
                                data-photo-title="<?php echo esc_attr($photo_title); ?>"
                                data-photo-reference="<?php echo esc_attr($reference); ?>">
                            <i class="fas fa-expand"></i>
                        </button>
                        
                    </div><!-- .photo-actions -->
                    
                </div><!-- .photo-info-container -->
                
            </div><!-- .photo-main-grid -->
        </div><!-- .container -->
    </section><!-- .photo-main -->
    
    <!-- Navigation Précédent / Suivant -->
    <section class="photo-navigation">
        <div class="container">
            <div class="nav-links">
                
                <!-- Photo précédente -->
                <?php
                $prev_post = get_previous_post();
                if ($prev_post) :
                    $prev_thumbnail = get_the_post_thumbnail_url($prev_post->ID, 'thumbnail');
                ?>
                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-prev">
                    <span class="nav-arrow"><i class="fas fa-arrow-left"></i></span>
                    <span class="nav-thumbnail">
                        <img src="<?php echo esc_url($prev_thumbnail); ?>" alt="Photo précédente">
                    </span>
                </a>
                <?php endif; ?>
                
                <!-- Photo suivante -->
                <?php
                $next_post = get_next_post();
                if ($next_post) :
                    $next_thumbnail = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
                ?>
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-next">
                    <span class="nav-thumbnail">
                        <img src="<?php echo esc_url($next_thumbnail); ?>" alt="Photo suivante">
                    </span>
                    <span class="nav-arrow"><i class="fas fa-arrow-right"></i></span>
                </a>
                <?php endif; ?>
                
            </div>
        </div>
    </section><!-- .photo-navigation -->
    
    <!-- Photos apparentées (même catégorie) -->
    <section class="related-photos">
        <div class="container">
            
            <h2 class="section-title">Vous aimerez aussi</h2>
            
            <div class="related-photos-grid">
                <?php
                // Requête pour récupérer les photos de la même catégorie
                $related_args = array(
                    'post_type' => 'photo',
                    'posts_per_page' => 2, // Afficher 2 photos apparentées
                    'post__not_in' => array($photo_id), // Exclure la photo actuelle
                    'orderby' => 'rand', // Ordre aléatoire
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorie-photo',
                            'field' => 'slug',
                            'terms' => $categorie_slug,
                        ),
                    ),
                );
                
                $related_query = new WP_Query($related_args);
                
                if ($related_query->have_posts()) :
                    while ($related_query->have_posts()) : $related_query->the_post();
                        // Utiliser le template part réutilisable
                        get_template_part('template-parts/photo-item');
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>Aucune photo apparentée.</p>';
                endif;
                ?>
            </div><!-- .related-photos-grid -->
            
        </div><!-- .container -->
    </section><!-- .related-photos -->

</main><!-- .single-photo-page -->

<?php
endwhile;

get_footer();
?>