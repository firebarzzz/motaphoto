<div id="contact-modal-2" class="modal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-wrapper">
        <div class="modal-content">
            <button class="modal-close" aria-label="Fermer">
                <svg width="24" height="24" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 
                    12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
            
            <div class="modal-header">
                <img src="<?php echo get_template_directory_uri(); ?>/images/contact-header.png" alt="Contact">
            </div>
            
            <div class="modal-body">
                <?php echo do_shortcode('[contact-form-7 id="d3bdcf6" title="Formulaire Contact Modale"]'); ?>
            </div>
        </div>
    </div>
</div>
