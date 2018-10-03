<!-- Modal -->
<div class="modal fade" id="modal_master_mediaruang" role="dialog"
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
      Form Input Media Luar Ruang
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalMediaruang" action="{{URL('/master/save_mediaruang')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>
      <div class="form-group">
        <label class="col-md-3 control-label">Jenis Media</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="parent_id" name="parent_id" style="width:100%">
            <option value="">-- Pilih Jenis --</option>
            @foreach($jenis as $p)
               <option value="{{$p['id']}}" > {{$p['nama_media']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Kode Media Luar Ruang</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="value_media" name="value_media"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Media Luar Ruang</label>
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
