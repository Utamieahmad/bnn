@extends('layouts.base_layout')
@section('title', 'Dir Diseminasi Informasi : Ubah Data Penyebarluasan Informasi P4GN Melalui Videotron')

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
          <h2>Form Ubah Data Kegiatan Penyebarluasan Informasi P4GN Melalui Media Videotron Direktorat Diseminasi</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form action="{{url('/pencegahan/dir_diseminasi/update_pendataan_videotron')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$pendataan['id']}}">
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="tgl_pelaksanaan" class="col-md-3 control-label">Tanggal Pelaksanaan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tgl_pelaksanaan" value="{{ \Carbon\Carbon::parse($pendataan['tanggal_pelaksanaan'])->format('d/m/Y') }}" class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="pelaksana" class="col-md-3 control-label">Pelaksana</label>
                <div class="col-md-6">
                  <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                    {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                    @foreach($instansi as $in)
                    <option value="{{$in['id_instansi']}}" {{($pendataan['idpelaksana'] == $in['id_instansi']) ? "selected" : ""}}>{{$in['nm_instansi']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="materi" class="col-md-3 control-label">Tema</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="materi" name="materi" class="form-control">{{$pendataan['materi']}}</textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="lokasi_kegiatan" class="col-md-3 control-label">Lokasi Kegiatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2 selectKabupaten" name="lokasi_penempatan_idkabkota">
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab as $prop => $val)
                    <optgroup label="{{$prop}}">
                      @foreach($val as $id => $kab)
                      <option value="{{$id}}" {{($pendataan['lokasi_penempatan_idkabkota'] == $id) ? "selected" : ""}}>{{$kab}}</option>
                      @endforeach
                    </optgroup>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="lokasi_penempatan" class="col-md-3 control-label">Alamat Penempatan Videotron</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$pendataan['lokasi_penempatan']}}" id="lokasi_penempatan" name="lokasi_penempatan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="durasi_waktu" class="col-md-3 control-label">Durasi Waktu (Detik)</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$pendataan['durasi_waktu']}}" id="durasi_waktu" name="durasi_waktu" type="number" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
                <div class="col-md-4">
                  <div class="mt-radio-list">
                    <label class="mt-radio col-md-9"> <input {{($pendataan['kodesumberanggaran'] == "DIPA") ? "checked" : ""}} disabled type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                      <span>Dipa</span>
                    </label>
                    <label class="mt-radio col-md-9"> <input {{($pendataan['kodesumberanggaran'] == "NONDIPA") ? "checked" : ""}} disabled type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                      <span>Non Dipa</span>
                    </label>
                  </div>
                </div>
              </div>

              @if($pendataan['anggaran_id'] != '')
              <div class="form-group" id="DetailAnggaran" >
                <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                <input type="hidden" name="asatker_code" id="kodeSatker" value="">
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
              @endif

              <div class="form-group">
                <label for="keterangan" class="col-md-3 control-label">Keterangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="keterangan" name="keterangan" class="form-control">{{$pendataan['keterangan']}}</textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="hasil_yang_dicapai" class="col-md-3 control-label">File Laporan</label>
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
                        @if (!empty($pendataan['file_upload']))
                            lihat file : <a style="color:yellow" href="{{\Storage::url('DiseminfoMediavideotron/'.$pendataan['file_upload'])}}">{{$pendataan['file_upload']}}</a>
                        @endif
                    </span>
                  </div>
                </div>

                <span class="help-block">
                </span>
            </div>
        </div>
    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_videotron')}}" class="btn btn-primary" type="button">BATAL</a>
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

    </div>
  </div>
</div>
@endsection
