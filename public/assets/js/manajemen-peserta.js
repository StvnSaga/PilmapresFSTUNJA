document.addEventListener('DOMContentLoaded', function() {

    const fileInput = document.getElementById('upload_file');
    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Atur batas maksimal ukuran file di sini (dalam MB)
                const maxSizeInMB = 5;
                const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

                if (file.size > maxSizeInBytes) {
                    // Tampilkan notifikasi error menggunakan SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: `Ukuran file tidak boleh melebihi ${maxSizeInMB} MB.`,
                    });

                    // Kosongkan input file agar file yang salah tidak terkirim
                    event.target.value = '';
                }
            }
        });
    }

    // Menyimpan state accordion berkas yang terakhir dibuka.
    const accordionElement = document.getElementById('accordionBerkas');
    if (accordionElement) {
        const lastOpenAccordionId = sessionStorage.getItem('lastOpenAccordionId');
        if (lastOpenAccordionId) {
            const accordionToOpen = document.getElementById(lastOpenAccordionId);
            if (accordionToOpen) {
                new bootstrap.Collapse(accordionToOpen, {
                    toggle: true
                });
            }
        }
        accordionElement.addEventListener('show.bs.collapse', function(event) {
            sessionStorage.setItem('lastOpenAccordionId', event.target.id);
        });
    }

    // Mengelola modal upload berkas untuk mode 'store' (baru) dan 'update' (ganti).
    const uploadModal = document.getElementById('uploadBerkasModal');
    if (uploadModal) {
        uploadModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const form = uploadModal.querySelector('#form-upload-berkas');
            const modalTitle = uploadModal.querySelector('.modal-title');
            const cuFields = uploadModal.querySelector('#cu-fields');
            const inputDeskripsiCu = form.querySelector('#upload_deskripsi_cu');

            form.reset();
            form.querySelector('input[name="_method"]')?.remove();

            const updateUrl = button.dataset.updateUrl;

            if (updateUrl) {
                modalTitle.textContent = 'Ganti Berkas: ' + button.dataset.namaBerkas;
                form.action = updateUrl;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                cuFields.style.display = 'none';
                inputDeskripsiCu.disabled = true;

            } else {
                const jenisBerkas = button.dataset.jenisBerkas;
                modalTitle.textContent = 'Upload: ' + button.dataset.namaBerkas;
                form.action = button.dataset.storeUrl;

                if (jenisBerkas === 'CU') {
                    cuFields.style.display = 'block';
                    inputDeskripsiCu.disabled = false;
                } else {
                    cuFields.style.display = 'none';
                    inputDeskripsiCu.disabled = true;
                }

                form.querySelector('#upload_jenis_berkas').value = jenisBerkas;
                form.querySelector('#upload_nama_berkas_wajib').value = button.dataset.namaBerkas;
            }
        });
    }

    // Mengisi data modal 'edit CU' saat dibuka.
    const editCuModal = document.getElementById('editCuModal');
    if (editCuModal) {
        editCuModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const form = editCuModal.querySelector('#form-edit-cu');
            form.action = button.dataset.updateUrl;
            form.querySelector('#edit_deskripsi_cu').value = button.dataset.namaBerkas;
            form.querySelector('#edit_tingkat_cu').value = button.dataset.tingkat;
        });
    }

    // Mengisi data modal 'edit peserta' saat dibuka.
    const editPesertaModal = document.getElementById('editPesertaModal');
    if (editPesertaModal) {
        editPesertaModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const form = editPesertaModal.querySelector('#form-edit-peserta');
            const data = button.dataset;

            form.action = `/panel/peserta/${data.id}`;
            form.querySelector('#edit_nama_lengkap').value = data.nama_lengkap || '';
            form.querySelector('#edit_nim').value = data.nim || '';
            form.querySelector('#edit_prodi').value = data.prodi || '';
            form.querySelector('#edit_angkatan').value = data.angkatan || '';
            form.querySelector('#edit_email').value = data.email || '';
            form.querySelector('#edit_no_hp').value = data.no_hp || '';
            form.querySelector('#edit_ipk').value = data.ipk || '';
        });
    }

    // Menangani format input IPK (mengubah koma menjadi titik).
    function initializeIpkInput(modalId, inputId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const ipkInput = modal.querySelector(inputId);
            if (ipkInput) {
                ipkInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/,/g, '.');
                });
            }
        }
    }

    initializeIpkInput('tambahPesertaModal', '#tambah_ipk');
    initializeIpkInput('editPesertaModal', '#edit_ipk');
});