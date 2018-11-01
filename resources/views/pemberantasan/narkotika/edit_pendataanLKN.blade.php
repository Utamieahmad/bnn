@extends('layouts.base_layout')
@section('title', 'Ubah Pendataan LKN')
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
                {!! (isset($breadcrumps) ? $breadcrumps : '' )!!}
                </ul>
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
                    <h2>Form Ubah Pendataan LKN Direktorat Narkotika</h2>
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
                    <form  data-parsley-validate class="form-horizontal form-label-left" action="{{URL('/pemberantasan/dir_narkotika/update_pendataan_lkn')}}" enctype="multipart/form-data" method="post" >
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal LKN</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                @if($data_kasus['data']['kasus_tanggal'] != "kosong")
                                <input type='text' name="kasus_tanggal" value="{{ \Carbon\Carbon::parse($data_kasus['data']['kasus_tanggal'] )->format('d/m/Y') }}" class="form-control" />
                                @else
                                <input type="text" name="kasus_tanggal" value="" class="form-control" />
                                @endif
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <select class="form-control select2" id="pelaksana" name="pelaksana">
                                    @foreach($instansi as $wil)
                                    <option value="{{$wil['id_instansi']}}" {{($wil['id_instansi'] == $data_kasus['data']['id_instansi']) ? 'selected="selected"':""}} >{{$wil['nm_instansi']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group group-lkn">
                            @php
                            if(strlen($data_kasus['data']['kasus_no']) > "20"){
                            $lkn = splitLKN($data_kasus['data']['kasus_no']);
                            } else {
                            $lkn = [
                            0 => "",
                            1 => "",
                            2 => "",
                            3 => "",
                            4 => "",
                            5 => ""
                            ];
                            }
                            @endphp

                            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN</label>
                            <div class="col-md-2 col-sm-1 col-xs-1">
                                <input type="text" name="noLKN[0]" id="noLKN0" value="{{(($lkn[0] != "")? $lkn[0] : "LKN")}}"  class="form-control" readonly>
                            </div>
                            <div class="col-md-2 col-sm-1 col-xs-1">
                                <input type="text" name="noLKN[1]" id="noLKN1" value="{{(($lkn[1] != "")? $lkn[1] : "")}}" class="form-control">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <select class="form-control" name="noLKN[2]" id="noLKN2">
                                    <!--option value="P2" {{($lkn[2] == 'P2') ? 'selected' : ''}}>P2</option>
                                    <option value="INTD" {{($lkn[2] == 'INTD') ? 'selected' : ''}}>INTD</option>
                                    <option value="TPPU" {{($lkn[2] == 'TPPU') ? 'selected' : ''}}>TPPU</option-->
                                    <option value="BRNTS" {{($lkn[2] == 'BRNTS') ? 'selected' : ''}}>BRNTS</option>
                                    <option value="NAR" {{($lkn[2] == 'NAR') ? 'selected' : ''}}>NAR</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-1 col-xs-1">
                                <input type="text" name="noLKN[3]" id="noLKN3" value="{{(isset($lkn[3])? $lkn[3] : "")}}"   class="form-control" >
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <input type="text" name="noLKN[4]" id="noLKN4" value="{{(isset($lkn[4])? $lkn[4] : "")}}"  class="form-control" readonly>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2 last-child">
                                <input type="text" name="noLKN[5]" id="noLKN5" value="{{(isset($lkn[5])? $lkn[5] : "")}}"  class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  ></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="pelaksanaGenerate" name="kasus_no" value="{{$data_kasus['data']['kasus_no']}}"   class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Penyidik</label>
                            <div class="col-md-6">
                                <div class="mt-repeater">
                                    <div data-repeater-list="penyidik">
                                        @if((json_decode($data_kasus['data']['meta_penyidik'],true)))
                                        @foreach(json_decode($data_kasus['data']['meta_penyidik'],true) as $r1 => $c1)
                                        <div data-repeater-item="" class="mt-repeater-item">
                                            <div class="row mt-repeater-row">
                                                <div class="col-md-10">
                                                    <label class="control-label">Nama Penyidik</label>
                                                    <select class="form-control selectPenyidik" name="penyidik[{{$r1}}][nama_penyidik]">
                                                        <option value="">-- Pilih Penyidik --</option>
                                                        @if ($penyidik)
                                                        @if ($penyidik['code'] == 200)
                                                        @foreach($penyidik['data']['pegawai'] as $w)
                                                        <option value="{{$w['nama']}}" {{($w['nama'] == $c1['nama_penyidik']) ? 'selected="selected"':""}} >{{$w['nama']}}</option>
                                                        @endforeach
                                                        @endif
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div data-repeater-item="" class="mt-repeater-item">
                                            <div class="row mt-repeater-row">
                                                <div class="col-md-10">
                                                    <label class="control-label">Nama Penyidik</label>
                                                    <select class="form-control selectPenyidik" name="penyidik[0][nama_penyidik]">
                                                        <option value="">-- Pilih Penyidik --</option>
                                                        @if ($penyidik['code'] == 200)
                                                        @foreach($penyidik['data']['pegawai'] as $w)
                                                        <option value="{{$w['nama']}}">{{$w['nama']}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                        <i class="fa fa-plus"></i> Tambah Penyidik</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Tanggal Kejadian</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                @if($data_kasus['data']['tgl_kejadian'] != "kosong")
                                <input type='text' name="tanggalKejadian" value="{{ \Carbon\Carbon::parse($data_kasus['data']['tgl_kejadian'])->format('d/m/Y') }}" class="form-control" />
                                @else
                                <input type="text" name="" value="" class="form-control" />
                                @endif
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >TKP Kasus</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="tkp" value="{{$data_kasus['data']['kasus_tkp']}}"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control select2 selectPropinsi" name="propinsi">
                                    <option value="">-- Pilih Provinsi -- </option>
                                    @foreach($propinsi['data'] as $p)
                                    <option value="{{$p['id_wilayah']}}" {{($p['id_wilayah'] == $data_kasus['data']['kasus_tkp_idprovinsi']) ? 'selected="selected"':""}}> {{$p['nm_wilayah']}}</p>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control select2 selectKabupaten" name="kabupaten">
                                    <option value="">-- Pilih Kabupaten --</option>
                                    @if($kabupaten == "kosong")
                                    @else
                                    @foreach($kabupaten['data'] as $k)
                                    <option value="{{$k['id_wilayah']}}"  {{($k['id_wilayah'] == $data_kasus['data']['kasus_tkp_idkabkota']) ? 'selected="selected"':""}}> {{$k['nm_wilayah']}}</p>
                                        @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Modus Operandi</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="modus" value="{{$data_kasus['data']['modus_operandi']}}" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Negara Sumber Narkotika</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control select2" name="negaraSumber">
                                    <option value="">-- Pilih Negara --</option>
                                    @foreach($negara as $n)
                                    <option value="{{$n->kode}}"  {{($data_kasus['data']['kode_negarasumbernarkotika'] == $n->kode) ? 'selected="selected"':""}}> {{$n->nama_negara}}</p>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jalur Masuk</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control select2 noSearch" name="jalurMasuk">
                                    <option @if ($data_kasus['data']['jalur_masuk']=='Perlintasan Batas Darat') selected="selected" @endif >Perlintasan Batas Darat</option>
                                    <option @if ($data_kasus['data']['jalur_masuk']=='Perlintasan Batas Laut') selected="selected" @endif >Perlintasan Batas Laut</option>
                                    <option @if ($data_kasus['data']['jalur_masuk']=='Perlintasan Batas Udara') selected="selected" @endif >Perlintasan Batas Udara</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Asal</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="ruteAsal" value="{{$data_kasus['data']['rute_asal']}}"   class="form-control col-md-7 col-xs-12" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Transit</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="ruteTransit" value="{{$data_kasus['data']['rute_transit']}}"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Tujuan</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="ruteTujuan" value="{{$data_kasus['data']['rute_tujuan']}}"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kasus</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control select2" name="jenisKasus">
                                    <option value="">-- Jenis Kasus -- </option>
                                    @foreach($jenisKasus['data'] as $keyGroup => $jenis )
                                    <option value="{{$jenis['id']}}" {{($data_kasus['data']['kasus_jenis'] == $jenis['id']) ? 'selected="selected"':""}}>{{$jenis['nm_jnskasus']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Uraian Singkat</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="uraian_singkat" value="{{$data_kasus['data']['uraian_singkat']}}"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Keterangan Lainnya</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="keterangan_lainnya" value="{{$data_kasus['data']['keterangan_lainnya']}}"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <!--div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Kasus</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control select2 noSearch" name="kelompokKasus">
                                                <option value="">-- Kelompok Kasus -- </option>
                                                <option value="TPPU" {{($data_kasus['data']['kasus_kelompok'] == "TPPU") ? 'selected="selected"':""}}>TPPU</option>
                                                <option value="NARKOTIKA" {{($data_kasus['data']['kasus_kelompok'] == "NARKOTIKA") ? 'selected="selected"':""}}>NARKOTIKA</option>
                                        </select>
                                </div>
                        </div-->

                        <div class="form-group">
                            <label for="file_upload" class="col-md-3 control-label">File Upload</label>
                            <div class="col-md-6">
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
                                <span class="help-block" style="color:white">
                                    @if ($data_kasus['data']['file_upload'])
                                    @if (\Storage::exists('NarkotikaKasus/'.$data_kasus['data']['file_upload']))
                                    Lihat File : <a  target="_blank" class="link_file" href="{{\Storage::url('NarkotikaKasus/'.$data_kasus['data']['file_upload'])}}">{{$data_kasus['data']['file_upload']}}</a>
                                    @endif
                                    @endif

                                </span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                @if ($data_kasus['data']['foto1'])
                                    <img src="data:image/png;base64,{{$data_kasus['data']['foto1']}}" id="blah" style="width:100%;height:150px;" />
                                @else
                                    <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
                                @endif                                
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">                                
                                @if ($data_kasus['data']['foto2'])
                                    <img src="data:image/png;base64,{{$data_kasus['data']['foto2']}}" id="blah2" style="width:100%;height:150px;" />
                                @else
                                    <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
                                @endif
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">                                
                                @if ($data_kasus['data']['foto3'])
                                    <img src="data:image/png;base64,{{$data_kasus['data']['foto3']}}" id="blah3" style="width:100%;height:150px;" />
                                @else
                                    <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type='file' name="foto1" onchange="readURL(this);" />
                                <input type="text" name="foto1_old" hidden value="{{$data_kasus['data']['foto1']}}"/>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type='file' name="foto2" onchange="readURL2(this);" />
                                <input type="text" name="foto2_old" hidden value="{{$data_kasus['data']['foto2']}}"/>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type='file' name="foto3" onchange="readURL3(this);" />
                                <input type="text" name="foto3_old" hidden value="{{$data_kasus['data']['foto3']}}"/>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">KIRIM</button>
                                <a href="{{route('pendataan_lkn')}}" class="btn btn-primary" type="button">BATAL</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tersangka" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Tersangka</a>
                            </li>
                            <li role="presentation" class=""><a href="#barang_bukti_narkotika" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Barbuk Narkotika</a>
                            </li>
                            <li role="presentation" class=""><a href="#barang_bukti_prekursor" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barbuk Prekursor</a>
                            </li>
                            <li role="presentation" class=""><a href="#barang_bukti_adiktif" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barbuk NPS dan Adiktif lainnya</a>
                            </li>
                            <li role="presentation" class=""><a href="#barang_bukti_aset" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barbuk Aset</a>
                            </li>
                            <li role="presentation" class=""><a href="#barang_bukti_nonnarkotika" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barbuk Non Narkotika</a>
                            </li>

                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tersangka" aria-labelledby="home-tab">
                                <div class="tools pull-right m-b-20">
                                    <button class="btn btn-success tambahTersangka" data-toggle="modal" data-target="#add_modaltersangka" data-url="{{URL('/pemberantasan/input_tersangka')}}">TAMBAH TERSANGKA</button>
                                </div>

                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%"> No </th>
                                            <th width="25%"> Nama Tersangka </th>
                                            <th width="15%"> Warga Negara </th>
                                            <th width="15%"> Peran Tersangka </th>
                                            <th width="15%"> Pendidikan Akhir </th>
                                            <th width="15%"> Pekerjaan </th>
                                            <th width="20%"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($tersangka['data']))
                                        @php $i = 1; @endphp
                                        @foreach($tersangka['data'] as $t)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$t['tersangka_nama']}}</td>
                                            <td>{{$t['kode_warga_negara']}}</td>
                                            <td>{{$t['nama_peran']}}</td>
                                            <td>{{$t['nama_pendidikan_akhir']}}</td>
                                            <td>{{$t['nama_pekerjaan']}}</td>
                                            <td class="actionTable">
                                                <a class="editTersangka" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="{{$t['tersangka_id']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                <a class="button-delete deleteTersangka" data-parent="kasus" data-parent_id="{{$t['kasus_id']}}" data-url="tersangka" data-target="{{$t['tersangka_id']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                        </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="7">
                                                <div class="">
                                                    Data Tersangka belum tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="barang_bukti_narkotika" aria-labelledby="profile-tab">
                                <div class="tools pull-right m-b-20">
                                    <button class="btn btn-success tambahBbNarkotika" data-toggle="modal" data-target="#add_modalNarkotika" data-url="{{URL('/pemberantasan/input_brgbukti')}}">TAMBAH BARANGBUKTI NARKOTIKA</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"> No </th>
                                            <th width="15%"> Jenis Barang Bukti </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="15%"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($brgBuktiNarkotika['data']))
                                        @php $i = 1; @endphp
                                        @foreach($brgBuktiNarkotika['data'] as $brgBukti)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
                                            <td>{{$brgBukti['nm_brgbukti']}}</td>
                                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['nm_satuan']}} )</td>
                                            <td class="actionTable">
                                                <a class="editBbNarkotika" data-url="{{URL('/pemberantasan/update_brgbukti')}}" data-id="{{$brgBukti['kasus_barang_bukti_id']}}" data-api="kasusbrgbukti" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                <a class="button-delete deleteBbNarkotika" data-parent="kasus" data-parent_id="{{$brgBukti['kasus_id']}}" data-url="kasusbrgbukti" data-target="{{$brgBukti['kasus_barang_bukti_id']}}" data-api="kasusbrgbukti" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                        </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="">
                                                    Data barang bukti belum tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="barang_bukti_prekursor" aria-labelledby="profile-tab">
                                <div class="tools pull-right m-b-20">
                                    <button class="btn btn-success tambahBbPrekursor" data-toggle="modal" data-target="#add_modalPrekursor" data-url="{{URL('/pemberantasan/input_brgbukti_prekursor')}}">TAMBAH BARANGBUKTI PREKURSOR</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"> No </th>
                                            <th width="15%"> Jenis Barang Bukti </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="15%"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($brgBuktiPrekursor['data']))
                                        @php $i = 1; @endphp
                                        @foreach($brgBuktiPrekursor['data'] as $brgBukti)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
                                            <td>{{$brgBukti['nm_brgbukti']}}</td>
                                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['nm_satuan']}} )</td>
                                            <td class="actionTable">
                                                <a class="editBbPrekursor" data-url="{{URL('/pemberantasan/update_brgbukti_prekursor')}}" data-id="{{$brgBukti['barangbukti_nonnarkotika_id']}}" data-api="buktiprekursor" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                <a class="button-delete deleteBbPrekursor" data-parent="kasus" data-parent_id="{{$brgBukti['kasus_id']}}" data-url="buktiprekursor" data-target="{{$brgBukti['barangbukti_nonnarkotika_id']}}" data-api="buktiprekursor" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                        </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="">
                                                    Data barang bukti belum tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="barang_bukti_adiktif" aria-labelledby="profile-tab">
                                <div class="tools pull-right m-b-20">
                                    <button class="btn btn-success tambahBbAdiktif" data-toggle="modal" data-target="#add_modalAdiktif" data-url="{{URL('/pemberantasan/input_brgbukti_adiktif')}}">TAMBAH BARANGBUKTI NPS & ADIKTIF LAINNYA</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"> No </th>
                                            <th width="15%"> Jenis Barang Bukti </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="15%"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($brgBuktiAdiktif['data']))
                                        @php $i = 1; @endphp
                                        @foreach($brgBuktiAdiktif['data'] as $brgBukti)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
                                            <td>{{$brgBukti['nm_brgbukti']}}</td>
                                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['nm_satuan']}} )</td>
                                            <td class="actionTable">
                                                <a class="editBbAdiktif" data-url="{{URL('/pemberantasan/update_brgbukti_adiktif')}}" data-id="{{$brgBukti['kasus_barang_bukti_id']}}" data-api="kasusbrgbukti" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                <a class="button-delete deleteBbAdiktif" data-parent="kasus" data-parent_id="{{$brgBukti['kasus_id']}}" data-url="kasusbrgbukti" data-target="{{$brgBukti['kasus_barang_bukti_id']}}" data-api="kasusbrgbukti" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                        </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="">
                                                    Data barang bukti belum tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="barang_bukti_aset" aria-labelledby="profile-tab">
                                <div class="tools pull-right m-b-20">

                                    <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsetbarang" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH BARANG BUKTI ASET</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%"> No </th>
                                            <th width="25%"> Nama Aset </th>
                                            <th width="5%"> Jumlah </th>
                                            <th width="25%"> Konversi (Rupiah) </th>
                                            <th width="20%"> Keterangan </th>
                                            <th width="15%"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($brgBuktiAsetBarang['data']))
                                        @php $i = 1; @endphp
                                        @foreach($brgBuktiAsetBarang['data'] as $val)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$val['nama_aset']}}</td>
                                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                            <td>{{$val['nilai_aset']}}</td>
                                            <td>{{$val['keterangan']}}</td>
                                            <td class="actionTable">
                                                <a class="editAset" data-idAset="#add_modalAsetbarang" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                <a class="button-delete " data-parent="kasus" data-parent_id="{{$val['kasus_id']}}" data-url="buktinonnarkotika" data-target="{{$val['id_aset']}}" data-api="aset" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                        </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6">
                                                <div class="">
                                                    Data barang bukti belum tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <!--div class="tools pull-right" style="margin-bottom:15px;">

                                        <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsettanah" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH ASET TANAH</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                        <thead>
                                                <tr role="row" class="heading">
                                                        <th width="5%"> No </th>
                                                        <th width="25%"> Nama Aset </th>
                                                        <th width="5%"> Jumlah </th>
                                                        <th width="25%"> Konversi (Rupiah) </th>
                                                        <th width="20%"> Keterangan </th>
                                                        <th width="15%"> Actions </th>
                                                </tr>
                                        </thead>
                                        @if(count($brgBuktiAsetTanah['data']))
                                        <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($brgBuktiAsetTanah['data'] as $val)
                                                <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$val['nama_aset']}}</td>
                                                        <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                                        <td>{{$val['nilai_aset']}}</td>
                                                        <td>{{$val['keterangan']}}</td>
                                                        <td class="actionTable">
                                                                <a class="editAset" data-idAset="#add_modalAsettanah" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                                <a data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                                        </td>
                                                </tr>
                                                @php $i = $i+1; @endphp
                                                @endforeach
                                        </tbody>
                                        @else
                                        <div class="">
                                                Data barang bukti belum tersedia.
                                        </div>
                                        @endif
                                </table>

                                <div class="tools pull-right" style="margin-bottom:15px;">

                                        <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsetbangunan" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH ASET BANGUNAN</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                        <thead>
                                                <tr role="row" class="heading">
                                                        <th width="5%"> No </th>
                                                        <th width="25%"> Nama Aset </th>
                                                        <th width="5%"> Jumlah </th>
                                                        <th width="25%"> Konversi (Rupiah) </th>
                                                        <th width="20%"> Keterangan </th>
                                                        <th width="15%"> Actions </th>
                                                </tr>
                                        </thead>
                                        @if(count($brgBuktiAsetBangunan['data']))
                                        <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($brgBuktiAsetBangunan['data'] as $val)
                                                <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$val['nama_aset']}}</td>
                                                        <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                                        <td>{{$val['nilai_aset']}}</td>
                                                        <td>{{$val['keterangan']}}</td>
                                                        <td class="actionTable">
                                                                <a class="editAset" data-idAset="#add_modalAsetbangunan" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                                <a data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                                        </td>
                                                </tr>
                                                @php $i = $i+1; @endphp
                                                @endforeach
                                        </tbody>
                                        @else
                                        <div class="">
                                                Data barang bukti belum tersedia.
                                        </div>
                                        @endif
                                </table>

                                <div class="tools pull-right" style="margin-bottom:15px;">

                                        <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsetlogam" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH ASET LOGAM MULIA</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                        <thead>
                                                <tr role="row" class="heading">
                                                        <th width="5%"> No </th>
                                                        <th width="25%"> Nama Aset </th>
                                                        <th width="5%"> Jumlah </th>
                                                        <th width="25%"> Konversi (Rupiah) </th>
                                                        <th width="20%"> Keterangan </th>
                                                        <th width="15%"> Actions </th>
                                                </tr>
                                        </thead>
                                        @if(count($brgBuktiAsetLogam['data']))
                                        <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($brgBuktiAsetLogam['data'] as $val)
                                                <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$val['nama_aset']}}</td>
                                                        <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                                        <td>{{$val['nilai_aset']}}</td>
                                                        <td>{{$val['keterangan']}}</td>
                                                        <td class="actionTable">
                                                                <a class="editAset" data-idAset="#add_modalAsetlogam" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                                <a data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                                        </td>
                                                </tr>
                                                @php $i = $i+1; @endphp
                                                @endforeach
                                        </tbody>
                                        @else
                                        <div class="">
                                                Data barang bukti belum tersedia.
                                        </div>
                                        @endif
                                </table>

                                <div class="tools pull-right" style="margin-bottom:15px;">

                                        <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsetuang" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH ASET UANG TUNAI</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                        <thead>
                                                <tr role="row" class="heading">
                                                        <th width="5%"> No </th>
                                                        <th width="25%"> Nama Aset </th>
                                                        <th width="5%"> Jumlah </th>
                                                        <th width="25%"> Konversi (Rupiah) </th>
                                                        <th width="20%"> Keterangan </th>
                                                        <th width="15%"> Actions </th>
                                                </tr>
                                        </thead>
                                        @if(count($brgBuktiAsetUang['data']))
                                        <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($brgBuktiAsetUang['data'] as $val)
                                                <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$val['nama_aset']}}</td>
                                                        <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                                        <td>{{$val['nilai_aset']}}</td>
                                                        <td>{{$val['keterangan']}}</td>
                                                        <td class="actionTable">
                                                                <a class="editAset" data-idAset="#add_modalAsetuang" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                                <a data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                                        </td>
                                                </tr>
                                                @php $i = $i+1; @endphp
                                                @endforeach
                                        </tbody>
                                        @else
                                        <div class="">
                                                Data barang bukti belum tersedia.
                                        </div>
                                        @endif
                                </table>

                                <div class="tools pull-right" style="margin-bottom:15px;">

                                        <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsetrekening" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH ASET REKENING</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                        <thead>
                                                <tr role="row" class="heading">
                                                        <th width="5%"> No </th>
                                                        <th width="25%"> Nama Aset </th>
                                                        <th width="5%"> Jumlah </th>
                                                        <th width="25%"> Konversi (Rupiah) </th>
                                                        <th width="20%"> Keterangan </th>
                                                        <th width="15%"> Actions </th>
                                                </tr>
                                        </thead>
                                        @if(count($brgBuktiAsetRekening['data']))
                                        <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($brgBuktiAsetRekening['data'] as $val)
                                                <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$val['nama_aset']}}</td>
                                                        <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                                        <td>{{$val['nilai_aset']}}</td>
                                                        <td>{{$val['keterangan']}}</td>
                                                        <td class="actionTable">
                                                                <a class="editAset" data-idAset="#add_modalAsetrekening" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                                <a data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                                        </td>
                                                </tr>
                                                @php $i = $i+1; @endphp
                                                @endforeach
                                        </tbody>
                                        @else
                                        <div class="">
                                                Data barang bukti belum tersedia.
                                        </div>
                                        @endif
                                </table>

                                <div class="tools pull-right" style="margin-bottom:15px;">

                                        <button class="btn btn-success tambahAset" data-toggle="modal" data-target="#add_modalAsetsurat" data-url="{{URL('/pemberantasan/input_brgbukti_aset')}}">TAMBAH ASET SURAT BERHARGA</button>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                        <thead>
                                                <tr role="row" class="heading">
                                                        <th width="5%"> No </th>
                                                        <th width="25%"> Nama Aset </th>
                                                        <th width="5%"> Jumlah </th>
                                                        <th width="25%"> Konversi (Rupiah) </th>
                                                        <th width="20%"> Keterangan </th>
                                                        <th width="15%"> Actions </th>
                                                </tr>
                                        </thead>
                                        @if(count($brgBuktiAsetSurat['data']))
                                        <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($brgBuktiAsetSurat['data'] as $val)
                                                <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$val['nama_aset']}}</td>
                                                        <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                                                        <td>{{$val['nilai_aset']}}</td>
                                                        <td>{{$val['keterangan']}}</td>
                                                        <td class="actionTable">
                                                                <a class="editAset" data-idAset="#add_modalAsetsurat" data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                                <a data-url="{{URL('/pemberantasan/update_brgbukti_aset')}}" data-id="{{$val['id_aset']}}" data-api="gettersangka" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                                        </td>
                                                </tr>
                                                @php $i = $i+1; @endphp
                                                @endforeach
                                        </tbody>
                                        @else
                                        <div class="">
                                                Data barang bukti belum tersedia.
                                        </div>
                                        @endif
                                </table-->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="barang_bukti_nonnarkotika" aria-labelledby="profile-tab">
                                <div class="tools pull-right  m-b-20">
                                    <button class="btn btn-success" data-toggle="modal" data-target="#add_modalNonnarkotika" data-url="{{URL('/pemberantasan/input_brgbukti_nonar')}}">TAMBAH BARANGBUKTI NON NARKOTIKA</button>
                                </div>

                                <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"> No </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="15%"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($brgBuktiNonNarkotika['data']))
                                        @php $i = 1; @endphp
                                        @foreach($brgBuktiNonNarkotika['data'] as $brgBukti)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$brgBukti['keterangan']}}</td>
                                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['nm_satuan']}} )</td>
                                            <td class="actionTable">
                                                <a class="editBbNonnarkotika" data-url="{{URL('/pemberantasan/update_brgbukti_nonar')}}" data-id="{{$brgBukti['kasus_barang_bukti_id']}}" data-api="kasusbrgbukti" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                                <a class="button-delete deleteBbNonnarkotika" data-parent="kasus" data-parent_id="{{$brgBukti['kasus_id']}}" data-url="kasusbrgbukti" data-target="{{$brgBukti['kasus_barang_bukti_id']}}" data-api="kasusbrgbukti" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                        </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="">
                                                    Data barang bukti belum tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
{{-- <div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">

	<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
						</div>
						<div class="modal-body">
						                                                            Apakah Anda ingin menghapus data ini ?
						</div>
                                                            <input type="hidden" class="target_id" value=""/>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
							<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button                                                            >
						</div>
					</div>
				</div>
			</div> --}}

			<div class="modal fade bs-modal-sm" tabindex="-                                                                1" role="dialog" id="modalDelete" aria-h                                                                    idden="true">
				<div class="moda                                                                        l-dialog modal-sm">
					<div cla                                                                            ss="modal-content">

						<div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda ingin menghapus data ini ?
                                            </div>
                                            <input type="hidden" class="target_id" value=""/>
                                            <input type="hidden" class="target_parent" value=""/>
                                            <input type="hidden" class="target_parent_id" value=""/>
                                            <input type="hidden" class="audit_menu" value="Pemberantasan - Direktorat Narkotika - Pendataan LKN"/>
                                            <input type="hidden" class="audit_url" value="http://{{ $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}"/>
                                            <input type="hidden" class="audit_ip_address" value="{{ $_SERVER['SERVER_ADDR'] }}"/>
                                            <input type="hidden" class="audit_user_agent" value="{{ $_SERVER['HTTP_USER_AGENT'] }}"/>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                                                <button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            @include('modal.modal_add_tersangka')
                            @include('modal.modal_add_narkotika')
                            @include('modal.modal_add_prekursor')
                            @include('modal.modal_add_adiktif')
                            @include('modal.modal_add_asetbarang')
                            @include('modal.modal_add_asettanah')
                            @include('modal.modal_add_asetbangunan')
                            @include('modal.modal_add_asetlogam')
                            @include('modal.modal_add_asetuang')
                            @include('modal.modal_add_asetrekening')
                            @include('modal.modal_add_asetsurat')
                            @include('modal.modal_add_nonnarkotika')
                            @endsection
