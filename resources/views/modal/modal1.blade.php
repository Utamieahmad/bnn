
<div class="modal fade" id="modal1" role="dialog"
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
      Form Edit Rekomendasi
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form action="" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
      <input type="hidden" name="form_method" value="create">
      <div class="form-body">

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="Pembayaran honorarium dan/atau biaya perjalanan dinas ganda dan/atau melebihi standar yang ditetapkan" id="periode" name="periode" type="text" class="form-control" >
          </div>
        </div>

        <div class="form-group">
          <label for="kode_satker" class="col-md-3 control-label">Kode temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <select name="kode_satker" id="kode_satker"  class="select2 form-control" tabindex="-1" aria-hidden="true" >
              <option>-- Pilih --</option>
              <option value="puslitdatin" selected>1</option>
              <option value="sekretariat_utama">5</option>
              <option value="biro_umum">9</option>
              <option value="sarana">11</option>
              <option value="sarana">12</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Kriteria</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="Temuan Ketidakpatuhan Terhadap Peraturan" id="periode" name="periode" type="text" class="form-control" maxlength="6" >
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Sebab</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="Pegawai tidak memperhatikan apa yang dibutuhkan" id="periode" name="periode" type="text" class="form-control" maxlength="6" >
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Akibat</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="Potongan pada biaya perjalanan dinas selanjutnya" id="periode" name="periode" type="text" class="form-control" maxlength="6" >
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggapan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="Agar pegawai dapat memanfaatkan dana sesuai standar yang ditetapkan" id="periode" name="periode" type="text" class="form-control" maxlength="6" >
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
                  <input type="file" name="file_upload"> </span>
                  <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                </div>
              </div>
              <span class="help-block" style="color:white">
                Lihat file : <a style="color:yellow" >IrtamaLHA-01/11/2017.pdf</a>
              </span>
            </div>
          </div>

        <div class="form-group">
          <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Rekomendasi</label>
          <div class="col-md-9 col-sm-9 col-xs-12">                                                        
              <div class="mt-repeater">
                  <div data-repeater-list="meta_instansi">
                      <div data-repeater-item="" class="mt-repeater-item">
                          <div class="row mt-repeater-row">
                              <div class="col-md-5 col-sm-5 col-xs-12">  
                                  <label class="control-label">Judul Rekomendasi</label>
                                  <input name="meta_instansi[][list_nama_instansi]" type="text" value="Pelaksanaan sanksi administrasi kepegawaian " class="form-control"> </div><div class="col-md-6 col-sm-6 col-xs-12">  
                                  <label class="control-label">Kode Rekomendasi</label>
                                  <input name="meta_instansi[][list_nama_instansi]" type="text" value="05" class="form-control"> </div><div class="col-md-5 col-sm-5 col-xs-12">  
                                  <label class="control-label">Nilai Rekomendasi</label>
                                  <input name="meta_instansi[][list_nama_instansi]" type="text" value="05" class="form-control"> </div><div class="col-md-6 col-sm-6 col-xs-12">  
                                  <label class="control-label">Audit Penanggung jawab</label>
                                  <input name="meta_instansi[][list_nama_instansi]" type="text" value="Audit Pusat" class="form-control"> </div>
                              <div class="col-md-1 col-sm-1 col-xs-12">
                                  <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                      <i class="fa fa-close"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                      <i class="fa fa-plus"></i> Tambah Rekomendasi</a>
              </div>
          </div>
        </div>

      </div>
      </form>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" onclick="submitForm(form)" class="btn btn-default"
        data-dismiss="modal">
        Batal
      </button>
      <button type="submit" class="btn btn-success">
        Kirim
      </button>
    </div>
  </form>
  
</div>
</div>
</div>
</div>
</div>
