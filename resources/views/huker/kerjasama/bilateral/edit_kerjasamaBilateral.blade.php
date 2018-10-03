@extends('layouts.base_layout')
@section('title', 'Ubah Data Pertemuan')

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
                    <h2>Form Ubah Data Pertemuan Direktorat Kerja Sama</h2>
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
    <form action="{{URL('/huker/dir_kerjasama/update_kerjasama_bilateral')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
    		<input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">

        <div class="form-group">
            <label for="kodejeniskerjasama" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kerja Sama</label>
            <div class="col-md-4">
                <div class="mt-radio-list">

                    <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodejeniskerjasama'] == 'Sidang_Regional') ? 'checked="checked"':""}} value="Sidang_Regional" name="kodejeniskerjasama" required >
                    <span>Sidang Regional</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodejeniskerjasama'] == 'Sidang_Internasional') ? 'checked="checked"':""}} value="Sidang_Internasional" name="kodejeniskerjasama">
                    <span>Sidang Internasional</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodejeniskerjasama'] == 'Pertemuan_Bilateral') ? 'checked="checked"':""}} value="Pertemuan_Bilateral" name="kodejeniskerjasama">
                    <span>Pertemuan Bilateral</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
              @if($data_detail['data']['tgl_pelaksana'] != "kosong")
              <input type='text' name="tgl_pelaksana" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksana'] )->format('d/m/Y') }}" class="form-control" required />
              @else
              <input type="text" name="tgl_pelaksana" value="" class="form-control" required />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>


        <div class="form-group">
            <label for="tempatpelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Pelaksanaan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['tempatpelaksana']}}" id="tempatpelaksana" name="tempatpelaksana" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lembaga Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['lembaga_penyelenggara']}}" id="lembaga_penyelenggara" name="lembaga_penyelenggara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodenegara" class="col-md-3 col-sm-3 col-xs-12 control-label">Negara Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="kodenegara" class="form-control select2" tabindex="-1" aria-hidden="true">
                  <option value="">-- Pilih Negara --</option>
                  @foreach($negara as $n)
                    <option value="{{$n->kode}}"  {{($data_detail['data']['kodenegara'] == $n->kode) ? 'selected="selected"':""}}> {{$n->nama_negara}}</p>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="institusi" class="col-md-3 col-sm-3 col-xs-12 control-label">Institusi Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['institusi_penyelenggara']}}" id="institusi_penyelenggara" name="institusi_penyelenggara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['materi']}}" id="materi" name="materi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
            <div class="col-md-4">
                <div class="mt-radio-list">
                  <label class="mt-radio col-md-9"> <input {{($data_detail['data']['kodeanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                  <span>Dipa</span>
                  </label>
                  <label class="mt-radio col-md-9"> <input {{($data_detail['data']['kodeanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                  <span>Non Dipa</span>
                  </label>
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
                <input value="{{$data_detail['data']['jmlh_delegasi_bnn']}}" id="jmlh_delegasi_bnn" name="jmlh_delegasi_bnn" type="number" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Kementerian Terkait</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['jmlh_delegasi_client']}}" id="jmlh_delegasi_client" name="jmlh_delegasi_client" type="number" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodenegara_mitra" class="col-md-3 control-label">Negara Mitra</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="kodenegara_mitra" class="form-control select2" tabindex="-1" aria-hidden="true">
                  <option value="">-- Pilih Negara --</option>
                  @foreach($negara as $n)
                    <option value="{{$n->kode}}"  {{($data_detail['data']['kodenegara_mitra'] == $n->kode) ? 'selected="selected"':""}}> {{$n->nama_negara}}</p>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="file_laporan" class="col-md-3 control-label">Dokumen Kegiatan</label>
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
						lihat file : <a style="color:yellow" href="{{\Storage::url('KerjasamaPerjanjianBilateral/'.$data_detail['data']['file_laporan'])}}">{{$data_detail['data']['file_laporan']}}</a>
					@endif
    			</span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
				<a href="{{route('kerjasama_bilateral')}}" class="btn btn-primary" type="button">BATAL</a>
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
