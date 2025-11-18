<?php
/**
 * Template Part : Bloc Photo avec Overlay
 *
 * Utilisé dans :
 * - front-page.php
 * - single-photo.php
 *
 * Affiche :
 * - Miniature
 * - Overlay au survol : œil, fullscreen, référence, catégorie
 */

// Infos principales
$photo_id     = get_the_ID();
$photo_url    = get_permalink();
$photo_title  = get_the_title();
$thumbnail    = get_the_post_thumbnail_url($photo_id, 'large');

// Custom fields (ACF/SCF)
$reference = get_field('reference', $photo_id);

// Catégorie principale (taxonomie : categorie-photo)
$categories = get_the_terms($photo_id, 'categorie-photo');
$categorie_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';

// Format (utile potentiellement pour la lightbox)
$formats = get_the_terms($photo_id, 'format');
$format_name = (!empty($formats) && !is_wp_error($formats)) ? $formats[0]->name : '';
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

            <!-- Icône œil : lien vers single -->
            <a
                href="<?php echo esc_url($photo_url); ?>"
                class="icon-eye"
                title="Voir la fiche"
            >
                <img
                    src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-eye.svg"
                    alt="Voir"
                >
            </a>

            <!-- Icône fullscreen : lightbox -->
            <button
                class="icon-fullscreen"
                data-photo-url="<?php echo esc_url(get_the_post_thumbnail_url($photo_id, 'full')); ?>"
                data-photo-title="<?php echo esc_attr($photo_title); ?>"
                data-photo-reference="<?php echo esc_attr($reference); ?>"
                title="Afficher en plein écran"
            >
                <i class="fas fa-expand-arrows-alt"></i>
            </button>

            <!-- Infos référence + catégorie -->
            <div class="photo-info">

                <div class="photo-info-left">
                    <span class="photo-reference">
                        <?php echo esc_html($reference); ?>
                    </span>
                </div>

                <div class="photo-info-right">
                    <span class="photo-category">
                        <?php echo esc_html($categorie_name); ?>
                    </span>
                </div>

            </div><!-- .photo-info -->

        </div><!-- .thumbnail-overlay -->

    </div><!-- .thumbnail-wrapper -->

</div><!-- .photo-item -->
