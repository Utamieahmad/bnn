<!-- Modal -->
<div class="modal fade" id="modal_input_nihil" tabindex="-1" role="dialog"
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
      Form Input Nihil
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" action="{{URL('/input_nihil')}}" method="post" role="form">
      {{csrf_field()}}
      <div class="form-group">
        <label  class="control-label col-md-3" for="tanggal">Tanggal</label>
        <div class="col-sm-9">
          <input type="text" class="form-control tanggal" name="tanggal" id="tanggal" readonly/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Pelaksana</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" name="idpelaksana_nihil" style="width:100%">
            @foreach($instansi as $in)
            <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="UR">URL/Module</label>
        <div class="col-sm-9">
          <input name="uri_module" value="{{$path}}" type="text" class="form-control" id="uri_module" readonly/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="keterangan">Keterangan</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="keterangan" name="keterangan_nihil"/>
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
