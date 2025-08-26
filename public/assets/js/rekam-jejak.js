document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#form-rekam-jejak');
    if (!form) return;

    // Cari semua input file di dalam form
    const fileInputs = form.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                
                // Cari elemen preview yang berada di dalam .form-group yang sama dengan input ini
                const previewContainer = event.target.closest('.form-group').querySelector('.winner-photo-preview');
                if (!previewContainer) return;

                const previewImage = previewContainer.querySelector('img');
                const previewIcon = previewContainer.querySelector('.icon-placeholder');
                
                reader.onload = function(e) {
                    // Tampilkan gambar yang dipilih
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    
                    // Sembunyikan ikon placeholder
                    if (previewIcon) {
                        previewIcon.style.display = 'none';
                    }
                }
                
                // Baca file sebagai URL
                reader.readAsDataURL(file);
            }
        });
    });
});
