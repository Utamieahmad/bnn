@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Monev Peraturan Perundang-undangan')

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
                    <h2>Form Ubah Data Kegiatan Monev Peraturan Perundang-undangan Direktorat Hukum</h2>
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
    <form action="{{URL('/huker/dir_hukum/update_hukum_monevperaturanuu')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$data_detail['data']['id']}}">
        <div class="form-body">

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['nomor_sprint']}}" id="nomor_sprint" name="nomor_sprint" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                 @if($data_detail['data']['tanggal_pelaksanaan'] != "kosong")
              <input type='text' name="tanggal_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tanggal_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" />
              @else
              <input type="text" name="tanggal_pelaksanaan" value="" class="form-control" />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['lokasi_kegiatan']}}" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan_idkabkota" class="col-md-3 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="lokasi_kegiatan_idkabkota" id="lokasi_kegiatan_idkabkota" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab['data'] as $keyGroup => $jenis )
                    <optgroup label="{{$keyGroup}}">
                      @foreach($jenis as $key => $val)
                      <option value="{{$key}}" {{($key == $data_detail['data']['lokasi_kegiatan_idkabkota']) ? 'selected="selected"':""}} >{{$val}}</option>
                      @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi Monev</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['materi']}}" id="materi" name="materi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['narasumber']}}" id="narasumber" name="narasumber" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">Peserta</label>
            <div class="col-md-9">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_instansi">
                        @foreach(json_decode($data_detail['data']['meta_instansi'],true) as $r1 => $c1)
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-3">
                                    <label class="control-label">Nama Instansi</label>
                                    <input name="meta_instansi[{{$r1}}][list_nama_instansi]" value="{{ (isset($c1['list_nama_instansi']) ? $c1['list_nama_instansi'] : '')}}" type="text" class="form-control"> </div>
                                <div class="col-md-3">
                                    <label class="control-label">Alamat Instansi</label>
                                    <input name="meta_instansi[{{$r1}}][list_alamat_instansi]" value="{{ (isset($c1['list_alamat_instansi']) ? $c1['list_alamat_instansi'] : '')}}" type="text" class="form-control"> </div>
                                <div class="col-md-3">
                                    <label class="control-label">Jumlah Peserta</label>
                                    <input name="meta_instansi[{{$r1}}][list_jumlah_peserta]" value="{{ (isset($c1['list_jumlah_peserta']) ? $c1['list_jumlah_peserta'] : '')}}" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-1">
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
            <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
            <div class="col-md-4">
                <div class="mt-radio-list">
                    <label class="mt-radio col-md-9"> <input disabled {{($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
					<span>Dipa</span>
					</label>

				    <label class="mt-radio col-md-9"> <input disabled {{($data_detail['data']['kodesumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
					<span>Non Dipa</span>
					</label>
                </div>
            </div>
        </div>

        @if($data_detail['data']['anggaran_id'] != '')
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
            <label for="file_upload" class="col-md-3 control-label">Hasil yang dicapai</label>
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
                    lihat file : <a style="color:yellow" href="{{\Storage::url('HukumMonevPerundangundangan/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
                    @endif
                </span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('hukum_monevperaturanuu')}}" class="btn btn-primary" type="button">BATAL</a>
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
