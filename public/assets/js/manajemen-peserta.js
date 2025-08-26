document.addEventListener('DOMContentLoaded', function () {
    const accordionElement = document.getElementById('accordionBerkas');
    if (accordionElement) {
        const lastOpenAccordionId = sessionStorage.getItem('lastOpenAccordionId');
        if (lastOpenAccordionId) {
            const accordionToOpen = document.getElementById(lastOpenAccordionId);
            if (accordionToOpen) {
                new bootstrap.Collapse(accordionToOpen, { toggle: true });
            }
        }
        accordionElement.addEventListener('show.bs.collapse', function (event) {
            sessionStorage.setItem('lastOpenAccordionId', event.target.id);
        });
    }

    const uploadModal = document.getElementById('uploadBerkasModal');
    if (uploadModal) {
        uploadModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = uploadModal.querySelector('#form-upload-berkas');
            const modalTitle = uploadModal.querySelector('.modal-title');
            const cuFields = uploadModal.querySelector('#cu-fields');
            const inputDeskripsiCu = form.querySelector('#upload_deskripsi_cu');

            form.reset();
            const oldMethodInput = form.querySelector('input[name="_method"]');
            if (oldMethodInput) oldMethodInput.remove();

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

    const editCuModal = document.getElementById('editCuModal');
    if (editCuModal) {
        editCuModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = editCuModal.querySelector('#form-edit-cu');
            form.action = button.dataset.updateUrl;
            form.querySelector('#edit_deskripsi_cu').value = button.dataset.namaBerkas;
            form.querySelector('#edit_tingkat_cu').value = button.dataset.tingkat;
        });
    }

    const editPesertaModal = document.getElementById('editPesertaModal');
    if (editPesertaModal) {
        editPesertaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = editPesertaModal.querySelector('#form-edit-peserta');
            const data = button.dataset;
            
            // !! PERBAIKAN UTAMA: Buat URL secara manual di JavaScript !!
            const baseUrl = "/panel/peserta/";
            form.action = baseUrl + data.id;

            // Isi semua input field
            form.querySelector('#edit_nama_lengkap').value = data.nama_lengkap || '';
            form.querySelector('#edit_nim').value = data.nim || '';
            form.querySelector('#edit_prodi').value = data.prodi || '';
            form.querySelector('#edit_angkatan').value = data.angkatan || '';
            form.querySelector('#edit_email').value = data.email || '';
            form.querySelector('#edit_no_hp').value = data.no_hp || '';
            form.querySelector('#edit_ipk').value = data.ipk || '';
        });
    }

    function initializeIpkInput(modalId, inputId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const ipkInput = modal.querySelector(inputId);
            if (ipkInput) {
                ipkInput.addEventListener('input', function (e) {
                    e.target.value = e.target.value.replace(/,/g, '.');
                });
            }
        }
    }

    initializeIpkInput('tambahPesertaModal', '#tambah_ipk');
    initializeIpkInput('editPesertaModal', '#edit_ipk');
});
