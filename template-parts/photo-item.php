<?php
/**
 * Template Part : Bloc Photo avec Overlay
 * VERSION FINALE - Disposition selon maquette
 *
 * Disposition :
 * - Icône fullscreen : coin supérieur droit
 * - Icône œil : centre de l'image
 * - Référence : bas gauche
 * - Catégorie : bas droite
 */

// Infos principales
$photo_id     = get_the_ID();
$photo_url    = get_permalink();
$photo_title  = get_the_title();
$thumbnail    = get_the_post_thumbnail_url($photo_id, 'large');
$full_image   = get_the_post_thumbnail_url($photo_id, 'full');

// Custom fields (ACF/SCF)
$reference = get_field('reference', $photo_id);

// Catégorie principale
$categories = get_the_terms($photo_id, 'categorie-photo');
$categorie_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';

// Chemin vers les images du thème - CORRIGÉ
$theme_images = get_template_directory_uri() . '/images';
?>

<div class="photo-item" data-photo-id="<?php echo esc_attr($photo_id); ?>">

    <div class="thumbnail-wrapper">

        <!-- Miniature -->
        <?php if ($thumbnail) : ?>
            <img
                src="<?php echo esc_url($thumbnail); ?>"
                alt="<?php echo esc_attr($photo_title); ?>"
                loading="lazy"
            >
        <?php endif; ?>

        <!-- Overlay -->
        <div class="thumbnail-overlay">

            <!-- Icône FULLSCREEN : coin supérieur droit -->
            <button
                class="icon-fullscreen"
                data-photo-url="<?php echo esc_url($full_image); ?>"
                data-photo-title="<?php echo esc_attr($photo_title); ?>"
                data-photo-reference="<?php echo esc_attr($reference); ?>"
                title="Afficher en plein écran"
            >
                <img
                    src="<?php echo esc_url($theme_images); ?>/Icon_fullscreen.png"
                    alt="Plein écran"
                >
            </button>

            <!-- Icône ŒIL : centre de l'image -->
            <a
                href="<?php echo esc_url($photo_url); ?>"
                class="icon-eye"
                title="Voir la fiche"
            >
                <img
                    src="<?php echo esc_url($theme_images); ?>/Icon_eye.png"
                    alt="Voir"
                >
            </a>

            <!-- Infos : bas de l'overlay -->
            <div class="photo-info">
                <span class="photo-reference">
                    <?php echo esc_html($reference); ?>
                </span>
                <span class="photo-category">
                    <?php echo esc_html($categorie_name); ?>
                </span>
            </div>

        </div><!-- .thumbnail-overlay -->

    </div><!-- .thumbnail-wrapper -->

</div><!-- .photo-item -->