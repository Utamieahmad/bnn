@extends('layouts.base_layout')
@section('title', 'Tambah Data Bimbingan Teknis')

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
                    <h2>Form Tambah Data Bimbingan Teknis Narkoba Direktorat Peran Serta Masyarakat</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                     <br />
                        @if (session('status'))
                            @php
                                $session= session('status');
                            @endphp
                            <div class="alert alert-{{$session['status']}}">
                                {{ $session['message'] }}
                            </div>
                        @endif
                        <form action="{{URL('/pemberdayaan/dir_masyarakat/input_pendataan_pelatihan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            {{ csrf_field() }}
                            <div class="form-body">

                            <div class="form-group">
                                <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
                                <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                    <input type='text' name="tgl_pelaksanaan" class="form-control" required />
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
                                <div class="col-md-4 radio">
                                    <div class="mt-radio-list">
                                        @if(count($sasaran) > 0 )
                                            @foreach($sasaran as $s => $val)
                                                <label class="mt-radio col-md-9"> 
                                                    <input type="radio" value="{{$s}}" name="sasaran">
                                                    <span>{{$val}}</span>
                                                </label>
                                            @endforeach
                                        @endif
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
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <label class="control-label">Nama Instansi</label>
                                                        <input name="group-c[0][list_nama_instansi]" type="text" class="form-control"> </div>
                                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                                        <label class="control-label">Jumlah Peserta</label>
                                                        <input name="group-c[0][list_jumlah_peserta]" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
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
                                <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control select2 " name="lokasi_kegiatan_idkabkota">
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
                                <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Lokasi Kegiatan </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="panitia" class="col-md-3 col-sm-3 col-xs-12 control-label">Panitia</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
                                </div>
                            </div>


                            <div class="form-group m-t-20">
                                <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="mt-repeater">
                                        <div data-repeater-list="materi">
                                            <div data-repeater-item="" class="mt-repeater-item">
                                                <div class="row mt-repeater-row">
                                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                                        <label class="control-label">Narasumber</label>
                                                        <input name="group-materi[0][narasumber]" value="" type="text" class="form-control" > </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                                        <div class="row">
                                                            <label class="control-label">Judul Materi</label>
                                                            <textarea name="group-materi[0][materi]" class="form-control"> </textarea>
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
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                            <i class="fa fa-plus"></i> Tambah Materi</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sasakodesumberanggaranran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                                <div class="col-md-4">
                                    <div class="radio"> 
                                        @if(count($kode_anggaran))
                                            @php $i = 0;@endphp
                                            @foreach($kode_anggaran as $k => $kval)
                                                @php $i = $i+1; @endphp
                                                <label class="mt-radio col-md-9"> 
                                                    <input type="radio" value="{{$k}}" name="kodesumberanggaran" id="anggaran{{$i}}">  
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
                    								<a href="{{route('pendataan_pelatihan')}}" class="btn btn-primary" type="button">BATAL</a>
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
