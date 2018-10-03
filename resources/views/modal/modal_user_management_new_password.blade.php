<!-- Modal -->
<div class="modal fade" id="modal_new_password" tabindex="-1" role="dialog"
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
      Form Ubah Password
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form method="POST" action="#" class="form-horizontal" role="form">
      {{ csrf_field() }}


      <div class="message-validation alert alert-error">

      </div>
      <div class="message-validation-success alert alert-success">

      </div>
      <div class="loader-wrap">
          <div class="loader"></div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Password Baru</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="password" name="password" value="" class="form-control col-md-7 col-xs-12">
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Konfirmasi Password</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="password" name="password_confirmation" value="" class="form-control col-md-7 col-xs-12">
          </div>
      </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <input type="hidden" name="id" value=""/>
      <button type="button" class="btn btn-default"
      data-dismiss="modal" onClick="cancel_save_password(event,this)">
      Batal
    </button>
    <button type="submit" class="btn btn-success" onClick="save_password(event,this)">
      Simpan
    </button>
  </div>
</form>
</div>
</div>
</div>
</div>
