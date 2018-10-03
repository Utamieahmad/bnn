<!-- Modal -->
<div class="modal fade" id="add_modalAsetrekening" role="dialog"
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
      Form Tambah Aset Rekening
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" method="POST" id="form_modalAsetrekening" action="{{URL('/pemberantasan/input_brgbukti_aset')}}" role="form">
      {{csrf_field()}}
      <input type="hidden" name="id" value="{{$id}}">
      <input type="hidden" name="url" value="{{\Request::path()}}">
      <input type="hidden" name="kode_isaset" value="ASET">
      <input type="hidden" name="kode_jenisaset" value="ASET_REKENING">
      <input type="hidden" name="kode_kelompokaset" value="KEL_ASET_UANG">
      <div class="form-group">
        <label  class="control-label col-md-3" for="nama_aset">Nama Aset</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nama_aset" name="nama_aset"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="jumlah_barang">Jumlah</label>
        <div class="col-sm-9">
          <input type="text" class="form-control " id="jumlah_barang_bukti_aset" name="jumlah_barang_bukti_aset"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Satuan</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="kode_satuan_barang_bukti_aset" name="kode_satuan_barang_bukti_aset" style="width:100%">
            @foreach($satuan['data'] as $val)
            <option value="{{$val['kd_satuan']}}">{{$val['nm_satuan']}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="konversi">Konversi Rupiah</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nilai_aset" name="nilai_aset" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)"/>
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
