<!-- Modal -->
<div class="modal fade" id="modal_master_kegiatan" role="dialog"
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
      Form Input Data Kegiatan
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalKegiatan" action="{{URL('/master/save_kegiatan')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Kegiatan</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="lookup_name" name="lookup_name"/>
        </div>
      </div>

      <div class="form-group">
        <label for="meta_media"  class="control-label col-md-3" >Biro</label>
        <div class="col-sm-9" style="color:white">
          @foreach($biro as $p)
            <input type="checkbox" value="{{$p['id_settama_lookup']}}" id="id_parent" name="id_parent[]">
            <span>{{$p['lookup_name']}}</span><br>
          @endforeach
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
