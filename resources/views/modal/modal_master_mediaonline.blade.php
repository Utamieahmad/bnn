<!-- Modal -->
<div class="modal fade" id="modal_master_mediaonline" role="dialog"
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
      Form Input Media Online
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalMediaonline" action="{{URL('/master/save_mediaonline')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>

      <div class="form-group">
        <label  class="control-label col-md-3" >Kode Media Online</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="value_media" name="value_media"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Media Online</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nama_media" name="nama_media"/>
        </div>
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
