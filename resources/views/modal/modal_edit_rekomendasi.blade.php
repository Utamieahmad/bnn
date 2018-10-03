
<div class="modal fade" id="modal_edit_bidang_audit" role="dialog"
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
      Form Edit Bidang
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form action="{{route('update_bidang_lha')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on" >
      <div class="form-body">
          {{csrf_field()}}
          <input type="hidden" name="tipe" class="tipe"/>
          <input type="hidden" name="id_lha_bidang" value="" class="id_lha_bidang"/>
        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="judul_temuan" name="judul_temuan" type="text" class="form-control judul_temuan" >
          </div>
        </div>

        <div class="form-group">
          <label for="kode_temuan" class="col-md-3 control-label">Kode temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <select name="kode_temuan" id="kode_temuan" class="form-control kode_temuan" tabindex="-1" aria-hidden="true">
              <option value="">-- Pilih --</option>
              @foreach($kode_temuan as $kd)
              <option value="{{$kd->kd_temuan}}">{{$kd->kd_temuan.' - '.$kd->nama_temuan}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Kriteria</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="kriteria" type="text" class="form-control kriteria">
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Sebab</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="sebab" type="text" class="form-control sebab">
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Akibat</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="akibat" type="text" class="form-control akibat">
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggapan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="tanggapan" type="text" class="form-control tanggapan">
          </div>
        </div>

        <div class="form-group">
          <label for="url_bukti" class="col-md-3 col-sm-3 col-xs-12 control-label">Bukti temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="input-group input-large">
                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                  <i class="fa fa-file fileinput-exists"></i>&nbsp;
                  <span class="fileinput-filename"> </span>
                </div>
                <span class="input-group-addon btn default btn-file">
                  <span class="fileinput-new"> Pilih Berkas </span>
                  <span class="fileinput-exists"> Ganti </span>
                  <input type="file" name="bukti_temuan"> </span>
                  <a href="javascript:;" class="input-group-addon btn red fileinput-exists " data-dismiss="fileinput"> Hapus </a>
                </div>
              </div>
              <span class="help-block span-link hide white">
                  Lihat File : <a  class="link_file" target="_blank" href=""></a>
              </span>
            </div>
          </div>

        <div class="form-group">
          <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Rekomendasi</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="hidden" name="id_deleted" value="" class="id_deleted"/>
              <div class="mt-repeater">
                <div data-repeater-list="meta_rekomendasi" class="penanggung_jawab">

                </div>
                <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                      <i class="fa fa-plus"></i> Tambah Rekomendasi</a>
            </div>
          </div>
        </div>

      </div>


      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button"  class="btn btn-default" data-dismiss="modal" > Batal </button>
        <button type="submit" class="btn btn-success btn-submit">Kirim </button>
      </div>
       </form>

 </div>
</div>
</div>
</div>
</div>
</div>
