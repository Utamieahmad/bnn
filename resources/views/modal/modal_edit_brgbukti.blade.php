<!-- Modal -->
<div class="modal fade" id="modal_edit_brgbukti" role="dialog"
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
      Form Edit Pemusnahan Barang Bukti
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form action="{{url('/pemberantasan/dir_wastahti/input_detail_pendataan_brgbukti')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" >
      {{ csrf_field() }}
      <input type="hidden" name="parent_id" value="{{$pemusnahan['id']}}">
      <input type="hidden" id="id" name="id" value="">
      <input type="hidden" id="id_brgbukti" name="id_brgbukt" value="">
      <div class="form-group">
        <label class="control-label col-md-3" >Nama Barang Bukti</label>
        <div class="col-sm-9">
          <input name="nm_brgbukti" value="" type="text" class="form-control" id="nm_brgbukti" readonly />
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="jumlah_barang_bukti">Jumlah Barang Bukti</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="jumlah_barang_bukti" name="jumlah_barang_bukti" readonly/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3" >Satuan</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nm_satuan" name="nm_satuan" readonly/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="keperluan_lab">Keperluan Lab</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="keperluan_lab" name="keperluan_lab"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="keperluan_diklat">Keperluan Diklat</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="keperluan_diklat" name="keperluan_diklat"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="keperluan_iptek">Keperluan Iptek</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="keperluan_iptek" name="keperluan_iptek"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3" for="jumlah_dimusnahkan">Jumlah Dimusnahkan</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="jumlah_dimusnahkan" name="jumlah_dimusnahkan"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Tanggal Pemusnahan</label>
        <div class='col-md-9 col-sm-9 col-xs-12 input-group date tanggal'>
          <input type='text' class="form-control" id="tgl_pemusnahan" name="tgl_pemusnahan"/>
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3" for="jumlah_dimusnahkan">Lokasi Pemusnahan</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="lokasi" name="lokasi"/>
        </div>
      </div>
      <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
          <div class="col-md-3 col-sm-3 col-xs-12">
              <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
              <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
              <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
          <div class="col-md-3 col-sm-3 col-xs-12">
              <input type='file' name="foto1" onchange="readURL(this);" />
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
              <input type='file' name="foto2" onchange="readURL2(this);" />
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
              <input type='file' name="foto3" onchange="readURL3(this);" />
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
