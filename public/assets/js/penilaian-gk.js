document.addEventListener('DOMContentLoaded', function() {
    // Pastikan form ada di halaman ini sebelum menjalankan script apa pun
    if (!document.getElementById('form-penilaian-gk')) return;

    // Bagian 1: Logika Kalkulasi Skor (yang sudah ada)
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

        // Skor dibagi 10 karena nilai input 5-10 sedangkan bobot persentase
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

    // Hitung total awal saat halaman dimuat
    calculateTotals();

    // Bagian 2: Logika BARU untuk Fitur Komentar
    const scoringRows = document.querySelectorAll('.scoring-row');

    scoringRows.forEach(row => {
        const toggleBtn = row.querySelector('.comment-toggle-btn');
        const commentContainer = row.querySelector('.comment-container');
        const commentTextarea = row.querySelector('.comment-textarea');

        if (toggleBtn && commentContainer && commentTextarea) {
             // Event untuk menampilkan/menyembunyikan textarea saat ikon diklik
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Cek apakah sedang tersembunyi, lalu ubah statusnya
                const isHidden = commentContainer.style.display === 'none' || commentContainer.style.display === '';
                commentContainer.style.display = isHidden ? 'block' : 'none';
            });

            // Event untuk mengubah warna ikon jika ada teks di dalamnya
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
