<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Siswa</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="{{ route('siswa.store') }}" method="POST"> 
                    @csrf
                    <input type="hidden" name="mode" value="UPDATE">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="nama">Nama </label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="number" name="nis" id="edit_nis" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat </label>
                        <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>