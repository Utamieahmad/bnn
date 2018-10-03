<!-- Modal -->
<div class="modal fade" id="modal_master_instansi" role="dialog"
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
      Form Input Instansi
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalInstansi" action="{{URL('/master/save_instansi')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>
      <div class="form-group">
        <label class="col-md-3 control-label">Wilayah</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="id_wilayah" name="id_wilayah" style="width:100%">
            <option value="">-- Pilih Wilayah --</option>
            @foreach($wilayah as $p)
               <option value="{{$p['id_wilayah']}}" > {{$p['nm_wilayah']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Instansi</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nm_instansi" name="nm_instansi"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Alamaat Instansi</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="alamat_inst" name="alamat_inst"/>
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
