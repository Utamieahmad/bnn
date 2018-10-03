<!-- Modal -->
<div class="modal fade" id="modal_master_kota" role="dialog"
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
      Form Input Kota
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalKota" action="{{URL('/master/save_kota')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>
      <div class="form-group">
        <label class="col-md-3 control-label">Provinsi</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="wil_id_wilayah" name="wil_id_wilayah" style="width:100%">
            <option value="">-- Pilih Provinsi --</option>
            @foreach($propinsi as $p)
               <option value="{{$p->id_wilayah}}" > {{$p->nm_wilayah}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Jenis</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kd_jnswilayah" name="kd_jnswilayah" style="width:100%">
            <option value="">-- Pilih Jenis --</option>
            <option value="6">Kota</option>
            <option value="2">Kabupaten</option>
            <option value="5">Wilayah Khusus</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Kota</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nm_wilayah" name="nm_wilayah"/>
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
