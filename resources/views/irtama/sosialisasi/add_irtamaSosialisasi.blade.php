@extends('layouts.base_layout')
@section('title', 'Tambah Data Sosialisasi')

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
          <h2>Form Tambah Data Sosialisasi Inspektorat Utama</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form action="{{url('/irtama/sosialisasi/input_irtama_sosialisasi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="no_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="sprin" name="sprin" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="no_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="lokasi" name="lokasi" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="lokasi_kegiatan_idkabkota" class="col-md-3 control-label">Satker</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="kode_satker" id="kode_satker" class="select2 form-control" tabindex="-1" aria-hidden="true" onChange="satker_code(this)">
                    <option value="">-- Pilih Satker --</option>
                    @if(count($satker) > 0 )
                      @foreach($satker as $s => $sval)
                        <option value="{{$sval->nama}}" data-id="{{$sval->id}}">{{$sval->nama}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <input type="hidden" name="list_satker" class="list_satker"/>
              </div>

              <div class="form-group">
                <label for="no_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="no_laporan" name="no_laporan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tgl_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                  <input type='text' name="tgl_laporan" class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="no_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="no_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pemapar</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="pemapar" name="pemapar" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="file_pemeriksaan" class="col-md-3 control-label">Unggah Bukti</label>
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
                    <a href="{{route('irtama_sosialisasi')}}" class="btn btn-primary" type="button">BATAL</a>
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
