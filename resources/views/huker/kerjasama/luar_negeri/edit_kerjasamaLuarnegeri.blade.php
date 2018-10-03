@extends('layouts.base_layout')
@section('title', 'Dir Kerjasama : Ubah Data Kegiatan Kerjasama Luar Negeri')

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
                    <h2>Form Ubah Data Kegiatan Kerjasama Luar Negeri Direktorat Kerjasama</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/huker/dir_kerjasama/update_kerjasama_luarnegeri')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
    		<input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">

        <div class="form-group">
            <label for="kodejeniskerjasama" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kerjasama</label>
            <div class="col-md-4">
                <div class="mt-radio-list">

                    <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodejeniskerjasama'] == 'Sidang Regional') ? 'checked="checked"':""}} value="Sidang Regional" name="kodejeniskerjasama">
                    <span>Sidang Regional</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodejeniskerjasama'] == 'Sidang Internasional') ? 'checked="checked"':""}} value="Sidang Internasional" name="kodejeniskerjasama">
                    <span>Sidang Internasional</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
              @if($data_detail['data']['tgl_pelaksana'] != "kosong")
              <input type='text' name="tgl_pelaksana" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksana'] )->format('d/m/Y') }}" class="form-control" />
              @else
              <input type="text" name="tgl_pelaksana" value="" class="form-control" />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="tempatpelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Pelaksanaan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['tempatpelaksana']}}" id="tempatpelaksana" name="tempatpelaksana" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lembaga Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['kodenegara']}}" id="kodenegara" name="kodenegara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['materi']}}" id="materi" name="materi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
            <div class="col-md-4">
                <div class="mt-radio-list">
                    <label class="mt-radio col-md-9"> <input disabled {{($data_detail['data']['kodeanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
					<span>Dipa</span>
					</label>

				    <label class="mt-radio col-md-9"> <input disabled {{($data_detail['data']['kodeanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
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
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['no_sprint']}}" id="no_sprint" name="no_sprint" type="text" class="form-control">
            </div>
        </div>

        <div class="x_title">
            <h2>Jumlah Delegasi RI</h2>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label for="jmlh_delegasi_bnn" class="col-md-3 col-sm-3 col-xs-12 control-label">BNN</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['jmlh_delegasi_bnn']}}" id="jmlh_delegasi_bnn" name="jmlh_delegasi_bnn" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Kementerian Terkait</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['jmlh_delegasi_client']}}" id="jmlh_delegasi_client" name="jmlh_delegasi_client" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="file_laporan" class="col-md-3 control-label">Laporan Hasil Pelaksanaan</label>
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
					@if (!empty($data_detail['data']['file_laporan']))
					lihat file : <a style="color:yellow" href="{{\Storage::url('KerjasamaLuarnegeri/'.$data_detail['data']['file_laporan'])}}">{{$data_detail['data']['file_laporan']}}</a>
					@endif
				</span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('kerjasama_luarnegeri')}}" class="btn btn-primary" type="button">BATAL</a>
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
