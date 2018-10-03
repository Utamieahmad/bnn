<div class="modal fade" id="modal_edit_ptl" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog white">
    <div class="modal-content modal-color">
        <!-- <form action="{{route('update_rekomendasi')}}" class="form-horizontal" id="form_modal_edit_ptl" method="post" enctype="multipart/form-data" autocomplete="on" onsubmit="submitModalRekomendasi(event,this)"> -->
        <form action="{{route('update_rekomendasi')}}" class="form-horizontal" id="form_modal_edit_ptl" method="post" enctype="multipart/form-data" autocomplete="on" >
            {{csrf_field()}}
            <input type="hidden" name="id" value="" class="id_rekomendasi" />
            <input type="hidden" name="tipe" value="" class="tipe" />
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel2">Edit Rekomendasi Temuan Pemantauan Tindak Lanjut</h4>
          </div>

          <div class="modal-body">
            <div class="content">
              
              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Rekomendasi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p judul_rekomendasi" ></p>
                </div>
              </div>
            </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Kode Rekomendasi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p kode_rekomendasi"> </p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Nilai Rekomendasi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p nilai_rekomendasi"></p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Penanggung Jawad</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p penanggung_jawab"></p>
                </div>
              </div>

              

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Nilai Yang Sudah Ditindak Lanjut</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control nilai_tindak_lanjut" name="nilai_tindak_lanjut" value="" onkeydown="numeric_only(event,this)" />
                </div>
              </div>

              <div class="form-group m-t-20 modal-ptl">
                <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Bukti Tindak Lanjut</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="meta-repeater">
                        <div data-repeater-list="meta_bukti">
                            <div data-repeater-item class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <div class="fileinput fileinput-new m-b-0" data-provides="fileinput">
                                            <div class="input-group input-large m-b-0">
                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                    <i class="fa fa-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"> </span>
                                                </div>
                                                <span class="input-group-addon btn default btn-file">
                                                  <span class="fileinput-new"> Pilih Berkas </span>
                                                  <span class="fileinput-exists"> Ganti </span>
                                                  <input type="file" name="bukti"> </span>
                                                  <input type="hidden" name="filename" value="">
                                                  <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                      <div class="row">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete m-0">
                                            <i class="fa fa-close"></i>
                                        </a>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add" onClick="cleanRepeater()">
                            <i class="fa fa-plus"></i> Tambah Bukti</a>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control status_rekomendasi" name="status">
                    <option value=""> -- Pilih Status -- </option>
                    @if(count($status) > 0 )
                      @foreach($status as $s => $sval)
                        <option value="{{$s}}"> {{$sval}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="messages">

              </div>
            </div>
          
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary confirm  btn-confirm"> Kirim</button>
            </div>
          </div>

          <div class="modal-footer-loading alert">
            <p> Loading ... </p>
          </div>
      </form>
    </div>
  </div>
</div>