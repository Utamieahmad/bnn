@extends('layouts.base_layout')
@section('title', 'Tambah Data Tes Narkoba')

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
                    <h2>Form Tambah Data Tes Narkoba Direktorat Peran Serta Masyarakat</h2>
                    <div class="clearfix"></div>
                </div>
                    <div class="x_content">
                        <br />
                        <form action="{{URL('/pemberdayaan/dir_masyarakat/input_pendataan_tes_narkoba')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            {{-- <input type="hidden" name="form_method" value="create"> --}}
                            {{ csrf_field() }}
                            <div class="form-body">

                            <div class="form-group">
                                <label for="no_surat_permohonan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Surat Permohonan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="no_surat_permohonan" name="no_surat_permohonan" type="text" class="form-control" required>
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
                                <label for="tgl_tes" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Tes</label>
                                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                    <input type='text' name="tgl_tes" class="form-control" required />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control select2 " name="sasaran">
                                        <option value="">-- Pilih Sasaran --</option>
                                        @if(isset($sasaran))
                                            @if(count($sasaran)>0)
                                                @foreach($sasaran as $sa =>$sval)
                                                    <option value="{{$sa}}" >{{$sval}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jumlah_peserta" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
                                </div>
                            </div>
                                
                            <div class="form-group">
                                <label for="jmlh_positif" class="col-md-3 col-sm-3 col-xs-12 control-label">Total Yang Terindikasi Positif</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="jmlh_positif" name="jmlh_positif" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kodesumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="mt-radio-list" id='buttons'>
                                        <label class="mt-radio col-md-9"> <input type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                                        <span>Dipa</span>
                                        </label>
                                        <label class="mt-radio col-md-9"> <input type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                                        <span>Non Dipa</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-group">
                                <label for="lokasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Tes</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="lokasi" name="lokasi" type="text" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                                
                            <div class="form-group">
                                <label for="keterangan_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan Lain</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="keterangan_lainnya" name="keterangan_lainnya" type="text" class="form-control col-md-7 col-xs-12">
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


                            </div>

                             <div class="form-actions fluid">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-success">SIMPAN</button>
                        								<a href="{{route('pendataan_tes_narkoba')}}" class="btn btn-primary" type="button">BATAL</a>
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
