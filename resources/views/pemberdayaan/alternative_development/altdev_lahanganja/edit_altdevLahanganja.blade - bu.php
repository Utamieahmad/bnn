@extends('layouts.base_layout')
@section('title', 'Dir Alternative Development : Ubah Data Alih Fungsi Lahan Ganja Kawasan Narkotika')

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
                        <h2>Form Ubah Data Alih Fungsi Lahan Ganja Kawasan Narkotika Direktorat Alternative Development</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br/>
                            @if (session('status'))
                                @php
                                    $session= session('status');
                                @endphp
                                    <div class="alert alert-{{$session['status']}}">
                                        {{ $session['message'] }}
                                    </div>
                            @endif
                            <form action="{{route('update_altdev_lahan_ganja')}}" class="form-horizontal"method="post" enctype="multipart/form-data" autocomplete="on">
                                <input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ""}}">
                                {{csrf_field()}}
                                <div class="form-body">

                                    <div class="form-group">
                                        <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
                                        <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                            <input type='text' name="tgl_kegiatan" value="{{ (isset($data->tgl_kegiatan) ? \Carbon\Carbon::parse($data->tgl_kegiatan)->format('d/m/Y') : '')}}" class="form-control" />
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                        <div class="col-md-6">
                                            <select name="idpelaksana" id="idpelaksana" class="form-control select2" tabindex="-1" aria-hidden="true">
                                                <option value="" {{(isset($data->idpelaksana) ? "" : 'selected=selected')}}> Pilih Pelaksana </option>
                                                {!! dropdownPelaksana($data->idpelaksana) !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="lokasirawan_idkabkota" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kawasan Rawan (Kab/Kota)</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select name="lokasirawan_idkabkota" id="lokasirawan_idkabkota" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                                                <option value="" {{(isset($data->lokasirawan_idkabkota) ? "" : 'selected=selected')}}> Pilih Kota/Kabupaten </option>
                                                {!! dropdownLokasiKabupaten($data->lokasirawan_idkabkota,'lokasirawan_idkabkota') !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="kode_kecamatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kawasan Rawan Kecamatan</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->kode_kecamatan) ? $data->kode_kecamatan : "")}}" id="kode_kecamatan" name="kode_kecamatan" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="alamat_ormas" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kawasan Desa</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->kode_desa) ? $data->kode_desa : "")}}" id="alamat_ormas" name="kode_desa" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->nama_kegiatan) ? $data->nama_kegiatan :  "")}}" id="nama_kegiatan" name="nama_kegiatan" type="text" class="form-control">
                                        </div>
                                    </div>

                                        <div class="form-group">
                                            <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Penyelenggara</label>
                                            <div class='col-md-6 col-sm-6 col-xs-12 input-group'>
                                                <div class="radio">
                                                    @if($penyelenggara)
                                                        @foreach($penyelenggara as $pkey=>$pvalue)
                                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="kodepenyelenggara" id="" {{(isset($data->kodepenyelenggara) ? ($data->kodepenyelenggara == $pkey ? 'checked=checked' : '') : '' )}}>
                                                            <span>{{$pvalue}} </span>
                                                            </label>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="penyelenggara_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Penyelenggara Lainnya (*)</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input value="{{(isset($data->penyelenggara_lainnya) ? $data->penyelenggara_lainnya : "")}}" id="" name="penyelenggara_lainnya" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="lokasilahan_idkabkota" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Alih Fungsi Lahan (Kab/Kota)</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12"> 
                                                <select name="lokasilahan_idkabkota" id="lokasilahan_idkabkota" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                                                    <option value="" {{(isset($data->lokasilahan_idkabkota) ? "" : 'selected=selected')}}> Pilih Kabupaten/Kota </option>
                                                    {!! dropdownLokasiKabupaten($data->lokasilahan_idkabkota) !!}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="kode_kecamatan_alih_lahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Alih Fungsi Lahan Kecamatan</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input value="{{(isset($data->kode_kecamatan_alih_lahan) ? $data->kode_kecamatan_alih_lahan : "")}}" id="kode_kecamatan_alih_lahan" name="kode_kecamatan_alih_lahan" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="kode_desa_alih_lahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Alih Fungsi Lahan Kawasan Desa</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input value="{{(isset($data->kode_desa_alih_lahan) ? $data->kode_desa_alih_lahan : "")}}" id="kode_desa_alih_lahan" name="kode_desa_alih_lahan" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="luas_lahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Luas Lahan</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input value="{{(isset($data->luas_lahan) ? number_format($data->luas_lahan) : "")}}" id="luas_lahan" name="luas_lahan" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric(event)" onChange="reformatNumber(event,this)" onClick="reformatNumber(event,this)">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="status_kepemilikan" class="col-md-3 col-sm-3 col-xs-12 control-label">Status Kepemilkan Tanah</label>
                                            <div class='col-md-6 col-sm-6 col-xs-12 input-group'>
                                                <div class="radio">
                                                    @if($status_tanah)
                                                        @foreach($status_tanah as $skey=>$svalue)
                                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$skey}}" name="kodestatustanah" id="" {{(isset($data->kodestatustanah) ? ($data->kodestatustanah == $skey ? 'checked=checked' : '') : '' )}}>
                                                            <span>{{$svalue}} </span>
                                                            </label>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    <div class="form-group">
                                        <label for="statustanah_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Status Kepemilikan Tanah Lainnya (*)</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->statustanah_lainnya) ? $data->statustanah_lainnya : "")}}" id="statustanah_lainnya" name="statustanah_lainnya" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="kodekomoditi" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Komoditi</label>
                                        <div class='col-md-6 col-sm-6 col-xs-12 input-group'>
                                            <div class="radio">
                                                @if($lahan_komoditi)
                                                    @foreach($lahan_komoditi as $ckey=>$cvalue)
                                                        <label class="mt-radio col-md-9"> <input type="radio" value="{{$ckey}}" name="kodekomoditi" id="" {{(isset($data->kodekomoditi) ? ($data->kodekomoditi == $ckey ? 'checked=checked' : '') : '' )}}>
                                                        <span>{{$cvalue}} </span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="idkomoditi_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Komoditi Lainnya (*)</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->idkomoditi_lainnya) ? $data->idkomoditi_lainnya : "")}}" id="idkomoditi_lainnya" name="idkomoditi_lainnya" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="bulan_tanam" class="col-md-3 col-sm-3 col-xs-12 control-label">Bulan Tanam</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select name="bulan_tanam" class="form-control select2">
                                                <option value="" {{(isset($data->bulan_tanam) ? "" : 'selected=selected')}}> Pilih Bulan </option>
                                                {!! dropdownBulan($data->bulan_tanam) !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="bulan_panen" class="col-md-3 col-sm-3 col-xs-12 control-label">Bulan Panen</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select name="bulan_panen" class="form-control select2">
                                                <option value="" {{(isset($data->bulan_panen) ? "" : 'selected=selected')}}> Pilih Bulan </option>
                                                {!! dropdownBulan($data->bulan_panen) !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jumlah_petani" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Petani per Kelompok Tani</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->jumlah_petani) ? $data->jumlah_petani : "")}}" id="jumlah_petani" name="jumlah_petani" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric(event)">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama_kelompok_tani" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kelompok Tani</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{(isset($data->nama_kelompok_tani) ? $data->nama_kelompok_tani : "")}}" id="nama_kelompok_tani" name="nama_kelompok_tani" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions fluid">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success">KIRIM</button>
                                            <a href="{{route('altdev_lahan_ganja')}}" class="btn btn-primary" type="button">BATAL</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div>
                                <h2> Peserta Alih Fungsi Lahan Ganja</h2>
                                @include('pemberdayaan.alternative_development.peserta_alihFungsi.index_pesertaAlihFungsi')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
