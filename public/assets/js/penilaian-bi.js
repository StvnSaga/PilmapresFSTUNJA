// public/assets/js/penilaian-bi.js
document.addEventListener('DOMContentLoaded', function () {
    if (!document.getElementById('form-penilaian-bi')) return;

    const scoreInputs = document.querySelectorAll('.score-input');
    const totalScoreEl = document.getElementById('total-skor-bi');

    // Rubrik skor sekarang menjadi bagian dari file JS, lebih terorganisir
    const scoreRubrics = {
        content:       [{ min: 22, max: 25, label: 'Excellent' }, { min: 18, max: 21, label: 'Good' }, { min: 11, max: 17, label: 'Fair' }, { min: 5,  max: 10, label: 'Poor' }],
        accuracy:      [{ min: 22, max: 25, label: 'Excellent' }, { min: 18, max: 21, label: 'Good' }, { min: 11, max: 17, label: 'Fair' }, { min: 5,  max: 10, label: 'Poor' }],
        fluency:       [{ min: 16, max: 20, label: 'Excellent' }, { min: 11, max: 15, label: 'Good' }, { min: 8,  max: 10, label: 'Fair' }, { min: 5,  max: 7,  label: 'Poor' }],
        pronunciation: [{ min: 16, max: 20, label: 'Excellent' }, { min: 11, max: 15, label: 'Good' }, { min: 8,  max: 10, label: 'Fair' }, { min: 5,  max: 7,  label: 'Poor' }],
        performance:   [{ min: 9,  max: 10, label: 'Excellent' }, { min: 7,  max: 8,  label: 'Good' }, { min: 5,  max: 6,  label: 'Fair' }, { min: 3,  max: 4,  label: 'Poor' }]
    };

    function getLevelLabel(field, score) {
        if (!score || isNaN(score)) return { label: '-', colorClass: '' };
        const level = scoreRubrics[field]?.find(r => score >= r.min && score <= r.max);
        if (!level) return { label: 'Invalid', colorClass: 'text-danger' };

        const colorClass = {
            'Excellent': 'text-success', 'Good': 'text-primary',
            'Fair': 'text-warning', 'Poor': 'text-danger'
        }[level.label] || '';
        return { label: level.label, colorClass };
    }

    function calculateTotalScore() {
        let total = 0;
        scoreInputs.forEach(input => total += Number(input.value) || 0);
        totalScoreEl.textContent = `${total} / 100`;
    }

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
        // Panggil sekali saat halaman dimuat untuk menampilkan skor & feedback awal
        eventHandler();
    });

        const scoringRows = document.querySelectorAll('.scoring-row');

    scoringRows.forEach(row => {
        const toggleBtn = row.querySelector('.comment-toggle-btn');
        const commentContainer = row.querySelector('.comment-container');
        const commentTextarea = row.querySelector('.comment-textarea');

        if (toggleBtn && commentContainer && commentTextarea) {
            // Event untuk menampilkan/menyembunyikan textarea
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isHidden = commentContainer.style.display === 'none' || !commentContainer.style.display;
                commentContainer.style.display = isHidden ? 'block' : 'none';
            });

            // Event untuk mengubah warna ikon jika ada teks
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