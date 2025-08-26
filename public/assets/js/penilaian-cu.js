document.addEventListener('DOMContentLoaded', function () {
    // Pastikan variabel klasifikasiData dan skorTabelRange ada sebelum menjalankan script
    if (typeof klasifikasiData === 'undefined' || typeof skorTabelRange === 'undefined') {
        return;
    }

    // Fungsi untuk memperbarui info rentang dan atribut input skor
    function updateSkorAndRange(row) {
        const bidang = row.querySelector('.select-bidang').value;
        const wujud = row.querySelector('.select-wujud').value;
        const tingkat = row.querySelector('.select-tingkat').value;
        const skorInput = row.querySelector('.input-skor');
        const rangeInfo = row.querySelector('.range-info');

        // Reset
        rangeInfo.textContent = '';
        skorInput.placeholder = 'Pilih klasifikasi';
        skorInput.removeAttribute('min');
        skorInput.removeAttribute('max');

        // Cek jika statusnya bukan 'final' sebelum membuat input bisa diedit
        if (skorInput.disabled === false) {
             skorInput.readOnly = true;
        }

        const range = skorTabelRange[bidang]?.[wujud]?.[tingkat];

        if (range) {
            const [minSkor, maxSkor] = range;
            
            if (skorInput.disabled === false) {
                if (minSkor === maxSkor) {
                    rangeInfo.textContent = `Skor Pasti: ${minSkor}`;
                    skorInput.value = minSkor;
                    skorInput.readOnly = true;
                } else {
                    rangeInfo.textContent = `Rentang: ${minSkor} - ${maxSkor}`;
                    skorInput.readOnly = false;
                }
            }
            skorInput.placeholder = 'Skor';
            skorInput.min = minSkor;
            skorInput.max = maxSkor;
        }
    }

    // Fungsi untuk mengelola event listener pada satu baris tabel
    function setupRow(row) {
        const selectBidang = row.querySelector('.select-bidang');
        const selectWujud = row.querySelector('.select-wujud');
        const selectTingkat = row.querySelector('.select-tingkat');
        
        selectBidang.addEventListener('change', function() {
            const bidangTerpilih = this.value;
            const wujudSaatIni = selectWujud.value;
            
            selectWujud.innerHTML = '<option value="">Pilih Wujud</option>';

            if (bidangTerpilih && klasifikasiData[bidangTerpilih]) {
                klasifikasiData[bidangTerpilih].forEach(function(wujud) {
                    const option = document.createElement('option');
                    option.value = wujud;
                    option.textContent = wujud;
                    // Jika wujud yang dipilih sebelumnya ada di daftar baru, pilih kembali
                    if(wujud === wujudSaatIni) {
                        option.selected = true;
                    }
                    selectWujud.appendChild(option);
                });
            }
            updateSkorAndRange(row);
        });

        selectWujud.addEventListener('change', () => updateSkorAndRange(row));
        selectTingkat.addEventListener('change', () => updateSkorAndRange(row));

        // Panggil sekali untuk inisialisasi setiap baris saat halaman dimuat
        updateSkorAndRange(row);
    }

    document.querySelectorAll('tbody tr').forEach(setupRow);
});
