/**
 * Mengelola fungsionalitas akordeon kustom di halaman panduan.
 * Memastikan hanya satu bagian yang bisa terbuka pada satu waktu.
 */
document.addEventListener('DOMContentLoaded', function() {
    const cardCU = document.getElementById('card-cu');
    const cardGK = document.getElementById('card-gk');
    const cardBI = document.getElementById('card-bi');

    const collapseOneEl = document.getElementById('collapseOne');
    const collapseTwoEl = document.getElementById('collapseTwo');
    const collapseThreeEl = document.getElementById('collapseThree');

    // Menjalankan skrip hanya jika semua elemen yang dibutuhkan ada.
    if (cardCU && cardGK && cardBI && collapseOneEl && collapseTwoEl && collapseThreeEl) {
        const collapseCU = new bootstrap.Collapse(collapseOneEl, { toggle: false });
        const collapseGK = new bootstrap.Collapse(collapseTwoEl, { toggle: false });
        const collapseBI = new bootstrap.Collapse(collapseThreeEl, { toggle: false });

        // Melacak status buka/tutup setiap bagian akordeon.
        let isCUOpen = false;
        let isGKOpen = false;
        let isBIOpen = false;

        // Menangani klik pada kartu CU.
        cardCU.addEventListener('click', function() {
            if (isCUOpen) {
                collapseCU.hide();
            } else {
                collapseCU.show();
                collapseGK.hide();
                collapseBI.hide();
                isGKOpen = false;
                isBIOpen = false;
                document.getElementById('headingOne').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            isCUOpen = !isCUOpen;
        });

        // Menangani klik pada kartu GK.
        cardGK.addEventListener('click', function() {
            if (isGKOpen) {
                collapseGK.hide();
            } else {
                collapseGK.show();
                collapseCU.hide();
                collapseBI.hide();
                isCUOpen = false;
                isBIOpen = false;
                document.getElementById('headingTwo').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            isGKOpen = !isGKOpen;
        });

        // Menangani klik pada kartu BI.
        cardBI.addEventListener('click', function() {
            if (isBIOpen) {
                collapseBI.hide();
            } else {
                collapseBI.show();
                collapseCU.hide();
                collapseGK.hide();
                isCUOpen = false;
                isGKOpen = false;
                document.getElementById('headingThree').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            isBIOpen = !isBIOpen;
        });
    }
});
