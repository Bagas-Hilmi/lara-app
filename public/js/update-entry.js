document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded');

    // Fungsi untuk menangani klik tombol update
    function handleUpdateClick(event) {
        console.log('Update button clicked');

        // Ambil data dari tombol
        const button = event.currentTarget;
        const id = button.getAttribute('data-id');
        const period = button.getAttribute('data-period');
        const balUsd = button.getAttribute('data-bal-usd');
        const balRp = button.getAttribute('data-bal-rp');
        const cumbalUsd = button.getAttribute('data-cumbal-usd');
        const cumbalRp = button.getAttribute('data-cumbal-rp');

        // Isi data ke dalam form modal
        document.getElementById('updateId').value = id;
        document.getElementById('yearMonthUpdate').value = period;
        document.getElementById('balanceUSDUpdate').value = balUsd;
        document.getElementById('balanceRPUpdate').value = balRp;
        document.getElementById('cumulativeBalanceUSDUpdate').value = cumbalUsd;
        document.getElementById('cumulativeBalanceRPUpdate').value = cumbalRp;

        // Tampilkan modal
        const modal = document.getElementById('update-form');
        if (modal) {
            console.log('Modal found, attempting to show');
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            console.error('Modal element not found');
        }
    }

    // Fungsi untuk menambahkan event listeners
    function addUpdateButtonListeners() {
        console.log('Adding update button listeners');
        document.querySelectorAll('.update-btn').forEach(button => {
            button.removeEventListener('click', handleUpdateClick);
            button.addEventListener('click', handleUpdateClick);
        });
    }

    // Tambahkan listeners saat halaman dimuat
    addUpdateButtonListeners();

    // Jika menggunakan DataTables, tambahkan ini:
    if ($.fn.dataTable) {
        $('#cipCumBalTable').on('draw.dt', function () {
            console.log('DataTable redrawn, re-adding listeners');
            addUpdateButtonListeners();
        });
    }

    // Event listener untuk tombol update di dalam modal
    document.getElementById('updateEntry').addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Update button clicked');
    
        const form = document.getElementById('updateEntryForm');
        const formData = new FormData(form);
    
        // Pastikan `mode` diatur ke `UPDATE`
        formData.append('mode', 'UPDATE');
        // Menambahkan informasi pengguna untuk `updated_by`
        const userIdMeta = document.querySelector('meta[name="user-id"]');
        const userId = userIdMeta ? userIdMeta.getAttribute('content') : null;
        formData.append('updated_by', userId);
    
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Entry updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('update-form')).hide();
                if ($.fn.DataTable) {
                    $('#cipCumBalTable').DataTable().ajax.reload();
                }
            } else {
                alert(data.message || 'Error updating entry');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the entry');
        });
    });
});
