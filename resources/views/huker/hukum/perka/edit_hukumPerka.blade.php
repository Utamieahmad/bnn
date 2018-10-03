@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Pembentukan Perka BNN')

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
                    <h2>Form Ubah Data Kegiatan Pembentukan Perka BNN Direktorat Hukum</h2>
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
    <form action="{{URL('/huker/dir_hukum/update_hukum_perka')}}" class="form-horizontal" id="frm_add_perka" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$data_detail['id']}}">
        <div class="form-body">

        <div class="form-group">
            <label for="nama_perka" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Perka</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['nama_perka']}}" id="nama_perka" name="nama_perka" type="text" class="form-control" required >
            </div>
        </div>

        <div class="form-group">
           <label for="bagian" class="col-md-3 col-sm-3 col-xs-12 control-label">Bagian</label>
                <div class="col-md-4">
                    <div class="mt-radio-list">
                        <label class="mt-radio col-md-9"> <input type="radio" value="Penelahaan" name="bagian" {{(trim($data_detail['bagian']) == 'Penelahaan') ? 'checked="checked"':""}} required >
                            <span>Penelahaan</span>
                        </label>

                        <label class="mt-radio col-md-9"> <input type="radio" value="Perancangan" name="bagian" {{(trim($data_detail['bagian']) == 'Perancangan') ? 'checked="checked"':""}} >
                            <span>Perancangan</span>
                        </label>

                    </div>
                </div>
        </div>

        <div class="form-group">
            <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['no_sprint']}}" id="no_sprint" name="no_sprint" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan Pra Peradilan</label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date datepicker-only'>
                                @if($data_detail['tgl_mulai'] != "")
                              <input type='text' name="tgl_mulai" value="{{ \Carbon\Carbon::parse($data_detail['tgl_mulai'] )->format('d/m/Y') }}" class="form-control" />
                              @else
                              <input type="text" name="tgl_mulai" value="" class="form-control" />
                              @endif
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_selesai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal'>
                                @if($data_detail['tgl_selesai'] != "")
                                  <input type='text' name="tgl_selesai" value="{{ \Carbon\Carbon::parse($data_detail['tgl_selesai'] )->format('d/m/Y') }}" class="form-control" />
                                  @else
                                  <input type="text" name="tgl_selesai" value="" class="form-control" />
                                  @endif
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="form-group">
            <label for="satker_inisiasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Satker Yang Menginisiasi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="satker_inisiasi" id="satker_inisiasi" class="form-control select2" tabindex="-1" aria-hidden="true">
                  <option value="">-- Pilih Satuan Kerja --</option>
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}" {{ ($in['id_instansi'] == trim($data_detail['satker_inisiasi'])) ? 'selected=""' : '' }}>{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="display:none">
            <label for="pelaksana" class="col-md-3 control-label">Pelaksana</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
            <div class="col-md-4">
                <div class="mt-radio-list">
                    <label class="mt-radio col-md-9"> <input {{($data_detail['sumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                    <span>Dipa</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input {{($data_detail['sumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                    <span>Non Dipa</span>
                    </label>
                </div>
            </div>
        </div>

          @if($data_detail['anggaran_id'] != '')
              <div class="form-group" id="PilihAnggaran">
                  <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                          <option value="">-- Pilih Anggaran --</option>
                      </select>
                  </div>
              </div>

          <div class="form-group" id="DetailAnggaran" >
              <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                  <input type="hidden" name="asatker_code" id="kodeSatker" value="681595">
                  <input type="hidden" id="kode_anggaran" value="{{$data_anggaran['data']['kode_anggaran']}}">
                  <input type="hidden" name="aid_anggaran" id="aid_anggaran" value="{{$data_anggaran['data']['refid_anggaran']}}">
              <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
                  <table class="table table-striped nowrap">
                      <tr><td>Kode Anggaran</td><td>{{$data_anggaran['data']['kode_anggaran']}}</td></tr>
                      <tr><td>Sasaran</td><td>{{$data_anggaran['data']['sasaran']}}</td></tr>
                      {{-- <tr><td>Pagu</td><td>{{$data_anggaran['data']['pagu']}}</td></tr> --}}
                      <tr><td>Target Output</td><td>{{$data_anggaran['data']['target_output']}}</td></tr>
                      <tr><td>Satuan Output</td><td>{{$data_anggaran['data']['satuan_output']}}</td></tr>
                      <tr><td>Tahun</td><td>{{$data_anggaran['data']['tahun']}}</td></tr>
                      {{-- <tr><td>Wilayah</td><td>{{$data_anggaran['data']['satker_code']}}</td></tr> --}}
                  </table>
              </div>
          </div>
      @else

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
                  <input type="hidden" name="asatker_code" id="kodeSatker" value="681595">
                  <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">

                  </div>
              </div>
          @endif

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_content">
                <div class="x_title">
                <h2>Tahapan Pembahasan</h2>
                <div class="clearfix"></div>
              </div>

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#draft" id="profile-tab" role="tab" data-toggle="tab" aria-expanded="true">a. Penyusunan draft awal</a>
                    </li>
                    <li role="presentation" class=""><a href="#harmonisasi" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">b. Harmonisasi</a>
                    </li>
                    <li role="presentation" class=""><a href="#finalisasi" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">c. Finalisasi</a>
                    </li>
                    <li role="presentation" class=""><a href="#rapat" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">d. Rapat penetapan</a>
                    </li>
                    <li role="presentation" class=""><a href="#lain" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Lain-Lain</a>
                    </li>

                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="draft" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <a class="btn btn-success" type="button" data-toggle="modal" data-target="#draft_perka">Tambah Draft</a>

                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th> Tanggal </th>
                            <th> No Surat Perintah </th>
                            <th> Jumlah Peserta </th>
                            <th> Laporan </th>
                            <th> Actions </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            @if(count($draft_awal['data']))
                            <tbody>
                              @foreach($draft_awal['data'] as $d)
                              <tr>
                                <td>{{$d['tanggal']}}</td>
                                <td>{{$d['no_sprint']}}</td>
                                <td>{{$d['jml_peserta']}}</td>
                                <td><a href="{{\Storage::url('HukumPerkaDraftAwal/'.$d['laporan'])}}">{{$d['laporan']}}</a></td>
                                <td class="actionTable">
                                  <a class="editPerkaDraftAwal" data-url="{{URL('/huker/update_perka_draftawal')}}" data-id="{{$d['id']}}" data-api="perkadraftawal" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                  <a class="button-delete" data-url="perkadraftawal" data-target="{{$d['id']}}" data-api="perkadraftawal" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                            @else
                            <div class="">
                              Data draft belum tersedia.
                            </div>
                            @endif
                          </tr>

                        </tbody>

                      </table>
                    </div>


                    <div role="tabpanel" class="tab-pane fade active" id="harmonisasi" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <a class="btn btn-success" data-toggle="modal" data-target="#harmonisasi_perka">Tambah Harmonisasi</a>
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th> Tanggal </th>
                            <th> Surat Perintah Peserta </th>
                            <th> Surat Tugas Peserta </th>
                            <th> Sprint/Sgas Narasumber </th>
                            <th> Narasumber / Materi </th>
                            <th> Peserta / Jumlah </th>
                            <th> Laporan </th>
                            <th> Actions </th>
                          </tr>
                        </thead>
                            @if(count($harmonisasi['data']))
                            <tbody>
                              @foreach($harmonisasi['data'] as $d)
                              <tr>
                                <td>{{$d['tanggal']}}</td>
                                <td>{{$d['no_sprint_peserta']}}</td>
                                <td>{{$d['no_sgas_peserta']}}</td>
                                <td>{{$d['no_sprint_nasum']}}</td>
                                <td>
                                  @php
                                    $meta = json_decode($d['meta_narasumber'], true);
                                    if(count($meta)){
                                      echo '<ol class="">';
                                      for($j = 0 ; $j < count($meta); $j++){
                                        echo '<li>'.$meta[$j]['narasumber_harmonisasi'].' / '.$meta[$j]['materi_harmonisasi'].'</li>';
                                      }
                                      echo '</ol>';
                                    }else{
                                      echo '-';
                                    }
                                  @endphp                                                                 </td>
                                <td>
                                  @php
                                    $meta = json_decode($d['meta_peserta'], true);
                                    if(count($meta)){
                                      echo '<ol class="">';
                                      for($j = 0 ; $j < count($meta); $j++){
                                        echo '<li>'.$meta[$j]['nama_harmonisasi'].' / '.$meta[$j]['jumlah_harmonisasi'].'</li>';
                                      }
                                      echo '</ol>';
                                    }else{
                                      echo '-';
                                    }
                                  @endphp                                                                 </td>
                                <td><a href="{{\Storage::url('HukumPerkaHarmonisasi/'.$d['laporan'])}}">{{$d['laporan']}}</a></td>
                                <td class="actionTable">
                                  <a class="editPerkaHarmonisasi" data-url="{{URL('/huker/update_perka_harmonisasi')}}" data-id="{{$d['id']}}" data-api="perkaharmonisasi" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                  <a class="button-delete" data-url="perkaharmonisasi" data-target="{{$d['id']}}" data-api="perkaharmonisasi" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                            @else
                            <div class="">
                              Data harmonisasi belum tersedia.
                            </div>
                            @endif

                        </tbody>

                      </table>

                    </div>

                    <div role="tabpanel" class="tab-pane fade " id="finalisasi" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <a class="btn btn-success" data-toggle="modal" data-target="#finalisasi_perka">Tambah Finalisasi</a>
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th> Tanggal </th>
                            <th> Surat Perintah Peserta </th>
                            <th> Surat Tugas Peserta </th>
                            <th> Sprint/Sgas Narasumber </th>
                            <th> Narasumber / Materi </th>
                            <th> Peserta / Jumlah </th>
                            <th> Laporan </th>
                            <th> Actions </th>
                          </tr>
                        </thead>
                            @if(count($finalisasi['data']))
                            <tbody>
                              @foreach($finalisasi['data'] as $d)
                              <tr>
                                <td>{{$d['tanggal']}}</td>
                                <td>{{$d['no_sprint_peserta']}}</td>
                                <td>{{$d['no_sgas_peserta']}}</td>
                                <td>{{$d['no_sprint_nasum']}}</td>
                                <td>
                                  @php
                                    $meta = json_decode($d['meta_narasumber'], true);
                                    if(count($meta)){
                                      echo '<ol class="">';
                                      for($j = 0 ; $j < count($meta); $j++){
                                        echo '<li>'.$meta[$j]['narasumber_finalisasi'].' / '.$meta[$j]['materi_finalisasi'].'</li>';
                                      }
                                      echo '</ol>';
                                    }else{
                                      echo '-';
                                    }
                                  @endphp                                                                 </td>
                                <td>
                                  @php
                                    $meta = json_decode($d['meta_peserta'], true);
                                    if(count($meta)){
                                      echo '<ol class="">';
                                      for($j = 0 ; $j < count($meta); $j++){
                                        echo '<li>'.$meta[$j]['nama_finalisasi'].' / '.$meta[$j]['jumlah_finalisasi'].'</li>';
                                      }
                                      echo '</ol>';
                                    }else{
                                      echo '-';
                                    }
                                  @endphp                                                                 </td>
                                <td><a href="{{\Storage::url('HukumPerkaFinalisasi/'.$d['laporan'])}}">{{$d['laporan']}}</a></td>
                                <td class="actionTable">
                                  <a class="editPerkaFinalisasi" data-url="{{URL('/huker/update_perka_finalisasi')}}" data-id="{{$d['id']}}" data-api="perkafinalisasi" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                                  <a class="button-delete" data-url="perkafinalisasi" data-target="{{$d['id']}}" data-api="perkafinalisasi" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                            @else
                            <div class="">
                              Data finalisasi belum tersedia.
                            </div>
                            @endif

                        </tbody>

                      </table>

                    </div>

                    <div role="tabpanel" class="tab-pane fade " id="rapat" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#finalisasi_perka">Tambah Finalisasi</button> -->
                      </div>
                      <input type="hidden" name="penetapan_id" value="{{ (isset($penetapan['id'])) ? $penetapan['id'] : '' }}">

                        <div class="form-group">
                            <label for="penetapan_tanggal" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                <input type='text' name="penetapan_tanggal" class="form-control" value="{{ (isset($penetapan['tanggal'])) ? \Carbon\Carbon::parse($penetapan['tanggal'])->format('d/m/Y') : '' }}" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="satker_inisiasi" class="col-md-3 col-sm-3 col-xs-12 control-label">No Surat Perintah</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="penetapan_no_sprint" name="penetapan_no_sprint" type="text" class="form-control" value="{{ (isset($penetapan['no_sprint'])) ? $penetapan['no_sprint'] : '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="satker_inisiasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="penetapan_jml_peserta" name="penetapan_jml_peserta" type="number" class="form-control" value="{{ (isset($penetapan['jml_peserta'])) ? $penetapan['jml_peserta'] : '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="penetapan_file_hasil" class="col-md-3 control-label">Laporan</label>
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
                                            <input type="file" name="penetapan_file_hasil"> </span>
                                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                    </div>
                                </div>
                              <span class="help-block" style="color:white">
                                  @if (!empty($penetapan['laporan']))
                                  lihat file : <a style="color:yellow" href="{{\Storage::url('HukumPerkaRapatPenetapan/'.$penetapan['laporan'])}}">{{$penetapan['laporan']}}</a>
                                  @endif
                              </span>
                            </div>
                        </div>

                        <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button class="btn btn-success addRapat" data-url="{{URL('/huker/update_perka_penetapan')}}">KIRIM</button>
                                    <a href="{{route('hukum_perka')}}" class="btn btn-primary" type="button">BATAL</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane fade " id="lain" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#finalisasi_perka">Tambah Finalisasi</button> -->
                      </div>

                        <div class="form-group">
                            <label for="tgl_ttd_kepala" class="col-md-3 col-sm-3 col-xs-12 control-label">e. Penandatangan oleh Kepala BNN</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                <input type='text' name="tgl_ttd_kepala" class="form-control" value="{{($data_detail['tgl_ttd_kepala'] != '') ? \Carbon\Carbon::parse($data_detail['tgl_ttd_kepala'])->format('d/m/Y') : '' }}"/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tgl_penomoran" class="col-md-3 col-sm-3 col-xs-12 control-label">f. Penomoran Perka</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                <input type='text' name="tgl_penomoran" class="form-control" value="{{($data_detail['tgl_penomoran'] != '') ? \Carbon\Carbon::parse($data_detail['tgl_penomoran'])->format('d/m/Y') : '' }}" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tgl_diundang" class="col-md-3 col-sm-3 col-xs-12 control-label">g. Diundangan oleh Kemenkumham</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                <input type='text' name="tgl_diundang" class="form-control" value="{{($data_detail['tgl_diundang'] != '') ? \Carbon\Carbon::parse($data_detail['tgl_diundang'])->format('d/m/Y') : '' }}" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">KIRIM</button>
                                    <a href="{{route('hukum_perka')}}" class="btn btn-primary" type="button">BATAL</a>
                                </div>
                            </div>
                        </div>



                    </div>


                  </div>
                </div>

              </div>
            </div>
          </div>

        <div class="form-group">
            <label for="file_hasil" class="col-md-3 control-label">Hasil yang dicapai</label>
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
                            <input type="file" name="hasil_dicapai"> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                    </div>
                </div>
                <span class="help-block" style="color:white">
                    @if (!empty($data_detail['hasil_dicapai']))
                    lihat file : <a style="color:yellow" href="{{\Storage::url('HukumPerkaDraftAwal/'.$data_detail['hasil_dicapai'])}}">{{$data_detail['hasil_dicapai']}}</a>
                    @endif
                </span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('hukum_perka')}}" class="btn btn-primary" type="button">BATAL</a>
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

      <div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
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
            <input type="hidden" class="audit_menu" value="Hukum dan Kerjasama - Direktorat Hukum - Pembentukan Perka"/>
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

@include('modal.modal_add_draft_perka')
@include('modal.modal_add_harmonisasi_perka')
@include('modal.modal_add_finalisasi_perka')
@endsection
