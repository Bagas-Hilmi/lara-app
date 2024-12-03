<!-- Modal untuk PO Commitment -->
<div class="modal fade" id="viewPocommitmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="editFormLabel" style="color: white;">Data PO Commitment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive p-0">
                    <table class="table" id="pocommitment-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                        <thead style="background-color: #3cb210; color: white;">
                            <tr>
                                <th class="text-center">Purchasing Doc</th>
                                <th class="text-center">Reference Item</th>
                                <th class="text-center">Doc Date</th>
                                <th class="text-center">Fiscal Year</th>
                                <th class="text-center">Material No</th>
                                <th class="text-center">Material Desc</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">UOM</th>
                                <th class="text-center">Value Trancurr</th>
                                <th class="text-center">TCurr</th>
                                <th class="text-center">Value in Obj</th>
                                <th class="text-center">Cost Element</th>
                                <th class="text-center">WBS</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
     /* Gaya untuk sel tabel */
     #pocommitment-table tbody td {
        padding: 8px; /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #pocommitment-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
    }
    #pocommitment-table th, #pocommitment-table td {
        padding: 8px;
        text-align: center;
    }
</style>