@extends('layouts.base_layout')
@section('title', 'Tambah Data Kegiatan Lainnya')

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
                    <h2>Form Tambah Data Kegiatan Lainnya Direktorat Hukum</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/huker/dir_hukum/input_hukum_lainnya')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
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
            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="mt-radio-list">
                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="Sosialisasi" name="jenis_kegiatan" required >
                    <span>Sosialisasi</span>
                    </label>

                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="Monitoring_Evaluasi" name="jenis_kegiatan">
                    <span>Monitoring dan Evaluasi</span>
                    </label>

                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="Rapat_Konsinyering" name="jenis_kegiatan">
                    <span>Rapat Konsinyering</span>
                    </label>

                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="Rapat_Antar_Kementrian" name="jenis_kegiatan">
                    <span>Rapat Antar Kementrian</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
           <label for="bagian" class="col-md-3 col-sm-3 col-xs-12 control-label">Bagian</label>
                <div class="col-md-4">
                    <div class="mt-radio-list">
                        <label class="mt-radio col-md-9"> <input type="radio" value="Penelahaan" name="bagian" required >
                            <span>Penelahaan</span>
                        </label>

                        <label class="mt-radio col-md-9"> <input type="radio" value="Perancangan" name="bagian">
                            <span>Perancangan</span>
                        </label>

                    </div>
                </div>
        </div> 

        <div class="form-group">
            <label for="sprint_kepala" class="col-md-3 col-sm-3 col-xs-12 control-label">No Surat Perintah Kepala BNN</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="sprint_kepala" name="sprint_kepala" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="permasalahan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Surat Perintah Deputi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="sprint_deputi" name="sprint_deputi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_mulai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal'>
                                <input type='text' name="tgl_mulai" class="form-control" value=""/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_selesai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date datepicker-only'>
                                <input type='text' name="tgl_selesai" class="form-control" value=""/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tempat_kegiatan" name="tempat_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="lokasi_kegiatan" id="lokasi_kegiatan" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                     <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab['data'] as $keyGroup => $jenis )
                    <optgroup label="{{$keyGroup}}">
                    @foreach($jenis as $key => $val)
                    <option value="{{$key}}">{{$val}}</option>
                    @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tema</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tema" name="tema" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="meta_narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_narasumber">
                        <div data-repeater-item="" class="mt-repeater-item">
                            <div class="mt-repeater-row">
                                <div class="row">
                                    <div class="col-md-11 col-sm-11 col-xs-12">
                                        <label class="control-label">Narasumber</label>
                                        <input name="meta_narasumber[][narasumber]" value="" type="text" class="form-control">
                                    </div> 
                                    <div class="col-md-1 col-sm-1 col-xs-12 p-0">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11 col-sm-11 col-xs-12">
                                        <label class="control-label">Materi</label>
                                        <textarea name="meta_narasumber[][materi]" class="form-control"></textarea>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Tambah Narasumber
                    </a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_peserta">
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <label class="control-label">Nama Instansi</label>
                                    <input name="meta_peserta[][nama_instansi]" type="text" class="form-control"> </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Jumlah Peserta</label>
                                    <input name="meta_peserta[][jumlah_peserta]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
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
            <label for="hasil_dicapai" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil yang dicapai</label>
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
                            <input type="file" name="hasil_dicapai"> </span>
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
				<a href="{{route('hukum_lainnya')}}" class="btn btn-primary" type="button">BATAL</a>
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
    