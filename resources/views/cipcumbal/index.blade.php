<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="Cip Cumulative Balance"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Cip Cumulative Balance"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Cip Cumulative Balance</h6>
                        </div>
                        <div class="card-body p-3">
                            <a href="#" class="btn btn-sm btn-info mb-2" data-bs-toggle="modal" data-bs-target="#new-form">New Entry</a>                    
                            <div class="table-responsive p-0">
                                <table id="cipCumBalTable" class="table table-striped nowrap table-responsive p-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th align="center">Action</th>
                                            <th align="center">ID</th>
                                            <th align="center">Period CIP</th>
                                            <th align="center">Bal USD</th>
                                            <th align="center">Bal RP</th>
                                            <th align="center">Cumbal USD</th>
                                            <th align="center">Cumbal RP</th>
                                            <th align="center">Report Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.auth></x-footers.auth>
        </div>
        <x-plugins></x-plugins>

        @include('modal.new-entry')
        @include('modal.update-entry')

        @push('css')
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@3.0.2/dist/style.css" rel="stylesheet" type="text/css">
        @endpush
        
        
        <script src="{{('js/update-entry.js') }}"></script>

        @push('js')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>

        
        <script>
        $('#cipCumBalTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('cipcumbal.index') }}",
        columns: [
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            {data: 'id_ccb', name: 'id_ccb', className: 'text-center'},
            {data: 'period_cip', name: 'period_cip', className: 'text-center'},
            {data: 'bal_usd', name: 'bal_usd', className: 'text-center'},
            {data: 'bal_rp', name: 'bal_rp', className: 'text-center'},
            {data: 'cumbal_usd', name: 'cumbal_usd', className: 'text-center'},
            {data: 'cumbal_rp', name: 'cumbal_rp', className: 'text-center'},
            {data: 'report_status', name: 'report_status', className: 'text-center'}

        ]
    });
            
    $('#cipCumBalTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if(confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: '/cipcumbal/' + id,
                type: 'DELETE',
                success: function(result) {
                    $('#cipCumBalTable').DataTable().ajax.reload();
                }
            });
        }
    });


        // untuk button save
        document.getElementById('saveEntry').addEventListener('click', function() {
        var yearMonth = document.getElementById('yearMonth').value;
        var balanceUSD = document.getElementById('balanceUSD').value;
        var balanceRP = document.getElementById('balanceRP').value;
        var cumulativeBalanceUSD = document.getElementById('cumulativeBalanceUSD').value;
        var cumulativeBalanceRP = document.getElementById('cumulativeBalanceRP').value;

        // Logika penyimpanan data
        console.log({
            yearMonth: yearMonth,
            balanceUSD: balanceUSD,
            balanceRP: balanceRP,
            cumulativeBalanceUSD: cumulativeBalanceUSD,
            cumulativeBalanceRP: cumulativeBalanceRP
        });

        // Setelah berhasil simpan data, tutup modal
        $('#new-form').modal('hide');
    });

    document.getElementById('updateEntry').addEventListener('click', function() {
    const id = document.getElementById('updateForm').getAttribute('data-id');
    const yearMonth = document.getElementById('yearMonth').value;
    const balanceUSD = document.getElementById('balanceUSD').value;
    const balanceRP = document.getElementById('balanceRP').value;
    const cumulativeBalanceUSD = document.getElementById('cumulativeBalanceUSD').value;
    const cumulativeBalanceRP = document.getElementById('cumulativeBalanceRP').value;



    // Kirim data ke server untuk pembaruan
    fetch('/update-endpoint/' + id, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            yearMonth: yearMonth,
            balanceUSD: balanceUSD,
            balanceRP: balanceRP,
            cumulativeBalanceUSD: cumulativeBalanceUSD,
            cumulativeBalanceRP: cumulativeBalanceRP
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data updated successfully!');
            $('#update-form').modal('hide');
            // Refresh data table or update UI
        } else {
            alert('Error updating data.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


               
            </script>
        @endpush
    </x-layout>
