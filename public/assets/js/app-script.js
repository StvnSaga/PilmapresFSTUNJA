/**
 * Menangani smooth scroll untuk tautan navigasi.
 * Ini memastikan bahwa saat tautan di-klik, halaman akan scroll dengan mulus
 * ke bagian yang dituju dan memberikan offset agar tidak tertutup oleh navbar.
 */
document.addEventListener('DOMContentLoaded', function() {
    const scrollLinks = document.querySelectorAll('.scroll-link');
    const navbar = document.querySelector('.navbar');
    const navbarHeight = navbar ? navbar.offsetHeight : 0;

    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const elementPosition = targetElement.offsetTop;
                const offsetPosition = elementPosition - navbarHeight - 20;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});
