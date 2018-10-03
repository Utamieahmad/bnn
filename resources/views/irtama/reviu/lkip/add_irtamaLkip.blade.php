@extends('layouts.base_layout')
@section('title', 'Tambah Data Reviu Laporan Kinerja Instansi Pemerintah')

@section('content')
    <div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
                <div class="">
                    {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
                </div>
            </div>
            <div class="clearfix"></div>


    <div class="title_right">
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Tambah Data Reviu Laporan Kinerja Instansi Pemerintah Inspektorat Utama</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form action="{{url('/irtama/reviu/input_irtama_lkip')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="no_sprint" name="no_sprint" type="text" class="form-control" >
                </div>
              </div>

               <div class="form-group">
                <label for="thn_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date year-only'>
                  <input type='text' name="tahun_anggaran" class="form-control col-md-7 col-xs-12" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="x_title">
                <h2>Tim</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="pengendali_teknis" name="pengendali_teknis" required>
										<option value="">-- Pilih Pengendali Teknis --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}">{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              <div class="form-group">
                <label for="sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="ketua_tim" name="ketua_tim" required>
										<option value="">-- Pilih Ketua Tim --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}">{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              <div class="form-group">
                <label for="sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Pereviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="pereviu" name="pereviu" required>
										<option value="">-- Pilih Pereviu --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}">{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              {{-- <div class="form-group">
                <label for="sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="sprin" name="sprin" type="text" class="form-control" >
                </div>
              </div> --}}

              <div class="x_title">
                <h2>Data Umum LKIP</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran Strategis</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="sasaran" name="sasaran" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                  <label for="meta_indikator" class="col-md-3 control-label">&nbsp;</label>
                  <div class="col-md-6">
                      <div class="mt-repeater">
                          <div data-repeater-list="meta_indikator">
                              <div data-repeater-item class="mt-repeater-item">
                                  <div class="row mt-repeater-row">
                                      <div class="col-md-5">
                                          <label class="control-label"> Indikator Kinerja Utama</label>
                                          <input name="meta_indikator[][indikator]" type="text" class="form-control"> </div>
                                      <div class="col-md-5">
                                          <label class="control-label">Target</label>
                                          <input name="meta_indikator[][target]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      </div>

                                      <div class="row mt-repeater-row">
                                      <div class="col-md-5">
                                          <label class="control-label">Realisasi</label>
                                          <input name="meta_indikator[][realisasi]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-5">
                                          <label class="control-label">Capaian (%)</label>
                                          <input name="meta_indikator[][capaian]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-1">
                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                              <i class="fa fa-close"></i>
                                          </a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                              <i class="fa fa-plus"></i> Tambah </a>
                      </div>
                  </div>
              </div>

              <div class="form-group">
                <label for="file_pemeriksaan" class="col-md-3 control-label">Checklist Reviu LKIP</label>
                <div class="col-md-5">
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group input-large">
                      <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                        <span class="fileinput-filename"> </span>
                      </div>
                      <span class="input-group-addon btn default btn-file">
                        <span class="fileinput-new"> Pilih Berkas </span>
                        <span class="fileinput-exists"> Ganti </span>
                        <input type="file" name="file_upload1"> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                      </div>
                    </div>
                    <span class="help-block">
                    </span>
                  </div>
                </div>

              <div class="form-group">
                <label for="nomor_lap" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="nomor_lap" name="nomor_lap" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tanggal_lap" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tanggal_lap" class="form-control col-md-7 col-xs-12 datetimepicker" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="file_pemeriksaan" class="col-md-3 control-label">Laporan Reviu LKIP</label>
                <div class="col-md-5">
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group input-large">
                      <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                        <span class="fileinput-filename"> </span>
                      </div>
                      <span class="input-group-addon btn default btn-file">
                        <span class="fileinput-new"> Pilih Berkas </span>
                        <span class="fileinput-exists"> Ganti </span>
                        <input type="file" name="file_upload2"> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                      </div>
                    </div>
                    <span class="help-block">
                    </span>
                  </div>
                </div>

              </div>

              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
                    <a href="{{route('irtama_lkip')}}" class="btn btn-primary" type="button">BATAL</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
