/**
 * Scripts JavaScript - Mota Photo
 *
 * Gère :
 * 1. Menu mobile
 * 2. Modale de contact
 * 3. Filtres & tri AJAX
 * 4. Pagination AJAX (Load More)
 * 5. Lightbox
 * 6. Animations au scroll
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /* ============================================================
           1. MENU MOBILE (BURGER)
        ============================================================ */
        const $menuToggle = $('.menu-toggle');
        const $mobileMenu = $('.mobile-menu');
        const $body       = $('body');

        // Ouvrir / fermer menu mobile
        $menuToggle.on('click', function() {
            $(this).toggleClass('active');
            $mobileMenu.toggleClass('active');
            $body.toggleClass('menu-open');
        });

        // Fermer menu mobile en cliquant sur un lien
        $('.mobile-nav-menu a').on('click', function() {
            $menuToggle.removeClass('active');
            $mobileMenu.removeClass('active');
            $body.removeClass('menu-open');
        });

        // Fermer menu mobile avec Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $mobileMenu.hasClass('active')) {
                $menuToggle.removeClass('active');
                $mobileMenu.removeClass('active');
                $body.removeClass('menu-open');
            }
        });


        /* ============================================================
           2. MODALE DE CONTACT
        ============================================================ */
        const $modal          = $('#myModal');
        const $openModalBtn   = $('.btn-contact-photo'); // boutons photos
        const $closeModalBtn  = $('.modal .close');

        // Ouvrir via lien menu WordPress
        $('.open-modal-from-menu').on('click', function(e) {
            e.preventDefault();
            $modal.addClass('active');
            $body.addClass('modal-open');
        });

        // Ouvrir via boutons photo
        $openModalBtn.on('click', function() {
            const photoRef = $(this).data('reference');
            if (photoRef) {
                $('#photo-reference').val(photoRef);
            }
            $modal.addClass('active');
            $body.addClass('modal-open');
        });

        // Fermer via X
        $closeModalBtn.on('click', function() {
            $modal.removeClass('active');
            $body.removeClass('modal-open');
        });

        // Fermer en cliquant en dehors
        $modal.on('click', function(e) {
            if ($(e.target).is($modal)) {
                $modal.removeClass('active');
                $body.removeClass('modal-open');
            }
        });

        // Fermer via Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $modal.hasClass('active')) {
                $modal.removeClass('active');
                $body.removeClass('modal-open');
            }
        });


        /* ============================================================
           3. FILTRES & TRI AJAX
        ============================================================ */
        const $categoryFilter = $('#category-filter, #filter-categorie');
        const $formatFilter   = $('#format-filter, #filter-format');
        const $dateSort       = $('#date-sort, #filter-date');
        const $photoGrid      = $('#photo-grid, #photo-container .thumbnail-container-accueil');
        const $loadMoreBtn    = $('#load-more, #load-more-posts');

        let currentPage = 1;

        function loadPhotos(page = 1, append = false) {
            const category = $categoryFilter.val() || '';
            const format   = $formatFilter.val() || '';
            const order    = $dateSort.val() || 'DESC';

            if (!append) {
                $photoGrid.html('<div class="loading">Chargement...</div>');
            }

            $loadMoreBtn.prop('disabled', true).text('Chargement...');

            $.ajax({
                url: motaphoto_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos',
                    nonce: motaphoto_ajax.nonce,
                    category: category,
                    format: format,
                    order: order,
                    page: page
                },
                success: function(response) {
                    if (response.success) {
                        if (append) {
                            $photoGrid.append(response.data.html);
                        } else {
                            $photoGrid.html(response.data.html);
                        }

                        // Bouton "Charger plus"
                        if (response.data.has_more) {
                            $loadMoreBtn.prop('disabled', false).text('Charger plus').show();
                        } else {
                            $loadMoreBtn.hide();
                        }

                        currentPage = page;

                        initLightbox();
                    } else {
                        $photoGrid.html('<p>Aucune photo trouvée.</p>');
                        $loadMoreBtn.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', error);
                    $loadMoreBtn.prop('disabled', false).text('Charger plus');
                }
            });
        }

        // Événements sur les filtres
        $categoryFilter.add($formatFilter).add($dateSort).on('change', function() {
            currentPage = 1;
            loadPhotos(1, false);
        });

        // Bouton "Charger plus"
        $loadMoreBtn.on('click', function() {
            loadPhotos(currentPage + 1, true);
        });


        /* ============================================================
           4. LIGHTBOX
        ============================================================ */
        const lightboxTemplate = `
            <div id="lightbox" class="lightbox">
                <div class="lightbox-content">
                    <span class="lightbox-close">&times;</span>
                    <span class="lightbox-nav lightbox-prev"><i class="fas fa-chevron-left"></i></span>
                    <img src="" alt="">
                    <span class="lightbox-nav lightbox-next"><i class="fas fa-chevron-right"></i></span>
                    <div class="lightbox-info">
                        <span class="lightbox-title"></span>
                        <span class="lightbox-reference"></span>
                    </div>
                </div>
            </div>
        `;
        $('body').append(lightboxTemplate);

        const $lightbox      = $('#lightbox');
        const $lightboxImg   = $lightbox.find('img');
        const $lightboxTitle = $lightbox.find('.lightbox-title');
        const $lightboxRef   = $lightbox.find('.lightbox-reference');

        let photos = [];
        let currentPhotoIndex = 0;

        function initLightbox() {
            photos = [];
            $('.icon-fullscreen').each(function() {
                photos.push({
                    url:       $(this).data('photo-url'),
                    title:     $(this).data('photo-title'),
                    reference: $(this).data('photo-reference')
                });
            });

            // Clic sur une vignette → ouvrir lightbox
            $('.icon-fullscreen').off('click').on('click', function() {
                const url = $(this).data('photo-url');
                currentPhotoIndex = photos.findIndex(p => p.url === url);
                showPhoto(currentPhotoIndex);
                $lightbox.addClass('active');
                $body.addClass('lightbox-open');
            });
        }

        function showPhoto(index) {
            if (!photos[index]) return;
            const p = photos[index];
            $lightboxImg.attr('src', p.url);
            $lightboxTitle.text(p.title);
            $lightboxRef.text(p.reference);
        }

        // Navigation lightbox
        $('.lightbox-prev').on('click', function() {
            currentPhotoIndex = (currentPhotoIndex - 1 + photos.length) % photos.length;
            showPhoto(currentPhotoIndex);
        });
        $('.lightbox-next').on('click', function() {
            currentPhotoIndex = (currentPhotoIndex + 1) % photos.length;
            showPhoto(currentPhotoIndex);
        });

        // Fermer lightbox
        $('.lightbox-close').on('click', function() {
            $lightbox.removeClass('active');
            $body.removeClass('lightbox-open');
        });

        // Clic en dehors
        $lightbox.on('click', function(e) {
            if ($(e.target).is($lightbox)) {
                $lightbox.removeClass('active');
                $body.removeClass('lightbox-open');
            }
        });

        // Navigation clavier
        $(document).on('keydown', function(e) {
            if (!$lightbox.hasClass('active')) return;
            if (e.key === 'Escape') {
                $lightbox.removeClass('active');
                $body.removeClass('lightbox-open');
            }
            if (e.key === 'ArrowLeft') {
                currentPhotoIndex = (currentPhotoIndex - 1 + photos.length) % photos.length;
                showPhoto(currentPhotoIndex);
            }
            if (e.key === 'ArrowRight') {
                currentPhotoIndex = (currentPhotoIndex + 1) % photos.length;
                showPhoto(currentPhotoIndex);
            }
        });

        initLightbox();


        /* ============================================================
           5. ANIMATIONS AU SCROLL
        ============================================================ */
        function checkVisibility() {
            $('.photo-item').each(function() {
                const elementTop     = $(this).offset().top;
                const elementBottom  = elementTop + $(this).outerHeight();
                const viewportTop    = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('visible');
                }
            });
        }

        checkVisibility();
        $(window).on('scroll', checkVisibility);

    });

})(jQuery);
