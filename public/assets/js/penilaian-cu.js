/**
 * Mengelola interaktivitas form penilaian Capaian Unggulan (CU).
 * Termasuk pembaruan dinamis pada dropdown dan validasi rentang skor.
 */
document.addEventListener('DOMContentLoaded', function() {
    if (typeof klasifikasiData === 'undefined' || typeof skorTabelRange === 'undefined') {
        return;
    }

    // Memperbarui rentang skor dan atribut input berdasarkan pilihan klasifikasi.
    function updateSkorAndRange(row) {
        const bidang = row.querySelector('.select-bidang').value;
        const wujud = row.querySelector('.select-wujud').value;
        const tingkat = row.querySelector('.select-tingkat').value;
        const skorInput = row.querySelector('.input-skor');
        const rangeInfo = row.querySelector('.range-info');

        // Reset tampilan info rentang dan input skor.
        rangeInfo.textContent = '';
        skorInput.placeholder = 'Pilih klasifikasi';
        skorInput.removeAttribute('min');
        skorInput.removeAttribute('max');
        if (skorInput.disabled === false) {
            skorInput.readOnly = true;
        }

        const range = skorTabelRange[bidang]?.[wujud]?.[tingkat];

        if (range) {
            const [minSkor, maxSkor] = range;
            skorInput.placeholder = 'Skor';
            skorInput.min = minSkor;
            skorInput.max = maxSkor;

            if (skorInput.disabled === false) {
                // Jika skor pasti (min = max), isi otomatis dan kunci input.
                if (minSkor === maxSkor) {
                    rangeInfo.textContent = `Skor Pasti: ${minSkor}`;
                    skorInput.value = minSkor;
                    skorInput.readOnly = true;
                } else {
                    rangeInfo.textContent = `Rentang: ${minSkor} - ${maxSkor}`;
                    skorInput.readOnly = false;
                }
            }
        }
    }

    // Mengatur event listener untuk setiap baris penilaian.
    function setupRow(row) {
        const selectBidang = row.querySelector('.select-bidang');
        const selectWujud = row.querySelector('.select-wujud');
        const selectTingkat = row.querySelector('.select-tingkat');

        // Memperbarui dropdown 'wujud' saat 'bidang' berubah.
        selectBidang.addEventListener('change', function() {
            const bidangTerpilih = this.value;
            const wujudSaatIni = selectWujud.value;

            selectWujud.innerHTML = '<option value="">Pilih Wujud</option>';

            if (bidangTerpilih && klasifikasiData[bidangTerpilih]) {
                klasifikasiData[bidangTerpilih].forEach(function(wujud) {
                    const option = document.createElement('option');
                    option.value = wujud;
                    option.textContent = wujud;
                    if (wujud === wujudSaatIni) {
                        option.selected = true;
                    }
                    selectWujud.appendChild(option);
                });
            }
            updateSkorAndRange(row);
        });

        selectWujud.addEventListener('change', () => updateSkorAndRange(row));
        selectTingkat.addEventListener('change', () => updateSkorAndRange(row));

        // Panggil sekali untuk inisialisasi saat halaman dimuat.
        updateSkorAndRange(row);
    }

    document.querySelectorAll('tbody tr').forEach(setupRow);
});
