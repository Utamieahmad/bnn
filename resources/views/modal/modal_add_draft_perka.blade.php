
<div class="modal fade" id="draft_perka" role="dialog"
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
      Form Tambah Penyusunan Draft Awal Perka Dir Hukum
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form action="{{URL('/huker/input_perka_draftawal')}}" class="form-horizontal" id="form_modalDraftPerka" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input type="hidden" name="form_method" value="create">
      <input type="hidden" name="id_perka" value="{{$id}}"> 
      <input type="hidden" name="draft_id" id="draft_id" value="">     
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
          <label  class="col-md-3 control-label" for="no_sprint">No Surat Perintah</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="no_sprint" name="no_sprint"/>
          </div>
        </div>

        <div class="form-group">
        <label  class="col-md-3 control-label" for="jml_peserta">Jumlah Peserta</label>
        <div class="col-sm-9">
          <input type="number" class="form-control" id="jml_peserta" name="jml_peserta"/>
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
                    lihat file : <a style="color:yellow" href="{{\Storage::url('HukumPerkaDraftAwal/')}}"></a>
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
