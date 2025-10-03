/**
 * Mengelola fungsionalitas pratinjau foto secara real-time
 * pada form manajemen Rekam Jejak.
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#form-rekam-jejak');
    if (!form) return;

    const fileInputs = form.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                const previewContainer = event.target.closest('.form-group').querySelector('.winner-photo-preview');

                if (!previewContainer) return;

                const previewImage = previewContainer.querySelector('img');
                const previewIcon = previewContainer.querySelector('.icon-placeholder');

                // Menampilkan gambar yang dipilih di area pratinjau.
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';

                    if (previewIcon) {
                        previewIcon.style.display = 'none';
                    }
                }

                reader.readAsDataURL(file);
            }
        });
    });
});
