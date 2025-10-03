/**
 * Mengelola interaktivitas form penilaian Gagasan Kreatif (GK),
 * termasuk kalkulasi skor dan fitur komentar.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Keluar jika form penilaian GK tidak ada di halaman ini.
    if (!document.getElementById('form-penilaian-gk')) return;

    // --- Kalkulasi Skor Otomatis ---
    const scoreInputs = document.querySelectorAll('.score-input');
    const totalPenyajianEl = document.getElementById('total-penyajian');
    const totalSubstansiEl = document.getElementById('total-substansi');
    const totalKualitasEl = document.getElementById('total-kualitas');
    const totalGkEl = document.getElementById('total-gk');

    function calculateTotals() {
        let totalPenyajian = 0;
        let totalSubstansi = 0;
        let totalKualitas = 0;

        scoreInputs.forEach(input => {
            const skor = parseFloat(input.value) || 0;
            const bobot = parseFloat(input.dataset.bobot);
            const bagian = input.dataset.bagian;
            const nilaiTertimbang = (skor * bobot);

            if (bagian === '1') {
                totalPenyajian += nilaiTertimbang;
            } else if (bagian === '2') {
                totalSubstansi += nilaiTertimbang;
            } else if (bagian === '3') {
                totalKualitas += nilaiTertimbang;
            }
        });

        totalPenyajian /= 10;
        totalSubstansi /= 10;
        totalKualitas /= 10;
        const totalGk = totalPenyajian + totalSubstansi + totalKualitas;

        totalPenyajianEl.textContent = `${totalPenyajian.toFixed(2)} / 10.00`;
        totalSubstansiEl.textContent = `${totalSubstansi.toFixed(2)} / 70.00`;
        totalKualitasEl.textContent = `${totalKualitas.toFixed(2)} / 20.00`;
        totalGkEl.textContent = `${totalGk.toFixed(2)} / 100.00`;
    }

    scoreInputs.forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    calculateTotals(); // Panggil sekali untuk inisialisasi skor saat halaman dimuat.

    // --- Fitur Tampilkan/Sembunyikan Komentar ---
    const scoringRows = document.querySelectorAll('.scoring-row');
    scoringRows.forEach(row => {
        const toggleBtn = row.querySelector('.comment-toggle-btn');
        const commentContainer = row.querySelector('.comment-container');
        const commentTextarea = row.querySelector('.comment-textarea');

        if (toggleBtn && commentContainer && commentTextarea) {
            // Event untuk toggle area komentar.
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isHidden = commentContainer.style.display === 'none' || commentContainer.style.display === '';
                commentContainer.style.display = isHidden ? 'block' : 'none';
            });

            // Event untuk mengubah warna ikon jika ada teks di dalam komentar.
            commentTextarea.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    toggleBtn.classList.add('text-primary');
                    toggleBtn.classList.remove('text-secondary');
                } else {
                    toggleBtn.classList.remove('text-primary');
                    toggleBtn.classList.add('text-secondary');
                }
            });
        }
    });
});
