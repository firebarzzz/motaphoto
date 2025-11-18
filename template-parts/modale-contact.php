<?php
/**
 * Template Part : Modale de Contact
 *
 * - Contient le formulaire Contact Form 7
 * - Chargée dans le footer pour être disponible partout
 * - Le champ "REF. PHOTO" est rempli dynamiquement via JS
 */
?>

<!-- ========================= -->
<!-- MODALE CONTACT -->
<!-- ========================= -->
<div id="myModal" class="modal">

    <div class="modal-content">

        <!-- Bouton Fermer -->
        <span class="close">&times;</span>

        <!-- Image d’en-tête (optionnel) -->
        <div class="modal-header-image">
            <img
                src="<?php echo get_template_directory_uri(); ?>/assets/images/contact-header.png"
                alt="Contact"
            >
        </div>

        <!-- Titre -->
        <h2 class="modal-title">Contactez-moi</h2>

        <!-- Formulaire CF7 -->
        <div class="modal-form">
            <?php
                echo do_shortcode(
                    '[contact-form-7 id="d3bdcf6" title="Formulaire Contact Modale"]'
                );
            ?>
        </div>

    </div><!-- .modal-content -->

</div><!-- #myModal -->
