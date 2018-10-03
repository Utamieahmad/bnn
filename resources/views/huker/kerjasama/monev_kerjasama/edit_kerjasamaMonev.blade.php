@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Monitoring dan Evaluasi Pelaksanaan Kerja Sama')

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
                    <h2>Form Ubah Data Kegiatan Monitoring dan Evaluasi Pelaksanaan Kerja Sama Direktorat Kerja Sama</h2>
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
    <form action="{{URL('/huker/dir_kerjasama/update_kerjasama_monev')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
    		<input type="hidden" name="id" value="{{$id}}">
    <div class="form-body">

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['nomor_sprint']}}" id="nomor_sprint" name="nomor_sprint" type="text" class="form-control" required >
            </div>
        </div>

        <div class="form-group">
            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['nama_kegiatan']}}" id="nama_kegiatan" name="nama_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
              @if($data_detail['data']['tanggal_pelaksanaan'] != "")
              <input type='text' name="tanggal_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tanggal_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" required />
              @else
              <input type="text" name="tanggal_pelaksanaan" value="" class="form-control" required />
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
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Pelaksanaan Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['lokasi_kegiatan']}}" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
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
                  <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} value="DIPA" name="kodesumberanggaran" id="anggaran1">
                  <span>Dipa</span>
                  </label>
                  <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
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
            <label for="file_hasil" class="col-md-3 control-label">Dokumen Kegiatan</label>
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
                    @if (!empty($data_detail['data']['file_upload']))
                        lihat file : <a style="color:yellow" href="{{\Storage::url('KerjasamaMonev/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
                    @endif
                </span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
				<a href="{{route('kerjasama_monev')}}" class="btn btn-primary" type="button">BATAL</a>
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
