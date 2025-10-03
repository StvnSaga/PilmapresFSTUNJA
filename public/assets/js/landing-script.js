/**
 * Mengelola carousel pemenang di halaman utama.
 * Memperbarui judul tahun secara dinamis saat slide berganti.
 */
document.addEventListener('DOMContentLoaded', function() {
    const pemenangCarousel = document.getElementById('pemenangCarousel');
    if (pemenangCarousel) {
        const carouselTahunTitle = document.getElementById('carousel-tahun-title');

        const updateJudulTahun = (activeItem) => {
            if (activeItem) {
                const tahun = activeItem.getAttribute('data-tahun');
                carouselTahunTitle.textContent = 'Pemenang Tahun ' + tahun;
            }
        };

        // Mengatur judul saat halaman pertama kali dimuat.
        const initialActiveItem = pemenangCarousel.querySelector('.carousel-item.active');
        updateJudulTahun(initialActiveItem);

        // Memperbarui judul saat slide mulai berganti untuk respons yang lebih cepat.
        pemenangCarousel.addEventListener('slide.bs.carousel', function(event) {
            updateJudulTahun(event.relatedTarget);
        });
    }
});