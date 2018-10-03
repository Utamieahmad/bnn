@extends('layouts.base_layout')
@section('title', 'Tambah Data SOP & Kebijakan')

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
          <h2>Form Tambah Data SOP &amp; Kebijakan Inspektorat Utama</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form action="{{url('/irtama/sop/input_irtama_sop')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="sprin" name="sprin" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Surat Perintah</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tgl_sprin" class="form-control" required/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="lokasi_kegiatan_idkabkota" class="col-md-3 control-label">Nama Kebijakan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="nama_sop_kebijakan" name="nama_sop_kebijakan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="lokasi_kegiatan_idkabkota" class="col-md-3 control-label">Jenis Kebijakan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="jenis_sop_kebijakan" id="jenis_sop_kebijakan" class="select2 form-control" tabindex="-1" aria-hidden="true">
                    <option value="">-- Pilih --</option>
                    <option value="SOP">SOP</option>
                    <option value="Pedoman">Pedoman</option>
                    <option value="Surat Edaran">Surat Edaran</option>
                    <option value="Dll">Dll</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kebijakan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tgl_sop" class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="file_hasil_pemeriksaan" class="col-md-3 control-label">Unggah Dokumen</label>
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
                        <input type="file" name="file_upload"> </span>
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
                    <a href="{{route('irtama_sop')}}" class="btn btn-primary" type="button">BATAL</a>
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
