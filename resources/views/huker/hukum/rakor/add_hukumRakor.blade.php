@extends('layouts.base_layout')
@section('title', 'Dir Hukum : Tambah Data Kegiatan Rakor dan Audiensi')

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
                    <h2>Form Tambah Data Kegiatan Rakor dan Audiensi Direktorat Hukum</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/huker/dir_hukum/input_hukum_rakor')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}

        <div class="form-body">

        <div class="form-group">
            <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="idpelaksana" id="idpelaksana" class="select2 form-control">
                    <option value="">--List Pelaksana --</option>
                    @foreach($instansi as $i)
                    <option value="{{$i['id_instansi']}}">{{$i['nm_instansi']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
            <div class="col-md-4">
                <div class="mt-radio-list">

                    <label class="mt-radio col-md-9"> <input type="radio" value="Rakor" name="jenis_kegiatan">
                    <span>Rakor</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" value="Audiensi" name="jenis_kegiatan">
                    <span>Audiensi</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="nomor_sprint" name="nomor_sprint" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                <input type='text' name="tgl_pelaksanaan" class="form-control" />
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Tema Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="materi" name="materi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="narasumber" name="narasumber" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="instansi" class="col-md-3 control-label">Peserta</label>
            <div class="col-md-8">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_instansi">
                        <div data-repeater-item="" class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-4">
                                    <label class="control-label">Nama Instansi</label>
                                    <input name="meta_instansi[][list_nama_instansi]" type="text" class="form-control"> </div>
                                <div class="col-md-5">
                                    <label class="control-label">Jumlah Peserta</label>
                                    <input name="meta_instansi[][list_jumlah_peserta]" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-1">
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
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan_kodepos" class="col-md-3 col-sm-3 col-xs-12 control-label">Kodepos Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="lokasi_kegiatan_kodepos" name="lokasi_kegiatan_kodepos" type="text" class="form-control numeric" onkeydown="numeric(event)">
            </div>
        </div>

        <div class="form-group">
            <label for="file_upload" class="col-md-3 control-label">Hasil yang dicapai</label>
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
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('hukum_rakor')}}" class="btn btn-primary" type="button">BATAL</a>
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
