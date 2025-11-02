<div class="photo-item">
    <?php if (has_post_thumbnail()) : ?>
        <div class="photo-thumbnail">
            <?php the_post_thumbnail('medium'); ?>
            <div class="photo-overlay">
                <a href="<?php the_permalink(); ?>" class="icon-eye">
                    <span class="dashicons dashicons-visibility"></span>
                </a>
                <a href="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" 
                   class="icon-fullscreen" 
                   data-lightbox="photo">
                    <span class="dashicons dashicons-format-image"></span>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>