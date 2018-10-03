<!-- Modal -->
<div class="modal fade" id="modal_master_barangbukti" role="dialog"
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
      Form Input Barang Bukti
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalBarangbukti" action="{{URL('/master/save_barangbukti')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" id="id" name="id"/>
      <div class="form-group">
        <label class="col-md-3 control-label">Jenis Kasus</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kd_jnskasus" name="kd_jnskasus" style="width:100%">
            <option value="">-- Pilih Jenis Kasus --</option>
            @foreach($jnskasus as $k)
               <option value="{{$k['id']}}" > {{$k['nm_jnskasus']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Jenis Barang Bukti</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kd_jnsbrgbukti" name="kd_jnsbrgbukti" style="width:100%">
            <option value="">-- Pilih Jenis Barang Bukti --</option>
            @foreach($jnsbarbuk as $b)
               <option value="{{$b['kd_jnsbrgbukti']}}" > {{$b['nm_jnsbrgbukti']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama Barang Bukti</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nm_brgbukti" name="nm_brgbukti"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Satuan</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kd_satuan" name="kd_satuan" style="width:100%">
            <option value="">-- Pilih Satuan --</option>
            @foreach($satuan as $s)
               <option value="{{$s['kd_satuan']}}" > {{$s['nm_satuan']}}</option>
            @endforeach
          </select>
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
