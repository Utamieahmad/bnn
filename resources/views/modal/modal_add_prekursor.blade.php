<!-- Modal -->
<div class="modal fade" id="add_modalPrekursor" role="dialog"
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
      Form Tambah Barang Bukti Prekursor
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" id="form_modalPrekursor" role="form" action="{{URL('/pemberantasan/input_brgbukti_prekursor')}}" method="post">
      {{csrf_field()}}
      <input type="hidden" name="url" value="{{\Request::path()}}">
      <input type="hidden" name="id" value="{{$id}}">
      <input type="hidden" name="bbId" id="bbId" value="">
      <div class="form-group">
        <label class="col-md-3 control-label" for="barang_bukti_narkoba">Barang Bukti Prekursor</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="jenisKasus" name="jenisKasus" style="width:100%">
            <option value="">-- Jenis Barang Bukti -- </option>
            @foreach($jenisBrgBuktiPrekursor['data'] as $keyGroup => $jenis )
            <optgroup label="{{$keyGroup}}">
              @foreach($jenis as $key => $val)
              <option value="{{$key}}">{{$val}}</option>
              @endforeach
            </optgroup>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="jumlah_barang">Jumlah Barang Bukti</label>
        <div class="col-sm-9">
          <input type="number" step="0.01" class="form-control " id="jumlah_barang_bukti" name="jumlah_barang_bukti"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Satuan</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2 selectKabupaten" id="kode_satuan_barang_bukti" name="kode_satuan_barang_bukti" style="width:100%">
            @foreach($satuan['data'] as $val)
            <option value="{{preg_replace('/\s+/', '', $val['kd_satuan'])}}">{{$val['nm_satuan']}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default"
      data-dismiss="modal">
      Tutup
    </button>
    <button type="submit" class="btn btn-success">
      Kirim
    </button>
  </div>

</form>
</div>
</div>
</div>
