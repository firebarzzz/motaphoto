document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('contact-modal');
    const openButtons = document.querySelectorAll('.open-contact');
    const closeButton = modal.querySelector('.modal-close');
    const overlay = modal.querySelector('.modal-overlay');

    openButtons.forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            modal.classList.add('active');
        });
    });

    [closeButton, overlay].forEach(el => {
        el.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    });
});
