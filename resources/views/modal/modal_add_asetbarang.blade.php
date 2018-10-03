<!-- Modal -->
<div class="modal fade" id="add_modalAsetbarang" role="dialog"
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
      Form Tambah Aset Barang Bukti
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" method="POST" id="form_modalAsetbarang" action="{{URL('/pemberantasan/input_brgbukti_aset')}}" role="form">
      {{csrf_field()}}
      <input type="hidden" name="id" value="{{$id}}">
      <input type="hidden" name="url" value="{{\Request::path()}}">
      <input type="hidden" name="kode_isaset" value="ASET">
      <input type="hidden" id="kode_jenisaset" name="kode_jenisaset" value="ASET_BARANG">
      <input type="hidden" id="kode_kelompokaset" name="kode_kelompokaset" value="KEL_ASET_BERGERAK">
      <input type="hidden" name="AsetId" id="AsetId" value="">

      <div class="form-group">
        <label class="col-md-3 control-label">Jenis Aset</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="jenis_aset" name="jenis_aset" style="width:100%">
            <option value="ASET_BARANG">ASET BARANG</option>
            <option value="ASET_TANAH">ASET TANAH</option>
            <option value="ASET_BANGUNAN">ASET BANGUNAN</option>
            <option value="ASET_LOGAMMULIA">ASET LOGAM MULIA</option>
            <option value="ASET_UANGTUNAI">ASET UANG TUNAI</option>
            <option value="ASET_REKENING">ASET REKENING</option>
            <option value="ASET_SURATBERHARGA">ASET SURAT BERHARGA</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="nama_aset">Nama Aset</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nama_aset" name="nama_aset"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="jumlah_barang">Jumlah</label>
        <div class="col-sm-9">
          <input type="text" class="form-control " id="jumlah_barang_bukti_aset" name="jumlah_barang_bukti_aset" onKeydown="numeric_only(event,this)"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Satuan</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kode_satuan_barang_bukti_aset" name="kode_satuan_barang_bukti_aset" style="width:100%">
            @foreach($satuan['data'] as $val)
            <option value="{{str_replace(' ', '', $val['kd_satuan'])}}">{{$val['nm_satuan']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="konversi">Konversi Rupiah</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nilai_aset" name="nilai_aset" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)" onKeydown="numeric_only(event,this)"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="keterangan">Keterangan</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="keterangan" name="keterangan"/>
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
