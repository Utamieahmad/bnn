@extends('layouts.base_layout')
@section('title', 'Tambah Data Monitoring dan Evaluasi')

@section('content')
    <div class="right_col mSelect withAnggaran" role="main">
        <div class="m-t-40  ">
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
                    <h2>Form Tambah Data Monitoring dan Evaluasi Direktorat Peran Serta Masyarakat</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <br />
                    <form action="{{URL('/pemberdayaan/dir_masyarakat/input_psm_supervisi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                        {{ csrf_field() }}
                        <div class="form-body">

                        <div class="form-group">
                            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                <input type='text' name="tgl_pelaksanaan" class="form-control" required/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                            <div class="col-md-6">
                                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required>
                                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                                  @foreach($instansi as $in)
                                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran</label>
                            <div class="col-xs-12 col-md-6 col-sm-6">
                                <select class="form-control select2" name="sasaran" onChange="pilih_sasaran(this)">
                                    <option value=""> -- Pilih Sasaran -- </option>
                                    @if(count($sasaran))
                                        @foreach($sasaran as $skey => $sval)
                                            <option value="{{$skey}}"> {{$sval}} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="form-group hide hasil_penilaian">
                            <label for="nama_instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Penilaian</label>
                            <div class="col-md-8">
                                <div class="mt-repeater">
                                    <div data-repeater-list="penilaian">
                                        <div data-repeater-item="" class="mt-repeater-item">
                                            <div class="row mt-repeater-row">
                                                <div class="col-md-5 col-xs-12 col-sm-5">
                                                    <label class="control-label">Nama Instansi</label>
                                                    <input name="penilaian[0][nama_instansi]" value="" type="text" class="form-control">
                                                </div>
                                                <div class="col-md-4 col-xs-12 col-sm-4">
                                                    <div class="row">
                                                        <label class="control-label">Hasil Penilaian</label>
                                                        <select class="form-control mSelect2" name="penilaian[0][hasil_penilaian]">
                                                            <option value=""> -- Pilih Penilaian --</option>
                                                            @if(isset($hasil_penilaian))
                                                                @if(count($hasil_penilaian))
                                                                    @foreach($hasil_penilaian as $hkey => $hvalue)
                                                                        <option value="{{$hkey}}"> {{$hvalue}}</option>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-12">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add" onClick="mSelect2(this)">
                                        <i class="fa fa-plus"></i> Tambah Instansi</a>
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="instansi" class="col-md-3 control-label">Instansi</label>
                            <div class="col-md-8">
                                <div class="mt-repeater">
                                    <div data-repeater-list="group-c">
                                        <div data-repeater-item="" class="mt-repeater-item">
                                            <div class="row mt-repeater-row">
                                                <div class="col-md-6  col-sm-6 col-xs-12">
                                                    <label class="control-label">Nama Instansi</label>
                                                    <input name="group-c[0][list_nama_instansi]" type="text" class="form-control"> </div>
                                                <div class="col-md-3  col-sm-3 col-xs-12">
                                                    <div class="row">
                                                        <label class="control-label">Jumlah Peserta</label>
                                                        <input name="group-c[0][list_jumlah_peserta]" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
                                                    </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                        <i class="fa fa-plus"></i> Tambah Instansi</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tim_supervisi" class="col-md-3 col-sm-3 col-xs-12 control-label">Tim Supervisi</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kodesumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                            <div class="col-md-6 col-sm-6 col-xs-12 radio">
                                <div class="mt-radio-list" id='buttons'>
                                    @if(count($sasaran))
                                        @php $i = 0;@endphp
                                        @foreach($kode_anggaran as $kkey => $kval)
                                        @php $i = $i+1; @endphp
                                            <label class="mt-radio col-md-9"> 
                                                <input type="radio" value="{{$kkey }}" name="kodesumberanggaran" id="anggaran{{$i}}">
                                                <span>{{$kval}}</span>
                                            </label>
                                        @endforeach
                                    @endif
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
                            <label for="hasil_yang_dicapai" class="col-md-3 control-label">Hasil yang dicapai</label>
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
                								<a href="{{route('psm_supervisi')}}" class="btn btn-primary" type="button">BATAL</a>
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
