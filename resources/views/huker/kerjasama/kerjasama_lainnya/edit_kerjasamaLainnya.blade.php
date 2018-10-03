@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Kerja Sama Lainnya')

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
                    <h2>Form Ubah Data Kegiatan Kerja Sama Lainnya Direktorat Kerja Sama</h2>
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
    <form action="{{URL('/huker/dir_kerjasama/update_kerjasama_lainnya')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
    		<input type="hidden" name="id" value="{{$id}}">
    <div class="form-body">

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
            <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['no_sprint']}}" id="no_sprint" name="no_sprint" type="text" class="form-control" required >
            </div>
        </div>

        <div class="form-group">
            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['nm_kegiatan']}}" id="nm_kegiatan" name="nm_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
              @if($data_detail['data']['tgl_pelaksanaan'] != "")
              <input type='text' name="tgl_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" required />
              @else
              <input type="text" name="tgl_pelaksanaan" value="" class="form-control" required />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Pelaksanaan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['tempat_pelaksanaan']}}" id="tempat_pelaksanaan" name="tempat_pelaksanaan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['narasumber']}}" id="narasumber" name="narasumber" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_peserta">
                    @if(!empty($data_detail['data']['meta_peserta']))
                      @foreach(json_decode($data_detail['data']['meta_peserta'],true) as $r1 => $c1)
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Nama Instansi</label>
                                    <input name="meta_peserta[{{$r1}}][list_nama_instansi]" value="{{$c1['list_nama_instansi']}}" type="text" class="form-control"> </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Alamat Instansi</label>
                                    <input name="meta_peserta[{{$r1}}][list_alamat_instansi]" value="{{$c1['list_alamat_instansi']}}" type="text" class="form-control"> </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Jumlah Peserta</label>
                                    <input name="meta_peserta[{{$r1}}][list_jumlah_peserta]" value="{{$c1['list_jumlah_peserta']}}" type="number" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                      @endforeach
                      @else
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Nama Instansi</label>
                                    <input name="meta_peserta[][list_nama_instansi]" value="" type="text" class="form-control"> </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Alamat Instansi</label>
                                    <input name="meta_peserta[][list_alamat_instansi]" value="" type="text" class="form-control"> </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label">Jumlah Peserta</label>
                                    <input name="meta_peserta[][list_jumlah_peserta]" value="" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                      @endif

                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Tambah Peserta</a>
                </div>
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
            <label for="sumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="mt-radio-list">
                  <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['sumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} value="DIPA" name="sumberanggaran" id="anggaran1">
                  <span>Dipa</span>
                  </label>
                  <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['sumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} value="NONDIPA" name="sumberanggaran" id="anggaran2">
                  <span>Non Dipa</span>
                  </label>
                </div>
            </div>
        </div>

          @if($data_detail['data']['anggaran_id'] != '')
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
            <label for="dokumen_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Dokumen Kegiatan</label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group input-large">
                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                            <span class="fileinput-filename"> </span>
                        </div>
                        <span class="input-group-addon btn default btn-file">
                            <span class="fileinput-new"> Pilih Berkas </span>
                            <span class="fileinput-exists"> Ganti </span>
                            <input type="file" name="dokumen_kegiatan"> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                    </div>
                </div>
                <span class="help-block" style="color:white">
                    @if (!empty($data_detail['data']['dokumen_kegiatan']))
                        lihat file : <a style="color:yellow" href="{{\Storage::url('KerjasamaLainnya/'.$data_detail['data']['dokumen_kegiatan'])}}">{{$data_detail['data']['dokumen_kegiatan']}}</a>
                    @endif
                </span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
				<a href="{{route('kerjasama_lainnya')}}" class="btn btn-primary" type="button">BATAL</a>
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
