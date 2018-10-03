@extends('layouts.base_layout')
@section('title', 'Tambah Data Kegiatan Pembelaan Hukum Pra Peradilan (Litigasi)')

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
                    <h2>Form Tambah Data Kegiatan Pembelaan Hukum Pra Peradilan (Litigasi) Direktorat Hukum</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/huker/dir_hukum/input_hukum_prapradilan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
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
            <label for="tgl_mulai" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan Pra Peradilan</label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_mulai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date datepicker-only'>
                                <input type='text' name="tgl_mulai" class="form-control" value="" required />
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
                                <input type='text' name="tgl_selesai" class="form-control" value="" required />
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
            <label for="tempat_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tempat_kegiatan" name="tempat_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="lokasi_kegiatan" id="lokasi_kegiatan" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                     <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab['data'] as $keyGroup => $jenis )
                    <optgroup label="{{$keyGroup}}">
                    @foreach($jenis as $key => $val)
                    <option value="{{$key}}">{{$val}}</option>
                    @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_permohonan_praperadilan" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Permohonan Pra Peradilan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="nomor_permohonan_praperadilan" name="nomor_permohonan_praperadilan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_permohonan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Permohonan Pra Peradilan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                <input type='text' name="tgl_permohonan" class="form-control" />
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="permasalahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Permasalahan Pra Peradilan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="permasalahan" name="permasalahan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="no_perkara" class="col-md-3 col-sm-3 col-xs-12 control-label">No Perkara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="no_perkara" name="no_perkara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tergugat" class="col-md-3 col-sm-3 col-xs-12 control-label">Tergugat</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tergugat" name="tergugat" placeholder="Jabatan" type="text" class="form-control">
            </div>
        </div>

        <div class="x_title">
            <h2>Identitas Pemohon Peradilan</h2>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Identitas</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="jenis_identitas" name="jenis_identitas" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="no_identitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Identitas</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="no_identitas" name="no_identitas" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nama_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="nama_pemohon" name="nama_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Lahir</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="tempat_lahir" name="tempat_lahir" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_lahir_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Lahir</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                <input type='text' name="tgl_lahir_pemohon" class="form-control" />
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Pemohon</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="lokasi_pemohon" name="lokasi_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Pemohon</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="alamat_pemohon" name="alamat_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Pekerjaan Pemohon</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="pekerjaan_pemohon" name="pekerjaan_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana Pra Peradilankan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label">No Surat Kuasa</label>
                        <input name="meta_praperadilan[no_surat_kuasa]" type="text" class="form-control"> </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label">No Surat Perintah</label>
                        <input name="meta_praperadilan[no_sprint]" type="text" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas Pelaksana</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="meta-repeater">
                    <div data-repeater-list="meta_pelaksana">
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Satuan Kerja</label>
                                    <select class="form-control metaSatker" name="meta_pelaksana[][list_satker]" onchange="loadIdentitasPelaksanaList(this)">
                                        <option value="">-- Pilih Satuan Kerja --</option>
                                          @if(count($satker))
                                            @foreach($satker as $s => $sval)
                                              <option value="{{$sval->id}}" data-id="{{$sval->id}}">{{$sval->nama}}</option>
                                            @endforeach
                                          @endif
                                    </select>

                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">NIP/Nama</label>
                                    <select class="form-control metaSatker" name="meta_pelaksana[][list_nip]">
                                        <option value="">-- Pilih Identitas --</option>
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreatePendamping">
                        <i class="fa fa-plus"></i> Tambah Pelaksana</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas Ahli Hukum</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_ahli">
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Nama</label>
                                    <input name="meta_ahli[][nama]" type="text" class="form-control"> </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">No Surat Tugas</label>
                                    <input name="meta_ahli[][no_surat_tugas]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <label class="control-label">Akademisi</label>
                                    <input name="meta_ahli[][akademisi]" type="text" class="form-control"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Tambah Ahli Hukum</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Data Sidang</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_sidang">
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Sidang Ke</label>
                                    <input name="meta_sidang[][sidang]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-5 col-sm-5 col-xs-12" >
                                    <label class="control-label">Tanggal Sidang</label>
                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal p-l-0 p-r-0' >
                                        <input type='text' name="meta_sidang[][tgl_sidang]" class="form-control metaDate" />
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div> </div>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <label class="control-label">Note</label>
                                    <input name="meta_sidang[][note]" type="text" class="form-control"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreateDataSidang">
                        <i class="fa fa-plus"></i> Tambah Data</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="sumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="mt-radio-list" id='buttons'>
                    <label class="mt-radio col-md-9"> <input type="radio" value="DIPA" name="sumberanggaran" id="anggaran1">
                    <span>Dipa</span>
                    </label>
                    <label class="mt-radio col-md-9"> <input type="radio" value="NONDIPA" name="sumberanggaran" id="anggaran2">
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
            <label for="hasil_akhir" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Akhir</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="hasil_akhir" id="hasil_akhir" class="select2 form-control" tabindex="-1" aria-hidden="true">
                    <option value="">-- Pilih Hasil --</option>
                    <option value="Menang">Menang</option>
                    <option value="Kalah">Kalah</option>
                </select>
            </div>
        </div>
    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('hukum_prapradilan')}}" class="btn btn-primary" type="button">BATAL</a>
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
