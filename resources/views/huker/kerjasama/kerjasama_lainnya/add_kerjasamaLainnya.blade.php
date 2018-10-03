@extends('layouts.base_layout')
@section('title', 'Tambah Data Kegiatan Kerja Sama Lainnya')

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
                    <h2>Form Tambah Data Kegiatan Kerja Sama Lainnya Direktorat Kerja Sama</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/huker/dir_kerjasama/input_kerjasama_lainnya')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
    <div class="form-body">

        <div class="form-group" style="display:none">
            <label for="pelaksana" class="col-md-3 control-label">Pelaksana</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="no_sprint" name="no_sprint" type="text" class="form-control" required >
            </div>
        </div>

        <div class="form-group">
            <label for="nm_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="nm_kegiatan" name="nm_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                <input type='text' name="tgl_pelaksanaan" class="form-control" required />
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Pelaksanaan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tempat_pelaksanaan" name="tempat_pelaksanaan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="narasumber" name="narasumber" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_peserta">
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Nama Instansi</label>
                                    <input name="meta_peserta[][list_nama_instansi]" type="text" class="form-control"> </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Alamat Instansi</label>
                                    <input name="meta_peserta[][list_alamat_instansi]" type="text" class="form-control"> </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Jumlah Peserta</label>
                                    <input name="meta_peserta[][list_jumlah_peserta]" type="number" class="form-control numeric" onkeydown="numeric(event)"> </div>
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
            <label for="sumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="mt-radio-list" id='buttons'>
                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="DIPA" name="sumberanggaran" id="anggaran1">
                    <span>Dipa</span>
                    </label>
                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="NONDIPA" name="sumberanggaran" id="anggaran2">
                    <span>Non Dipa</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group" id="PilihAnggaran" style="display:none">
            <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                    <option value="">-- Pilih Anggaran --</option>
                </select>
            </div>
        </div>

        <div class="form-group" id="DetailAnggaran" style="display:none">
            <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
            <input type="hidden" name="asatker_code" id="kodeSatker" value="">
            <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">

            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Dokumen Kegiatan</label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group input-large">
                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                            <span class="fileinput-filename"> </span>
                        </div>
                        <span class="input-group-addon btn default btn-file">
                            <span class="fileinput-new"> Pilih Berkas </span>
                            <span class="fileinput-exists"> Ganti </span>
                            <input type="file" name="dokumen_kegiatan"> </span>
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
                <button type="submit" class="btn btn-success">KIRIM</button>
				<a href="{{route('kerjasama_lainnya')}}" class="btn btn-primary" type="button">BATAL</a>
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
