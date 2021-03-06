@extends('layouts.base_layout')
@section('title', 'Tambah Data Kegiatan')

@section('content')
<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah2').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
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
                        <h2>Form Tambah Kegiatan Direktorat PLRIP</h2>
                        <div class="clearfix"></div>
                    </div>
                        <div class="x_content">
                        <br />
                        <form action="{{route('save_kegiatan_pelatihan_plrip')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            {{csrf_field()}}
                            <div class="form-body">

                                <div class="form-group">
                                    <label for="pelaksana" class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control select2" tabindex="-1" aria-hidden="true" required>
                                            <option value="" selected="selected"> -- Pilih Jenis Kegiatan -- </option>
                                            @if(isset($jenis_kegiatan))
                                                @if(count($jenis_kegiatan))
                                                    @foreach($jenis_kegiatan as $j=> $jval)
                                                        <option value="{{$j}}"> {{$jval}}</option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="tema" name="tema" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pelaksana" class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="id_pelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                                            <option value="" selected="selected"> Pilih Pelaksana </option>
                                            {!! dropdownPelaksana('',true) !!}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tgl_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Surat Perintah</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="tgl_sprint" name="nomor_sprint" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tgl_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Surat Perintah</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                        <input type='text' name="tgl_sprint" class="form-control" value=""/>
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tgl_dilaksanakan_mulai" class="col-md-3 col-sm-3 col-xs-12 control-label">Periode</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
                                                        <input type='text' name="tgl_dilaksanakan_mulai" class="form-control" value=""/>
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_end'>
                                                        <input type='text' name="tgl_dilaksanakan_selesai" class="form-control" value=""/>
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="tgl_dilaksanakan_selesai" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Berakhir Pelatihan</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                        <input type='text' name="tgl_dilaksanakan_selesai" class="form-control" value="" />
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div> --}}

                                <div class="form-group">
                                    <label for="tempat" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
                                    <!--<label for="tempat" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Pelatihan</label>-->
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="tempat" name="tempat" type="text" class="form-control">
                                    </div>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="tempat_kodepos" class="col-md-3 col-sm-3 col-xs-12 control-label">Kodepos Tempat Pelatihan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="tempat_kodepos" name="tempat_kodepos" type="text" class="form-control" onKeydown="numeric_only(event,this)">
                                    </div>
                                </div> --}}

                                <div class="form-group">
                                    <label for="jumlah_narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Narasumber</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_narasumber" name="jumlah_narasumber" type="text" class="form-control" onKeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control" onKeydown="numeric_only(event,this)">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="uraian_singkat" class="col-md-3 col-sm-3 col-xs-12 control-label">Uraian Singkat Materi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="uraian_singkat" name="uraian_singkat" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="file_nspk" class="col-md-3 control-label">Materi (Upload)</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="input-group input-large">
                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                    <span class="fileinput-filename"> </span>
                                                </div>
                                                <span class="input-group-addon btn default btn-file">
                                                    <span class="fileinput-new"> Pilih Berkas </span>
                                                    <span class="fileinput-exists"> Ganti </span>
                                                    <input type="file" name="file_materi"> </span>
                                                <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                            </div>
                                        </div>
                                        <span class="help-block white" >

                                        </span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">                                
                                        <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">                                
                                        <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">                                
                                        <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type='file' name="foto1" onchange="readURL(this);" />
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type='file' name="foto2" onchange="readURL2(this);" />
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type='file' name="foto3" onchange="readURL3(this);" />
                                    </div>
                                </div>
                                
                            </div>

                             <div class="form-actions fluid">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-success">SIMPAN</button>
                                        <a href="{{route('kegiatan_pelatihan_plrip')}}" class="btn btn-primary" type="button">BATAL</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
