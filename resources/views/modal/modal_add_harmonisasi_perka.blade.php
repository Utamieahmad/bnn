
<div class="modal fade" id="harmonisasi_perka" role="dialog"
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
      Form Tambah Harmonisasi Perka Dir Hukum
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form action="{{URL('/huker/input_perka_harmonisasi')}}" class="form-horizontal" id="form_modalHarmonisasiPerka" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input type="hidden" name="form_method" value="create">
      <input type="hidden" name="id_perka" value="{{$id}}"> 
      <input type="hidden" name="harmonisasi_id" id="harmonisasi_id" value=""> 
      <div class="form-body">

        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal</label>
          <div class='col-md-9 col-sm-9 col-xs-12 input-group date tanggal'>
            <input type='text' class="form-control" id="tanggal" name="tanggal" required/>
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
        </div>

        <div class="form-group">
          <label  class="col-md-3 control-label" for="no_sprint_peserta">No Surat Perintah Peserta</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="no_sprint_peserta" name="no_sprint_peserta"/>
          </div>
        </div>

        <div class="form-group">
          <label  class="col-md-3 control-label" for="no_sgas_peserta">No. Surat Tugas Peserta</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="no_sgas_peserta" name="no_sgas_peserta"/>
          </div>
        </div>

        <div class="form-group">
          <label  class="col-md-3 control-label" for="no_sprint_nasum">Sprint/Sgas Narasumber</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="no_sprint_nasum" name="no_sprint_nasum"/>
          </div>
        </div>

      <div class="form-group">
          <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
          <div class="col-md-9 col-sm-9 col-xs-12">                                                        
              <div class="mt-repeater" id="narasumber_repeater">
                  <div data-repeater-list="meta_narsum_materi" class="mt-repeater-before-item">
                      <div data-repeater-item="" class="mt-repeater-item">
                          <div class="row mt-repeater-row">
                              <div class="col-md-5 col-sm-5 col-xs-12">  
                                  <label class="control-label">Narasumber</label>
                                  <input name="meta_narsum_materi[][narasumber_harmonisasi]" type="text"  class="form-control"> </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">  
                                  <label class="control-label">Materi</label>
                                  <input name="meta_narsum_materi[][materi_harmonisasi]" type="text" class="form-control"> </div>
                              <div class="col-md-1 col-sm-1 col-xs-12">
                                  <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                      <i class="fa fa-close"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                      <i class="fa fa-plus"></i> Tambah Narasumber</a>
              </div>
          </div>
        </div>

        <div class="form-group">
          <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
          <div class="col-md-9 col-sm-9 col-xs-12">                                                        
              <div class="mt-repeater" id="peserta_repeater">
                  <div data-repeater-list="meta_peserta" class="mt-repeater-before-item">
                      <div data-repeater-item="" class="mt-repeater-item">
                          <div class="row mt-repeater-row">
                              <div class="col-md-5 col-sm-5 col-xs-12">  
                                  <label class="control-label">Nama Instansi</label>
                                  <input name="meta_peserta[][nama_harmonisasi]" type="text"  class="form-control"> </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">  
                                  <label class="control-label">Jumlah Peserta</label>
                                  <input name="meta_peserta[][jumlah_harmonisasi]" type="text" class="form-control"> </div>
                              <div class="col-md-1 col-sm-1 col-xs-12">
                                  <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                      <i class="fa fa-close"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                      <i class="fa fa-plus"></i> Tambah Peserta</a>
              </div>
          </div>
        </div>

        <div class="form-group">
          <label for="hasil_dicapai" class="col-md-3 col-sm-3 col-xs-12 control-label">Laporan</label>
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
                  <input type="file" name="hasil_dicapai"> </span>
                  <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                </div>
              </div>
                <span class="help-block" style="color:white; display:none;" id="laporan">
                    lihat file : <a style="color:yellow" href="{{\Storage::url('HukumPerkaHarmonisasi/')}}"></a>
                </span>
            </div>
          </div>

      </div>

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
