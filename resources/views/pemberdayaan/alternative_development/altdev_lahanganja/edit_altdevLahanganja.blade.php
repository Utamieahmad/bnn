@extends('layouts.base_layout')
@section('title', 'Ubah Data Alih Fungsi Lahan Ganja Kawasan Narkotika')

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
                                    <div class="form-group">
                                        <label for="keterangan_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan Lainnya</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input value="{{$data->keterangan_lainnya}}" type="text" name="keterangan_lainnya" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="lokasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Peninjauan</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                           <input value="{{$data->lokasi}}" type="text" name="lokasi" class="form-control"/>
                                        </div>
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
                                    <div style="height: 20px;">&nbsp;</div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            @if ($data->foto1)
                                                <img src="data:image/png;base64,{{$data->foto1}}" id="blah" style="width:100%;height:150px;" />
                                            @else
                                                <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
                                            @endif                                
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">                                
                                            @if ($data->foto2)
                                                <img src="data:image/png;base64,{{$data->foto2}}" id="blah2" style="width:100%;height:150px;" />
                                            @else
                                                <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
                                            @endif
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">                                
                                            @if ($data->foto3)
                                                <img src="data:image/png;base64,{{$data->foto3}}" id="blah3" style="width:100%;height:150px;" />
                                            @else
                                                <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
                                            @endif
                                        </div>
                                    </div>                                                                        

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <input type='file' name="foto1" onchange="readURL(this);" />
                                            <input type="text" name="foto1_old" hidden value="{{$data->foto1}}"/>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <input type='file' name="foto2" onchange="readURL2(this);" />
                                            <input type="text" name="foto2_old" hidden value="{{$data->foto2}}"/>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <input type='file' name="foto3" onchange="readURL3(this);" />
                                            <input type="text" name="foto3_old" hidden value="{{$data->foto3}}"/>
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
