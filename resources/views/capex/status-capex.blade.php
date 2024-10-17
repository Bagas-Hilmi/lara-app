<div class="modal fade" id="status-modal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="statusModalLabel" style="color: white;">Status Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive p-0">
                    <table id="status-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                        <thead>
                            <tr>
                                <th align="center">ID Capex Status</th>
                                <th align="center">ID Capex</th>
                                <th align="center">Status </th>
                                <th align="center">Created At</th>
                                <th align="center">Updated At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
       // Event saat modal status-modal dibuka
       $('#status-modal').on('shown.bs.modal', function (e) {
           var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id


           $('#status-table').DataTable({
               destroy: true, // Menghancurkan instance DataTable sebelumnya
               processing: true,
               serverSide: true,
               order: [[0, 'desc']],
               ajax: {
                   url: '/capex/' + idCapex, // Mengarah ke function show dengan id_capex
                   type: 'GET',
                   data: { flag: 'status' }
               },
               columns: [
                   { data: 'id_capex_status', name: 'id_capex_status', className: 'text-center'},
                   { data: 'id_capex', name: 'id_capex', className: 'text-center'},
                   { data: 'status', name: 'status', className: 'text-center'},
                   {
                       data: 'created_at',
                       name: 'created_at',
                       className: 'text-center',
                       render: function(data) {
                           return moment(data).format('YYYY-MM-DD'); 
                       }
                   },
                   {
                       data: 'updated_at',
                       name: 'updated_at',
                       className: 'text-center',
                       render: function(data) {
                           return moment(data).format('YYYY-MM-DD'); 
                       }
                   }
               ]
           });
       });    
   });
   
</script>