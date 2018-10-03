@extends('layouts.base_layout')
@section('title', 'Tambah Data Pertemuan')

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
                    <h2>Form Tambah Data Pertemuan Direktorat Kerja Sama</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/huker/dir_kerjasama/input_kerjasama_bilateral')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
        <div class="form-body">

        <div class="form-group">
            <label for="kodejeniskerjasama" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kerja Sama</label>
            <div class="col-md-4">
                <div class="mt-radio-list">

                    <label class="mt-radio col-md-9"> <input type="radio" value="Sidang_Regional" name="kodejeniskerjasama" required >
                    <span>Sidang Regional</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" value="Sidang_Internasional" name="kodejeniskerjasama">
                    <span>Sidang Internasional</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" value="Pertemuan_Bilateral" name="kodejeniskerjasama">
                    <span>Pertemuan Bilateral</span>
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

        <div class="form-group">
            <label for="tgl_pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                <input type='text' name="tgl_pelaksana" class="form-control" required />
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>


        <div class="form-group">
            <label for="tempatpelaksa" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Pelaksanaan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tempatpelaksana" name="tempatpelaksana" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodenegara" class="col-md-3 col-sm-3 col-xs-12 control-label">Lembaga Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="lembaga_penyelenggara" name="lembaga_penyelenggara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodenegara" class="col-md-3 col-sm-3 col-xs-12 control-label">Negara Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="kodenegara" class="form-control select2" tabindex="-1" aria-hidden="true">
                  <option value="">-- Pilih Negara --</option>
                  @foreach($negara as $n)
                    <option value="{{$n->kode}}" > {{$n->nama_negara}}</p>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="institusi_penyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Institusi Penyelenggara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="institusi_penyelenggara" name="institusi_penyelenggara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="materi" name="materi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodesumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="mt-radio-list" id='buttons'>
                    <label class="mt-radio col-md-9"> <input type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                    <span>Dipa</span>
                    </label>
                    <label class="mt-radio col-md-9"> <input type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                    <span>Non Dipa</span>
                    </label>
                </div>
            </div>
        </div>

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
            <input type="hidden" name="asatker_code" id="kodeSatker" value="">
            <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">

            </div>
        </div>

        <div class="form-group">
            <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="no_sprint" name="no_sprint" type="text" class="form-control">
            </div>
        </div>

        <div class="x_title">
            <h2>Jumlah Delegasi RI</h2>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label for="jmlh_delegasi_bnn" class="col-md-3 col-sm-3 col-xs-12 control-label">BNN</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="jmlh_delegasi_bnn" name="jmlh_delegasi_bnn" type="number" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Kementerian Terkait</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="jmlh_delegasi_client" name="jmlh_delegasi_client" type="number" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kodenegara_mitra" class="col-md-3 control-label">Negara Mitra</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="kodenegara_mitra" class="form-control select2" tabindex="-1" aria-hidden="true">
                    <option value="">-- Pilih Negara --</option>
                    @foreach($negara as $n)
                      <option value="{{$n->kode}}" > {{$n->nama_negara}}</p>
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
                <span class="help-block">
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
