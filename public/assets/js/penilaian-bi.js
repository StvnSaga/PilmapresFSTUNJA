/**
 * Mengelola fungsionalitas interaktif pada form penilaian Bahasa Inggris (BI).
 * Termasuk kalkulasi skor total, umpan balik real-time, dan toggle catatan.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Keluar jika form penilaian BI tidak ada di halaman ini.
    if (!document.getElementById('form-penilaian-bi')) return;

    const scoreInputs = document.querySelectorAll('.score-input');
    const totalScoreEl = document.getElementById('total-skor-bi');

    // Rubrik penilaian yang menjadi dasar untuk umpan balik otomatis.
    const scoreRubrics = {
        content: [{ min: 22, max: 25, label: 'Excellent' }, { min: 18, max: 21, label: 'Good' }, { min: 11, max: 17, label: 'Fair' }, { min: 5, max: 10, label: 'Poor' }],
        accuracy: [{ min: 22, max: 25, label: 'Excellent' }, { min: 18, max: 21, label: 'Good' }, { min: 11, max: 17, label: 'Fair' }, { min: 5, max: 10, label: 'Poor' }],
        fluency: [{ min: 16, max: 20, label: 'Excellent' }, { min: 11, max: 15, label: 'Good' }, { min: 8, max: 10, label: 'Fair' }, { min: 5, max: 7, label: 'Poor' }],
        pronunciation: [{ min: 16, max: 20, label: 'Excellent' }, { min: 11, max: 15, label: 'Good' }, { min: 8, max: 10, label: 'Fair' }, { min: 5, max: 7, label: 'Poor' }],
        performance: [{ min: 9, max: 10, label: 'Excellent' }, { min: 7, max: 8, label: 'Good' }, { min: 5, max: 6, label: 'Fair' }, { min: 3, max: 4, label: 'Poor' }]
    };

    // Menentukan label level (misal: 'Good') dan kelas warna berdasarkan skor.
    function getLevelLabel(field, score) {
        if (!score || isNaN(score)) return { label: '-', colorClass: '' };

        const level = scoreRubrics[field]?.find(r => score >= r.min && score <= r.max);
        if (!level) return { label: 'Invalid', colorClass: 'text-danger' };

        const colorClass = {
            'Excellent': 'text-success', 'Good': 'text-primary',
            'Fair': 'text-warning', 'Poor': 'text-danger'
        } [level.label] || '';

        return { label: level.label, colorClass };
    }

    // Menghitung dan menampilkan total skor dari semua input.
    function calculateTotalScore() {
        let total = 0;
        scoreInputs.forEach(input => total += Number(input.value) || 0);
        totalScoreEl.textContent = `${total} / 100`;
    }

    // Menerapkan event listener ke setiap input skor untuk pembaruan real-time.
    scoreInputs.forEach(input => {
        const eventHandler = () => {
            const field = input.dataset.field;
            const score = parseInt(input.value);
            const feedbackEl = document.getElementById(`feedback-${field}`);
            const { label, colorClass } = getLevelLabel(field, score);

            feedbackEl.textContent = label;
            feedbackEl.className = 'input-group-text feedback-span ' + colorClass;
            calculateTotalScore();
        };
        input.addEventListener('input', eventHandler);
        eventHandler(); // Panggil sekali saat memuat untuk inisialisasi.
    });

    // Mengelola fungsionalitas untuk menampilkan/menyembunyikan area komentar.
    const scoringRows = document.querySelectorAll('.scoring-row');
    scoringRows.forEach(row => {
        const toggleBtn = row.querySelector('.comment-toggle-btn');
        const commentContainer = row.querySelector('.comment-container');
        const commentTextarea = row.querySelector('.comment-textarea');

        if (toggleBtn && commentContainer && commentTextarea) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isHidden = commentContainer.style.display === 'none' || !commentContainer.style.display;
                commentContainer.style.display = isHidden ? 'block' : 'none';
            });

            // Mengubah warna ikon jika ada teks di dalam komentar.
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
