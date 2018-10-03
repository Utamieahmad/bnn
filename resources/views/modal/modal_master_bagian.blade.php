<!-- Modal -->
<div class="modal fade" id="modal_master_bagian" role="dialog"
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
      Form Input Data Bagian
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalBagian" action="{{URL('/master/save_bagian')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>
      <div class="form-group">
        <label class="col-md-3 control-label">Biro</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="id_lookup_parent" name="id_lookup_parent" style="width:100%">
            <option value="">-- Pilih Biro --</option>
            @foreach($biro as $p)
               <option value="{{$p['id_settama_lookup']}}" > {{$p['lookup_name']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Bagian</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="lookup_name" name="lookup_name"/>
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
