@extends('layouts.base_layout')
@section('title', 'Ubah Data Alih Fungsi Lahan Ganja Kawasan Narkotika')

@section('content')
    <div class="right_col mSelect" role="main">
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
                            <form action="{{route('update_altdev_lahan_ganja')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                                {{csrf_field()}}
                                <div class="form-body">
                                    <input type="hidden" name="id" value="{{$data->id}}"/>
                                    <div class="form-group">
                                        <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
                                        <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                            <input type='text' name="tgl_kegiatan" value="{{($data->tgl_kegiatan ? date('d/m/Y',strtotime($data->tgl_kegiatan)) : '') }}" class="form-control" required />
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                        <div class='col-md-6 col-sm-6 col-xs-12'>
                                            <input type='text' name="idpelaksana" value="{{$data->idpelaksana}}" class="form-control" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Penyelenggara</label>
                                        <div class='col-md-6 col-sm-6 col-xs-12'>
                                            <?php
                                                $meta_kode = [];
                                                if($data->meta_kode_penyelenggara){
                                                    $json = json_decode($data->meta_kode_penyelenggara);
                                                    foreach($json as $j => $jval){
                                                        $meta_kode[] = $jval;
                                                    }
                                                }else{
                                                    $meta_kode = [];
                                                }
                                            ?>
                                            <div class="checkbox">
                                                @if($penyelenggara)
                                                    @foreach($penyelenggara as $pkey=>$pvalue)
                                                        <label class="mt-checkbox col-md-9">
                                                            <input type="checkbox" value="{{$pkey}}" name="kodepenyelenggara[]" {{( in_array($pkey,$meta_kode) ? 'checked=checked' : '')}}>
                                                            <span>{{$pvalue}} </span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="kodekomoditi" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Komoditi</label>
                                        <div class='col-md-6 col-sm-6 col-xs-12 '>
                                            <?php
                                                $meta_komoditi = [];
                                                if($data->meta_kode_komoditi){
                                                    $json = json_decode($data->meta_kode_komoditi);
                                                    foreach($json as $j => $jval){
                                                        $meta_komoditi[] = $jval;
                                                    }
                                                }else{
                                                    $meta_komoditi = [];
                                                }
                                            ?>
                                            <div class="checkbox">
                                            @if($lahan_komoditi)
                                                @foreach($lahan_komoditi as $ckey=>$cvalue)
                                                    <label class="mt-checkbox col-md-9">
                                                        <input type="checkbox" value="{{$cvalue['nama_komoditi']}}" name="kodekomoditi[]" {{( in_array($cvalue['nama_komoditi'],$meta_komoditi) ? 'checked=checked' : '')}}>
                                                        <span>{{$cvalue['nama_komoditi']}} </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="bulan_tanam" class="col-md-3 col-sm-3 col-xs-12 control-label">Masa Tanam</label>

                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                           <input value="{{($data->bulan_tanam ? ((fmod($data->bulan_tanam,1) !== 0.00 )? $data->bulan_tanam : (int)$data->bulan_tanam ): '')}}" type="text" name="bulan_tanam" onkeypress="decimal_number(event,this)" class="form-control"/>
                                        </div>
                                        <label for="bulan_tanam" class="col-md-1 col-sm-1 col-xs-12 control-label">Bulan</label>
                                    </div>
                                     <div class="form-group m-t-20">
                                        <label for="Lokasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi dan Kepemilikan Lahan</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12 lokasi-alih-fungsi">
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12 col-sm-12">
                                                    <div class="mt-repeater">
                                                        <!-- start -->

                                                        @php
                                                            $json_meta = [];
                                                            if($data->meta_lokasi_lahan){
                                                                $json_meta = json_decode($data->meta_lokasi_lahan,true);
                                                            }else{
                                                                $json_meta = [];
                                                            }
                                                        @endphp


                                                        <!-- end -->
                                                        <div data-repeater-list="lokasi_lahan">

                                                            @if(count($json_meta) > 0)
                                                                @foreach($json_meta as $lkey => $lval)
                                                                    <div data-repeater-item="" class="mt-repeater-item">
                                                                        <div class="row mt-repeater-row">
                                                                            <div class="col-md-5 col-xs-12 col-sm-4">
                                                                                <select name="lokasi_lahan[0][lokasilahan_idkabkota]" id="lokasirawan_idkabkota" class="form-control mSelect2" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                                                                                    <option value="" selected="selected"> -- Pilih Kabupaten -- </option>
                                                                                    {!! dropdownLokasiKabupaten($lval['lokasilahan_idkabkota']) !!}
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-2 col-xs-12 col-sm-3">
                                                                                <input name="lokasi_lahan[0][luas_lahan]" value="{{($lval['luas_lahan'] ? number_format($lval['luas_lahan']) : '' )}}" type="text" class="form-control" placeholder="Luas" onkeydown="numeric_only(event,this)" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)">
                                                                            </div>
                                                                            <div class="col-md-4  col-xs-12 col-sm-4">
                                                                                <input type="text" value="{{(isset($lval['kodestatustanah']) ? $lval['kodestatustanah'] : '')}}" name="lokasi_lahan[0][kodestatustanah]" class="form-control"  Placeholder="Status Kepemilikan"/>
                                                                            </div>

                                                                            <div class="col-md-1  col-xs-12 col-sm-1 btn-repeater">
                                                                                <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                                                    <i class="fa fa-close"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            <div data-repeater-item="" class="mt-repeater-item">
                                                                <div class="row mt-repeater-row">
                                                                    <div class="col-md-5 col-xs-12 col-sm-4">
                                                                        <select name="lokasi_lahan[0][lokasilahan_idkabkota]" id="lokasirawan_idkabkota" class="form-control mSelect2" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                                                                            <option value="" selected="selected"> -- Pilih Kabupaten -- </option>
                                                                            {!! dropdownLokasiKabupaten() !!}
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 col-xs-12 col-sm-3">
                                                                        <input name="lokasi_lahan[0][luas_lahan]" type="text" class="form-control" placeholder="Luas" onkeydown="numeric_only(event,this)" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)">
                                                                    </div>
                                                                    <div class="col-md-4  col-xs-12 col-sm-4">
                                                                        <input type="text" value="" name="lokasi_lahan[0][kodestatustanah]" class="form-control"  Placeholder="Status Kepemilikan"/>
                                                                    </div>

                                                                    <div class="col-md-1  col-xs-12 col-sm-1 btn-repeater">
                                                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                                            <i class="fa fa-close"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                                            <i class="fa fa-plus"></i> Tambah Anggota</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-actions fluid m-t-20">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-success">KIRIM</button>
                                        <a href="{{route('altdev_lahan_ganja')}}" class="btn btn-primary" type="button">BATAL</a>
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
