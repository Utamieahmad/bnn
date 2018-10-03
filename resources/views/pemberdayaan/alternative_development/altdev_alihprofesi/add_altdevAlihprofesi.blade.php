@extends('layouts.base_layout')
@section('title', 'Tambah Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika')
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
                    <h2>Form Tambah Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika Direktorat Alternative Development</h2>
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
                        <form action="{{route('save_altdev_alih_profesi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            {{csrf_field()}}
                            <div class="form-body">
                            <div class="form-group">
                                <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                <div class="col-md-6 col-xs-12 col-sm-6">
                                    <select name="id_instansi" id="id_instansi" class="form-control select2" tabindex="-1" aria-hidden="true" required>
                                        <option value="" {{(isset($data->id_instansi) ? "" : 'selected=selected')}}> Pilih Pelaksana </option>
                                        {!! dropdownPelaksana() !!}
                                    </select>
                                </div>
                            </div>

                             <div class="form-group">
                                <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Penyelenggara</label>
                                <div class="col-md-6 col-xs-12 col-sm-6">
                                    <div class="radio">
                                        @if($penyelenggara)
                                            @foreach($penyelenggara as $pkey=>$pvalue)
                                                <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="kodepenyelenggara">
                                                <span>{{$pvalue}} </span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kawasan Rawan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="nama_kegiatan" name="nama_kegiatan" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan </label>
                                <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                    <input type='text' name="tgl_kegiatan" value="" class="form-control" required/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="lokasi_idkabkota" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi/Tempat Kegiatan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12"> 
                                    <select name="lokasi_idkabkota" id="lokasi_idkabkota" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                                        <option value="" {{(isset($data->lokasi_idkabkota) ? "" : 'selected=selected')}}> Pilih Kabupaten/Kota </option>
                                        {!! dropdownLokasiKabupaten() !!}
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelatihan Yang Diikuti</label>
                            <div class='col-md-6 col-sm-6 col-xs-12'>
                                <div class="checkbox">
                                    @if(isset($profesi_pelatihan))
                                        @foreach($profesi_pelatihan as $mkey=>$mvalue)
                                            <label class="mt-checkbox col-md-9"> <input type="checkbox" value="{{$mkey}}" name="kode_pelatihan[]" id="">
                                            <span>{{$mvalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                    <input type="text" name="pelatihan_lain" placeholder="Pelatihan Lainnya" class="form-control"/>
                                </div>
                            </div>
                        </div>

                         <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">KIRIM</button>
                                    <a href="{{route('altdev_alih_profesi')}}" class="btn btn-primary" type="button">BATAL</a>
                                    
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
