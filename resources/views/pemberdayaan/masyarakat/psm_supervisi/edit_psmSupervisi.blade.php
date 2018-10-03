@extends('layouts.base_layout')
@section('title', 'Ubah Data Monitoring dan Evaluasi')

@section('content')
    <div class="right_col withAnggaran mSelect" role="main">
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
                    <h2>Form Ubah Data Monitoring dan Evaluasi Direktorat Peran Serta Masyarakat</h2>
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
                    <form action="{{URL('/pemberdayaan/dir_masyarakat/update_psm_supervisi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="form-body">

                        <div class="form-group">
                            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                              @if($data_detail['data']['tgl_pelaksanaan'] != "kosong")
                              <input type='text' name="tgl_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" required/>
                              @else
                              <input type="text" name="tgl_pelaksanaan" value="" class="form-control" required/>
                              @endif
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                            <div class="col-md-6">
                                <input type="hidden" name="id_pelaksana" class="id_pelaksana" value="{{$data_detail['data']['idpelaksana']}}"/>
                                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required> 
                                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                                  @foreach($instansi as $in)
                                  <option value="{{$in['id_instansi']}}" {{($in['id_instansi'] == $data_detail['data']['idpelaksana']) ? 'selected="selected"':""}} >{{$in['nm_instansi']}}</option>
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
                                        @php $i = 0;@endphp
                                        @foreach($sasaran as $skey => $sval)
                                        @php $i = $i+1; @endphp
                                            <option value="{{$skey}}" {{($data_detail['data']['kodesasaran'] == $skey) ? 'selected=selected':""}} id="anggaran{{$i}}"> {{$sval}} </option>
                                        @endforeach
                                    @endif
                                   </select>
                            </div>
                        </div>
                        <?php
                        $meta_penilaian = [];
                        if($data_detail['data']['meta_penilaian']){
                            $meta_penilaian = json_decode($data_detail['data']['meta_penilaian'],true);
                        }
                        ?>

                        <div class="form-group hasil_penilaian {{ ( $data_detail['data']['kodesasaran'] ? '' : 'hide')}}">
                            <label for="nama_instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Penilaian</label>
                            <div class="col-md-8">
                                <div class="mt-repeater">
                                    <div data-repeater-list="penilaian">
                                        @if(count($meta_penilaian))
                                            @foreach($meta_penilaian as $m => $p)
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="row mt-repeater-row">
                                                        <div class="col-md-5 col-xs-12 col-sm-5">
                                                            <label class="control-label">Nama Instansi</label>
                                                            <input name="penilaian[][nama_instansi]" value="{{ isset($p['nama_instansi']) ? $p['nama_instansi']  : ''}}" type="text" class="form-control">
                                                        </div>
                                                        <div class="col-md-4 col-xs-12 col-sm-4">
                                                            <div class="row">
                                                                <label class="control-label">Hasil Penilaian</label>
                                                                <select class="form-control mSelect2" name="penilaian[][hasil_penilaian]">
                                                                    <option value=""> -- Pilih Penilaian --</option>
                                                                    @if(isset($hasil_penilaian))
                                                                        @if(count($hasil_penilaian))
                                                                            @foreach($hasil_penilaian as $hkey => $hvalue)
                                                                                <option value="{{$hkey}}" {{ isset($p['hasil_penilaian']) ? (($p['hasil_penilaian'] == $hkey ) ? 'selected=selected' : '') : ''}}> {{$hvalue}}</option>
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
                                            @endforeach
                                        @else
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
                                        @endif
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
                                        @if($data_detail['data']['meta_instansi'])
                                            @foreach(json_decode($data_detail['data']['meta_instansi'],true) as $r1 => $c1)
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="row mt-repeater-row">
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <label class="control-label">Nama Instansi</label>
                                                            <input name="group-c[{{$r1}}][list_nama_instansi]" value="{{$c1['list_nama_instansi']}}" type="text" class="form-control"> </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                                            <div class="row">
                                                                <label class="control-label">Jumlah Peserta</label>
                                                                <input name="group-c[{{$r1}}][list_jumlah_peserta]" value="{{$c1['list_jumlah_peserta']}}" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
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
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <label class="control-label">Nama Instansi</label>
                                                            <input name="group-c[][list_nama_instansi]" value="" type="text" class="form-control"> </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                                            <div class="row">
                                                                <label class="control-label">Jumlah Peserta</label>
                                                                <input name="group-c[][list_jumlah_peserta]" value="" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
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
                                        <i class="fa fa-plus"></i> Tambah Instansi</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tim_supervisi" class="col-md-3 col-sm-3 col-xs-12 control-label">Tim Supervisi</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="{{$data_detail['data']['panitia_monev']}}" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi yang disampaikan</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="{{-- $data_detail['data']['materi'] --}}" id="materi" name="materi" type="text" class="form-control">
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 radio">
                                <div class="mt-radio-list">
                                    @if(count($sasaran))
                                        @php $i = 0;@endphp
                                        @foreach($kode_anggaran as $kkey => $kval)
                                        @php $i = $i+1; @endphp
                                            <label class="mt-radio col-md-9"> 
                                                <input type="radio"  value="{{$kkey }}" name="kodesumberanggaran" id="anggaran{{$i}}" {{($data_detail['data']['kodesumberanggaran'] == $kkey ) ? 'checked="checked"':""}}>
                                                <span>{{$kval}}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="PilihAnggaran" {{(($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? '' : 'style=display:none;') }}>
                            <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran </label> 
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                                    <option value="">-- Pilih Anggaran --</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="form-group" id="DetailAnggaran">
                            <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                            <input type="hidden" name="asatker_code" id="kodeSatker" value="">
                            <input type="hidden" name="kode_anggaran" id="kode_anggaran" value="{{( isset($data_anggaran['kode_anggaran']) ? $data_anggaran['kode_anggaran'] : '')}}">
                            <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
                                @if($data_detail['data']['anggaran_id'])
                                    @if(count($data_anggaran) > 0)
                                        <table class="table table-striped nowrap">
                                          <tr><td>Kode Anggaran</td><td>{{$data_anggaran['kode_anggaran']}}</td></tr>
                                          <tr><td>Sasaran</td><td>{{$data_anggaran['sasaran']}}</td></tr>
                                          <tr><td>Target Output</td><td>{{$data_anggaran['target_output']}}</td></tr>
                                          <tr><td>Satuan Output</td><td>{{$data_anggaran['satuan_output']}}</td></tr>
                                          <tr><td>Tahun</td><td>{{$data_anggaran['tahun']}}</td></tr>
                                        </table>
                                    @endif
                                @endif
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
                                <span class="help-block" style="color:white">
                                    @if ($data_detail['data']['file_upload'])
                                        @if (\Storage::exists('PsmSupervisi/'.$data_detail['data']['file_upload']))
                                            Lihat File : <a  target="_blank" class="link_file" href="{{\Storage::url('PsmSupervisi/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
                                        @endif
                                    @endif
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
