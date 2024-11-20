<div class="modal fade" id="update-form" tabindex="-1" aria-labelledby="updateFormLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #42bd37;">
        <h5 class="modal-title" id="updateFormLabel" style="color: white;">Update Entry Cip Cumulative Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateEntryForm" action="{{ route('cipcumbal.store') }}" method="POST">
          @csrf
          <input type="hidden" name="flag" value="update">
          <input type="hidden" id="updateId" name="id">
          <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">

          <div class="container-fluid">
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="yearMonthUpdate" class="form-label">Year / Month</label>
                <div class="month-input-container">
                    <input type="month" class="form-control" id="yearMonthUpdate" name="period_cip" required>
                </div>
              </div>
            </div>

            <div class="row mb-3 balance-container">
              <div class="col-md-6">
                <label for="balanceUSDUpdate" class="form-label">Balance (USD)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="balanceUSDUpdate" name="bal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="balanceRPUpdate" class="form-label">Balance (RP)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="balanceRPUpdate" name="bal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
              </div>
            </div>

            <div class="row mb-3 cumulative-container">
              <div class="col-md-6">
                <label for="cumulativeBalanceUSDUpdate" class="form-label">Cumulative Balance (USD)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="cumulativeBalanceUSDUpdate" name="cumbal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="cumulativeBalanceRPUpdate" class="form-label">Cumulative Balance (RP)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="cumulativeBalanceRPUpdate" name="cumbal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn bg-gradient-success" id="updateEntry">Update</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
      const numberInputs = document.querySelectorAll('input.update-input'); // Menggunakan kelas khusus untuk input update

      numberInputs.forEach(input => {
          input.addEventListener('input', function() {
              // Menghapus semua karakter yang bukan angka, koma, dan titik
              let value = this.value.replace(/[^0-9.,]/g, '');

              // Memisahkan bagian integer dan desimal
              let parts = value.split(',');
              let integerPart = parts[0].replace(/\./g, ''); // Menghapus titik dari bagian integer
              let decimalPart = parts[1] ? ',' + parts[1].slice(0, 2) : ''; // Menyimpan bagian desimal maksimum 2 digit

              // Memformat bagian integer dengan pemisah ribuan
              let formattedInteger = parseInt(integerPart).toLocaleString('id-ID');

              // Menggabungkan bagian integer dan desimal
              this.value = formattedInteger + decimalPart;
          });

          input.addEventListener('blur', function() {
              // Format saat fokus hilang (blur)
              let value = this.value.replace(/\./g, '').replace(/,/g, '.'); // Menghapus titik dan mengubah koma menjadi titik
              if (value) {
                  this.value = parseFloat(value).toString(); 
              }
          });
      });
  });
</script>


<script>
  document.addEventListener('DOMContentLoaded', function () {

    // Fungsi untuk menangani klik tombol update
    function handleUpdateClick(event) {

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
            addUpdateButtonListeners();
        });
    }
  });
</script>

<script>
  document.getElementById('updateEntry').addEventListener('click', function(e) {
      e.preventDefault();

      // Tampilkan SweetAlert untuk konfirmasi
      Swal.fire({
          title: 'Apakah Anda yakin ingin memperbarui entri ini?',
          text: "Data ini akan diperbarui!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, perbarui!',
          cancelButtonText: 'Batal'
      }).then((result) => {
          if (result.isConfirmed) {
              const form = document.getElementById('updateEntryForm');
              const formData = new FormData(form);
  
              // Pastikan `mode` diatur ke `UPDATE`
              formData.append('mode', 'UPDATE');
              
              // Menambahkan informasi pengguna untuk `updated_by`
              const userIdMeta = document.querySelector('meta[name="user-id"]');
              const userId = userIdMeta ? userIdMeta.getAttribute('content') : null;
              formData.append('updated_by', userId);
  
              // Mengirim data menggunakan AJAX
              $.ajax({
                  url: form.action,
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  success: function(data) {
                      if (data.success) {
                          // Tampilkan pesan sukses menggunakan SweetAlert
                          Swal.fire({
                                title: "Sukses!",
                                text: "Entry berhasil di update!",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000
                                
                          });
                          // Tutup modal
                          bootstrap.Modal.getInstance(document.getElementById('update-form')).hide();
                          
                          // Reload DataTable tanpa refresh halaman
                          if ($.fn.DataTable) {
                              $('#cipCumBalTable').DataTable().ajax.reload();
                          }
                      } else {
                          Swal.fire(
                              'Error!',
                              data.message || 'Error updating entry',
                              'error'
                          );
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('Error:', error);
                      Swal.fire(
                          'Error!',
                          'An error occurred while updating the entry',
                          'error'
                      );
                  }
              });
          }
      });
  });
</script>



