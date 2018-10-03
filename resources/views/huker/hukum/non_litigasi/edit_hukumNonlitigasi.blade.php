@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Konsultasi Hukum (Non Litigasi)')

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
          <h2>Form Ubah Data Kegiatan Konsultasi Hukum (Non Litigasi) Direktorat Hukum</h2>
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
          <form action="{{URL('/huker/dir_hukum/update_hukum_nonlitigasi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$id}}">
            <div class="form-body">

              <div class="form-group" >
                <div class="form-group">
                  <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="pelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required >
                      <option value="">-- Pilih Pelaksana --</option>
                      @foreach($instansi as $in)
                      <option value="{{$in['id_instansi']}}" {{($data_detail['pelaksana'] == $in['id_instansi']) ? "selected" : ""}}>{{$in['nm_instansi']}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$data_detail['jenis_kegiatan']}}" id="jenis_kegiatan" name="jenis_kegiatan" placeholder="Rakor, Simposium, atau Seminar" type="text" class="form-control" required >
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprint_BNN" class="col-md-3 col-sm-3 col-xs-12 control-label">No.Surat Perintah BNN</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$data_detail['no_sprint_kepala']}}" id="no_sprint_kepala" name="no_sprint_kepala" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprint_deputi" class="col-md-3 col-sm-3 col-xs-12 control-label">No.Surat Perintah Deputi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$data_detail['no_sprint_deputi']}}" id="no_sprint_deputi" name="no_sprint_deputi" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="">
                  <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Mulai</label>
                  <div class="col-md-6 col-xs-6 col-sm-9">
                    <div class="row">
                      <div class="col-sm-5 col-md-5 col-xs-12">
                        <div class="row">
                          <div class="col-md-11 col-sm-11 col-xs-12 input-group date tanggal">
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
                      <div class="col-md-7 col-sm-7 col-xs-12 input-group date tanggal">
                        <div class="row">
                          <label for="tahun_anggaran" class="col-md-5 col-sm-5 col-xs-12 control-label">Tanggal Akhir</label>
                          <div class="col-md-7 col-sm-7 col-xs-12 input-group date tanggal">
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
              </div>

              <div class="form-group">
                <label for="nomor_surat_perintah" class="col-md-3 col-sm-3 col-xs-12 control-label">Tema Kegiatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$data_detail['tema']}}" id="tema" name="tema" type="text" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                  <div class="mt-repeater">
                    <div data-repeater-list="meta_narasumber">
                      @foreach(json_decode($data_detail['meta_narasumber'],true) as $r1 => $c1)
                      <div data-repeater-item="" class="mt-repeater-item">
                        <div class="mt-repeater-row">
                          <div class="row">
                            <div class="col-md-11 col-sm-11 col-xs-12">
                              <label class="control-label">Narasumber</label>
                              <input name="meta_narasumber[{{$r1}}][Narasumber]" value="{{ (isset($c1['Narasumber']) ? $c1['Narasumber'] : '')}}" type="text" class="form-control">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 p-0">
                              <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                <i class="fa fa-close"></i>
                              </a>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-11 col-sm-11 col-xs-12">
                              <label class="control-label">Materi</label>
                              <textarea name="meta_narasumber[{{$r1}}][materi]" class="form-control">{{ (isset($c1['materi']) ? $c1['materi'] : '')}}</textarea>
                              <!-- <input name=meta_narasumber[0][materi]" value="" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div> -->
                            </div>

                          </div>
                        </div>
                      </div>
                      @endforeach
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                      <i class="fa fa-plus"></i> Tambah Narasumber
                    </a>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="mt-repeater">
                    <div data-repeater-list="meta_peserta">
                      @foreach(json_decode($data_detail['meta_peserta'],true) as $r1 => $c1)
                      <div data-repeater-item class="mt-repeater-item">
                        <div class="row mt-repeater-row">
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <label class="control-label">Nama Instansi</label>
                            <input name="meta_peserta[{{$r1}}][nama_instansi]" value="{{ (isset($c1['nama_instansi']) ? $c1['nama_instansi'] : '')}}" type="text" class="form-control"> </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                              <label class="control-label">Jumlah Peserta</label>
                              <input name="meta_peserta[{{$r1}}][jumlah_peserta]" value="{{ (isset($c1['jumlah_peserta']) ? $c1['jumlah_peserta'] : '')}}" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                              <div class="col-md-1 col-sm-1 col-xs-12">
                                <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                  <i class="fa fa-close"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </div>
                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                          <i class="fa fa-plus"></i> Tambah Peserta</a>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="nomor_surat_perintah" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="{{$data_detail['tempat_kegiatan']}}" id="tempat_kegiatan" name="tempat_kegiatan" type="text" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="lokasi_kegiatan" class="col-md-3 control-label">Lokasi Kegiatan</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="lokasi_kegiatan" id="lokasi_kegiatan" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                          <option value="">-- Pilih Kabupaten --</option>
                          @foreach($propkab as $keyGroup => $jenis )
                          <optgroup label="{{$keyGroup}}">
                            @foreach($jenis as $key => $val)
                            <option value="{{$key}}" {{($key == $data_detail['lokasi_kegiatan']) ? 'selected="selected"':""}} >{{$val}}</option>
                            @endforeach
                          </optgroup>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="sumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
                      <div class="col-md-4">
                        <div class="mt-radio-list">
                          <label class="mt-radio col-md-9"> <input {{($data_detail['sumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="sumberanggaran" id="anggaran1">
                            <span>Dipa</span>
                          </label>

                          <label class="mt-radio col-md-9"> <input {{($data_detail['sumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="sumberanggaran" id="anggaran2">
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

                    <div class="form-group">
                      <label for="hasil_dicapai " class="col-md-3 control-label">Hasil yang dicapai</label>
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
                              <input type="file" name="hasil_dicapai "> </span>
                              <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                            </div>
                          </div>
                          <span class="help-block" style="color:white">
                            @if (!empty($data_detail['hasil_dicapai']))
                            lihat file : <a style="color:yellow" href="{{\Storage::url('HukumNonlitigasi/'.$data_detail['hasil_dicapai'])}}">{{$data_detail['hasil_dicapai']}}</a>
                            @endif
                          </span>
                        </div>
                      </div>

                    </div>

                    <div class="form-actions fluid">
                      <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">KIRIM</button>
                          <a href="{{route('hukum_nonlitigasi')}}" class="btn btn-primary" type="button">BATAL</a>
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
