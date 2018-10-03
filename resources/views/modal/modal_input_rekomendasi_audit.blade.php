
<div class="modal fade" id="modal_bidang_audit" role="dialog"
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
      Form Input Rekomendasi
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form action="{{route('add_bidang_lha')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
      <div class="form-body">
          {{csrf_field()}}
          <input type="hidden" name="tipe" class="tipe"/>
          <input type="hidden" name="id_lha_parent" value="{{isset($data) ? $data->id_lha :''}}"/>
        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="judul_temuan" name="judul_temuan" type="text" class="form-control"  required>
          </div>
        </div>

        <div class="form-group">
          <label for="kode_temuan" class="col-md-3 control-label">Kode temuan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <select name="kode_temuan" id="kode_temuan" class="select2 form-control" tabindex="-1" aria-hidden="true" required>
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
            <input value="" id="periode" name="kriteria" type="text" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Sebab</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="sebab" type="text" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Akibat</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="akibat" type="text" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggapan</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input value="" id="periode" name="tanggapan" type="text" class="form-control">
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
                  <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                </div>
              </div>
              <span class="help-block">
              </span>
            </div>
          </div>

        <div class="form-group">
          <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Rekomendasi</label>
          <div class="col-md-9 col-sm-9 col-xs-12">

              <div class="mt-repeater">
                <div data-repeater-list="meta_rekomendasi">
                    <div data-repeater-item="" class="mt-repeater-item">
                        <div class="row mt-repeater-row">
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <label class="control-label">Judul Rekomendasi</label>
                                <input name="meta_rekomendasi[0][judul_rekomendasi]" type="text" class="form-control">
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Kode Rekomendasi</label>
                                <select name="meta_rekomendasi[0][kode_rekomendasi]" id="kode_rekomendasi" class="form-control" tabindex="-1" aria-hidden="true">
                                  <option>-- Pilih --</option>
                                  @foreach($kode_rekomendasi as $kd)
                                  <option value="{{$kd->kd_rekomendasi}}">{{$kd->kd_rekomendasi.' - '.$kd->nama_rekomendasi}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-md-5 col-sm-5 col-xs-12">
                                <label class="control-label">Nilai Rekomendasi</label>
                                <input name="meta_rekomendasi[0][nilai_rekomendasi]" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Audit Penanggung jawab</label>
                                <input name="meta_rekomendasi[0][penanggung_jawab]" type="text" class="form-control">
                              </div>
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


      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="submit"  class="btn btn-default"
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
