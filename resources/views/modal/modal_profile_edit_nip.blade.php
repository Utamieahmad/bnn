<!-- Modal -->
<div class="modal fade" id="modal_edit_nip" tabindex="-1" role="dialog"
aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content modal-color">
    <!-- Modal Header -->
    <div class="modal-header">
      <button type="button" class="close"
      data-dismiss="modal">
      <span aria-hidden="true" class="c-white">&times;</span>
      <span class="sr-only">Close</span>
    </button>
    <h4 class="modal-title c-white" id="myModalLabel">
      Form Tambah NIP
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" action="{{URL('/change_nip_process')}}" method="post" role="form">
      {{csrf_field()}}
      <div class="form-group">
        <label  class="control-label col-md-3" for="nip">NIP</label>
        <div class="col-sm-9">
          <input type="number" class="form-control" name="nip" id="nip" required/>
        </div>
      </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default"
      data-dismiss="modal">
      Batal
    </button>
    <button type="submit" class="btn btn-success">
      Simpan
    </button>
  </div>
</form>
</div>
</div>
</div>
</div>
