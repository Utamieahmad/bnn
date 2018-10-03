@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Pemetaan Kawasan Rawan Narkoba')

@section('content')
    <div class="right_col num-paste" role="main">
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
                            <h2>Form Ubah Data Kegiatan Pemetaan Kawasan Rawan Narkoba Direktorat Peran Serta Masyarakat</h2>
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
                            <form action="{{route('update_altdev_kawasan_rawan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                                <div class="form-body">
                                <input type="hidden" name="id" value="{{ (isset($data->id) ? $data->id : '' ) }}"/>
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                        <input type='text' name="tgl_pelaksanaan" class="form-control" value=" {{(isset($data) ? \Carbon\Carbon::parse($data->tgl_pelaksanaan)->format('d/m/Y') : "" ) }} " required/>
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <!-- <input type="text" class="form-control" value="" name="idpelaksana"/> -->

                                        <select class="form-control select2"  name="idpelaksana" required>
                                          <option value="">-- Pilih Pelaksana</option>
                                          {!! dropdownPelaksana($data->idpelaksana  ) !!}
                                        </select>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="lokasi_kawasan_rawan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Lokasi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="radio ">
                                            @if(isset($jenis_lokasi))
                                                @if(count($jenis_lokasi) > 0)
                                                    @foreach($jenis_lokasi as $lkey=>$lvalue)
                                                        <label class="mt-radio col-md-9"> <input type="radio" value="{{$lkey}}" name="jenis_lokasi" {{(isset($data) ? (($data->jenis_lokasi == $lkey) ? 'checked=checked' : ''):'')}}>
                                                        <span>{{$lvalue}} </span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_kawasan_rawan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Lokasi Rawan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->lokasi_kawasan_rawan) ? $data->lokasi_kawasan_rawan : "" ) }}" id="lokasi_kawasan_rawan" name="lokasi_kawasan_rawan" type="text" class="form-control">
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="kode_desakampung" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Desa/Kampung</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kode_desakampung) ? $data->kode_desakampung : "" ) }}" id="kode_desakampung" name="kode_desakampung" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kode_kelurahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kelurahan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kode_kelurahan) ? $data->kode_kelurahan : "" ) }}" id="kode_kelurahan" name="kode_kelurahan" type="text" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="kode_kecamatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kecamatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kode_kecamatan) ? $data->kode_kecamatan : "" ) }}" id="kode_kecamatan" name="kode_kecamatan" type="text" class="form-control">
                                    </div>
                                </div>    

                                <div class="form-group">
                                    <label for="lokasi_idkabkota" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kab/Kota</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <!-- <input value="{{(isset($data->lokasi_idkabkota) ? $data->lokasi_idkabkota : "" ) }}" id="lokasi_idkabkota" name="lokasi_idkabkota" type="text" class="form-control"> -->
                                        <select name="lokasi_idkabkota" class="form-control select2">
                                            <option value="">-- Pilih Kabupaten --</option>
                                            {!! dropdownLokasiKabupaten($data->lokasi_idkabkota) !!}}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_longitude" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Longitude</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->lokasi_longitude) ? $data->lokasi_longitude : "" ) }}" id="lokasi_longitude" name="lokasi_longitude" type="text" class="form-control">
                                    </div>
                                </div>    

                                <div class="form-group">
                                    <label for="lokasi_latitude" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Latitude</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->lokasi_latitude) ? $data->lokasi_latitude : "" ) }}" id="lokasi_latitude" name="lokasi_latitude" type="text" class="form-control">
                                    </div>
                                </div>      

                                <div class="form-group">
                                    <label for="kode_geografis" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Geografis</label>
                                    <div class="col-md-6 col-xs-12 col-sm-6">
                                        <div class="radio">
                                            @if($jenis_geografis)
                                                @foreach($jenis_geografis as $jkey=>$jvalue)
                                                    <label class="mt-radio col-md-9"> <input type="radio" value="{{$jkey}}" name="kode_geografis" id="" {{(isset($data->kode_geografis) ? ($data->kode_geografis == $jkey ? 'checked=checked' : '') : '' )}}>
                                                    <span>{{$jvalue}} </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="x_title">
                                    <h2>Pendidikan Masyarakat (Jumlah)</h2>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan_sd" class="col-md-3 col-sm-3 col-xs-12 control-label">SD</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->pendidikan_sd) ? $data->pendidikan_sd : "" ) }}" id="pendidikan_sd" name="pendidikan_sd" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan_slp" class="col-md-3 col-sm-3 col-xs-12 control-label">SLTP</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->pendidikan_slp) ? $data->pendidikan_slp : "" ) }}" id="pendidikan_slp" name="pendidikan_slp" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan_sla" class="col-md-3 col-sm-3 col-xs-12 control-label">SLTA</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->pendidikan_sla) ? $data->pendidikan_sla : "" ) }}" id="pendidikan_sla" name="pendidikan_sla" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan_pt" class="col-md-3 col-sm-3 col-xs-12 control-label">Perguruan Tinggi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->pendidikan_pt) ? $data->pendidikan_pt : "" ) }}" id="pendidikan_pt" name="pendidikan_pt" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan_putus_sekolah" class="col-md-3 col-sm-3 col-xs-12 control-label">Putus Sekolah</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->pendidikan_putus_sekolah) ? $data->pendidikan_putus_sekolah : "" ) }}" id="pendidikan_putus_sekolah" name="pendidikan_putus_sekolah" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan_tdk_sekolah" class="col-md-3 col-sm-3 col-xs-12 control-label">Tidak Sekolah</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->pendidikan_tdk_sekolah) ? $data->pendidikan_tdk_sekolah : "" ) }}" id="pendidikan_tdk_sekolah" name="pendidikan_tdk_sekolah" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="x_title">
                                    <h2>Pekerjaan Masyarakat</h2>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_tni" class="col-md-3 col-sm-3 col-xs-12 control-label">TNI</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_tni) ? $data->kerja_tni : "" ) }}" id="kerja_tni" name="kerja_tni" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="kerja_polisi" class="col-md-3 col-sm-3 col-xs-12 control-label">Polisi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_polisi) ? $data->kerja_polisi : "" ) }}" id="kerja_polisi" name="kerja_polisi" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="kerja_swasta" class="col-md-3 col-sm-3 col-xs-12 control-label">Swasta</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_swasta) ? $data->kerja_swasta : "" ) }}" id="kerja_swasta" name="kerja_swasta" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_wiraswasta" class="col-md-3 col-sm-3 col-xs-12 control-label">Wiraswasta</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_wiraswasta) ? $data->kerja_wiraswasta : "" ) }}" id="kerja_wiraswasta" name="kerja_wiraswasta" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_buruh" class="col-md-3 col-sm-3 col-xs-12 control-label">Kerja Buruh</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_buruh) ? $data->kerja_buruh : "" ) }}" id="kerja_buruh" name="kerja_buruh" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_tani" class="col-md-3 col-sm-3 col-xs-12 control-label">Petani</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_tani) ? $data->kerja_tani : "" ) }}" id="kerja_tani" name="kerja_tani" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_mahasiswa" class="col-md-3 col-sm-3 col-xs-12 control-label">Mahasiswa</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_mahasiswa) ? $data->kerja_mahasiswa : "" ) }}" id="kerja_mahasiswa" name="kerja_mahasiswa" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_pelajar" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelajar</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_pelajar) ? $data->kerja_pelajar : "" ) }}" id="kerja_pelajar" name="kerja_pelajar" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_irt" class="col-md-3 col-sm-3 col-xs-12 control-label">Ibu Rumah Tangga</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_irt) ? $data->kerja_irt : "" ) }}" id="kerja_irt" name="kerja_irt" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kerja_pengangguran" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengangguran</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->kerja_pengangguran) ? $data->kerja_pengangguran : "" ) }}" id="kerja_pengangguran" name="kerja_pengangguran" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="luas_kawasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Luas Kawasan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->luas_kawasan) ? $data->luas_kawasan : "" ) }}" id="luas_kawasan" name="luas_kawasan" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">Satuan m2</span>
                                </div>

                                <div class="x_title">
                                    <h2>Batas Wilayah</h2>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="batas_timur" class="col-md-3 col-sm-3 col-xs-12 control-label">Timur</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->batas_timur) ? $data->batas_timur : "" ) }}" id="batas_timur" name="batas_timur" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="batas_barat" class="col-md-3 col-sm-3 col-xs-12 control-label">Barat</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->batas_barat) ? $data->batas_barat : "" ) }}" id="batas_barat" name="batas_barat" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="batas_utara" class="col-md-3 col-sm-3 col-xs-12 control-label">Utara</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->batas_utara) ? $data->batas_utara : "" ) }}" id="batas_utara" name="batas_utara" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="batas_selatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Selatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->batas_selatan) ? $data->batas_selatan : "" ) }}" id="batas_selatan" name="batas_selatan" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="x_title">
                                    <h2>Narkoba yang Beredar</h2>
                                    <div class="clearfix"></div>
                                    <h5>Jenis Narkoba</h5>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_shabu" class="col-md-3 col-sm-3 col-xs-12 control-label">Shabu</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_shabu ? ((fmod($data->narkoba_shabu,1) !== 0.00 )? $data->narkoba_shabu : (int)$data->narkoba_shabu ): '')}}" id="narkoba_shabu" name="narkoba_shabu" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_ekstasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Ekstasi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_ekstasi ? ((fmod($data->narkoba_ekstasi,1) !== 0.00 )? $data->narkoba_ekstasi : (int)$data->narkoba_ekstasi ): '')}}" id="narkoba_ekstasi" name="narkoba_ekstasi" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_ganja" class="col-md-3 col-sm-3 col-xs-12 control-label">Ganja</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_ganja ? ((fmod($data->narkoba_ganja,1) !== 0.00 )? $data->narkoba_ganja : (int)$data->narkoba_ganja ): '')}}" id="narkoba_ganja" name="narkoba_ganja" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_putau" class="col-md-3 col-sm-3 col-xs-12 control-label">Putau</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_putau ? ((fmod($data->narkoba_putau,1) !== 0.00 )? $data->narkoba_putau : (int)$data->narkoba_putau ): '')}}" id="narkoba_putau" name="narkoba_putau" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_heroin" class="col-md-3 col-sm-3 col-xs-12 control-label">Heroin</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_heroin ? ((fmod($data->narkoba_heroin,1) !== 0.00 )? $data->narkoba_heroin : (int)$data->narkoba_heroin ): '')}}" id="narkoba_heroin" name="narkoba_heroin" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_benzodiazephine" class="col-md-3 col-sm-3 col-xs-12 control-label">Benzodiazephine</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_benzodiazephine ? ((fmod($data->narkoba_benzodiazephine,1) !== 0.00 )? $data->narkoba_benzodiazephine : (int)$data->narkoba_benzodiazephine ): '')}}" id="narkoba_benzodiazephine" name="narkoba_benzodiazephine" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_dextromethorphan" class="col-md-3 col-sm-3 col-xs-12 control-label">Dextromethorphan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_dextromethorphan ? ((fmod($data->narkoba_dextromethorphan,1) !== 0.00 )? $data->narkoba_dextromethorphan : (int)$data->narkoba_dextromethorphan ): '')}}" id="narkoba_dextromethorphan" name="narkoba_dextromethorphan" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="narkoba_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Lainnya</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{($data->narkoba_lainnya ? ((fmod($data->narkoba_lainnya,1) !== 0.00 )? $data->narkoba_lainnya : (int)$data->narkoba_lainnya ): '')}}" id="narkoba_lainnya" name="narkoba_lainnya" type="text" class="form-control col-md-7 col-xs-12 decimal_num" onkeypress="decimal_number(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kode_jmlhtersangka_tahunan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Tersangka Per Tahun</label>
                                    <div class="col-md-6 col-xs-12 col-sm-6">
                                        <div class="radio">
                                            @if($jumlah_tersangka)
                                                @foreach($jumlah_tersangka as $tkey=>$tvalue)
                                                    <label class="mt-radio col-md-9"> <input type="radio" value="{{$tkey}}" name="kode_jmlhtersangka_tahunan" id="" {{(isset($data->kode_jmlhtersangka_tahunan) ? ($data->kode_jmlhtersangka_tahunan == $tkey ? 'checked=checked' : '') : '' )}}>
                                                    <span>{{$tvalue}} </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="x_title">
                                    <h2>Jumlah Barang Bukti yang disita</h2><br>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="barbuk_shabu" class="col-md-3 col-sm-3 col-xs-12 control-label">Shabu</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_shabu) ? $data->barbuk_shabu : "")}}" id="barbuk_shabu" name="barbuk_shabu" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">gram</span>
                                </div>

                                <div class="form-group">
                                    <label for="barbuk_ekstasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Ekstasi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_ekstasi) ? $data->barbuk_ekstasi : "")}}" id="barbuk_ekstasi" name="barbuk_ekstasi" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">tablet</span>
                                </div>

                                <div class="form-group">
                                    <label for="barbuk_ganja" class="col-md-3 col-sm-3 col-xs-12 control-label">Ganja</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_ganja) ? $data->barbuk_ganja : "")}}" id="barbuk_ganja" name="barbuk_ganja" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">gram</span>
                                </div> 

                                <div class="form-group">
                                    <label for="barbuk_putau" class="col-md-3 col-sm-3 col-xs-12 control-label">Putau</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_putau) ? $data->barbuk_putau : "")}}" id="barbuk_putau" name="barbuk_putau" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">gram</span>
                                </div>

                                <div class="form-group">
                                    <label for="barbuk_heroin" class="col-md-3 col-sm-3 col-xs-12 control-label">Heroin</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_heroin) ? $data->barbuk_heroin : "")}}" id="barbuk_heroin" name="barbuk_heroin" type="text" class="form-control col-md-7 col-xs-12 " onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">gram</span>
                                </div>

                                <div class="form-group">
                                    <label for="barbuk_benzodiazephine" class="col-md-3 col-sm-3 col-xs-12 control-label">Benzodiazephine</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_benzodiazephine) ? $data->barbuk_benzodiazephine : "")}}" id="barbuk_benzodiazephine" name="barbuk_benzodiazephine" type="text" class="form-control col-md-7 col-xs-12 " onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">tablet</span>
                                </div>

                                <div class="form-group">
                                    <label for="barbuk_dextromethorphan" class="col-md-3 col-sm-3 col-xs-12 control-label">Dextromethorphan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_dextromethorphan) ? $data->barbuk_dextromethorphan : "")}}" id="barbuk_dextromethorphan" name="barbuk_dextromethorphan" type="text" class="form-control col-md-7 col-xs-12 " onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">gram</span>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input value="{{(isset($data->barbuk_lainnya) ? $data->barbuk_lainnya : "")}}" id="barbuk_lainnya" name="barbuk_lainnya" type="text" class="form-control col-md-7 col-xs-12" placeholder="Lainnya">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{(isset($data->barbuk_lainnya_jumlah) ? $data->barbuk_lainnya_jumlah : "")}}" id="barbuk_lainnya_jumlah" name="barbuk_lainnya_jumlah" type="text" class="form-control col-md-7 col-xs-12 " onkeydown="numeric_only(event,this)">
                                    </div>
                                    <span class="help-block white">gram</span>
                                </div>

                                <div class="form-group">
                                    <label for="meta_kriminalitas" class="col-md-3 control-label">Kriminalitas</label>
                                    <div class="col-md-6 col-xs-12 col-sm-6 checkbox">
                                            <?php
                                            $array_meta = [];
                                            $meta = explode(',', $data->meta_kriminalitas);
                                            if(count($meta) >= 1){
                                                for($i = 0; $i < count($meta); $i++){
                                                   $array_meta[] = $meta[$i];
                                                }
                                            }
                                            ?>
                                            @if($kriminalitas)
                                                @foreach($kriminalitas as $kkey=>$kvalue)
                                                    <label class="mt-radio col-md-9"> <input type="checkbox" value="{{$kkey}}" name="meta_kriminalitas[]" id="" {{( (count($data->kode_geografis)>= 1 )? (in_array($kkey,$array_meta) ? 'checked=checked' : '') : '' )}}>
                                                    <span>{{$kvalue}} </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                </div>

                                 <div class="form-actions fluid">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success">KIRIM</button>
                                             <a href="{{route('altdev_kawasan_rawan')}}" class="btn btn-primary" type="button">BATAL</a>

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
