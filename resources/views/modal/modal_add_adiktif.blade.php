<div class="modal fade" id="add_modalAdiktif" role="dialog"
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
      Form Tambah Barang Bukti NPS & Adiktif lainnya
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal form-label-left" id="form_modalAdiktif" action="{{URL('/pemberantasan/input_brgbukti_adiktif')}}" method="post" role="form">
      {{csrf_field()}}
      <input type="hidden" name="url" value="{{\Request::path()}}">
      <input type="hidden" name="id" value="{{$id}}">
      <input type="hidden" name="bbId" id="bbId" value="">
      <div class="form-group">
        <label class="col-md-3 control-label" for="barang_bukti_narkoba">Barang Bukti NPS & adiktif lainnya</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="jenisKasus" name="jenisKasus" style="width:100%">
            <option value="">-- Jenis Barang Bukti -- </option>
            @if(isset($jenisBrgBuktiAdiktif))
              @foreach($jenisBrgBuktiAdiktif['data'] as $keyGroup => $jenis )
                <optgroup label="{{$keyGroup}}">
                  @foreach($jenis as $key => $val)
                  <option value="{{preg_replace('/\s+/', '', $key)}}">{{$val}}</option>
                  @endforeach
                </optgroup>
              @endforeach
            @endif
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="jumlah_barang">Jumlah Barang</label>
        <div class="col-sm-9">
          <input type="number" step="0.01" class="form-control " id="jumlah_barang_bukti" name="jumlah_barang_bukti"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Satuan</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kode_satuan_barang_bukti" name="kode_satuan_barang_bukti" style="width:100%">
            @if(isset($satuan))
              @if($satuan['data'])
                @foreach($satuan['data'] as $val)
                <option value="{{ ($val['kd_satuan'] ? preg_replace('/\s+/', '', $val['kd_satuan']) : '')}}">{{$val['nm_satuan']}}</option>
                @endforeach
              @endif
            @endif
          </select>
        </div>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default"
      data-dismiss="modal">Batal
    </button>
    <button type="submit" class="btn btn-success">
      Simpan
    </button>
  </div>
</form>

</div>
</div>
</div>
