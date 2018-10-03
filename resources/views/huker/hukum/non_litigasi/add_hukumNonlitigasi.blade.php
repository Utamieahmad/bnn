@extends('layouts.base_layout')
@section('title', 'Tambah Data Kegiatan Konsultasi Hukum (Non Litigasi)')

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
                        <h2>Form Tambah Data Kegiatan Konsultasi Hukum (Non Litigasi) Direktorat Hukum</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form action="{{URL('/huker/dir_hukum/input_hukum_nonlitigasi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            {{ csrf_field() }}
                            <div class="form-body">
                              <div class="form-group">
                                <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <select name="pelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required >
                                    <option value="">-- Pilih Pelaksana --</option>
                                    @foreach($instansi as $in)
                                    <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                                <div class="form-group">
                                    <label for="nomor_laporan_polisi" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" name="jenis_kegiatan" type="text" class="form-control" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nomor_surat_perintah" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah Kepala BNN</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="nomor_surat_perintah" name="nomor_surat_perintah" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nomor_surat_perintah" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah Deputi</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="nomor_surat_perintah" name="sprint_deputi" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <label for="tgl_mulai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
                                                        <input type='text' name="tgl_mulai" class="form-control" value=""/>
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <label for="tgl_selesai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_end'>
                                                        <input type='text' name="tgl_selesai" class="form-control" value=""/>
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
                                    <label for="nomor_surat_perintah" class="col-md-3 col-sm-3 col-xs-12 control-label">Tema Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="nomor_surat_perintah" name="tema_kegiatan" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="mt-repeater">
                                            <div data-repeater-list="meta_narasumber">
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="mt-repeater-row">
                                                        <div class="row">
                                                            <div class="col-md-11 col-sm-11 col-xs-12">
                                                                <label class="control-label">Narasumber</label>
                                                                <input name="meta_narasumber[0][Narasumber]" value="" type="text" class="form-control">
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
                                                                <textarea name="meta_narasumber[0][materi]" class="form-control"></textarea>
                                                                <!-- <input name=meta_narasumber[0][materi]" value="" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div> -->
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                                <i class="fa fa-plus"></i> Tambah Narasumber
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group -b-20">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="mt-repeater">
                                            <div data-repeater-list="meta_peserta">
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="row mt-repeater-row">
                                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                                            <label class="control-label">Nama Instansi</label>
                                                            <input name="detail_instansi[0][nama_instansi]" value="" type="text" class="form-control"> </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                                            <div class="row">
                                                                <label class="control-label">Jumlah Peserta</label>
                                                                <input name="detail_instansi[0][jumlah_peserta]" value="" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div>
                                                            </div>
                                                        <div class="col-md-1 col-sm-1 col-xs-12">
                                                            <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                                <i class="fa fa-close"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                                <i class="fa fa-plus"></i> Tambah Instansi</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-t-20">
                                    <label for="nomor_laporan_polisi" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" name="tempat_kegiatan" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group m-t-20">
                                    <label for="nomor_laporan_polisi" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="lokasi_kegiatan" class="form-control select2">
                                          @foreach($propkab as $keyGroup => $p )
                                          <optgroup label="{{$keyGroup}}">
                                            @foreach($p as $key => $val)
                                            <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                          </optgroup>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                  <label for="sumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="mt-radio-list" id='buttons'>
                                      <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="DIPA" name="sumberanggaran" id="anggaran1">
                                        <span>Dipa</span>
                                      </label>
                                      <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="NONDIPA" name="sumberanggaran" id="anggaran2">
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

                                <div class="form-group m-t-20">
                                    <label for="file_laporan" class="col-md-3 control-label">Hasil Yang Sudah Dicapai</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="input-group input-large">
                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                    <span class="fileinput-filename"> </span>
                                                </div>
                                                <span class="input-group-addon btn default btn-file">
                                                    <span class="fileinput-new"> Pilih Berkas </span>
                                                    <span class="fileinput-exists"> Ganti </span>
                                                    <input type="file" name="file_laporan" id="file-type"> </span>
                                                <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                             <div class="form-actions fluid">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">KIRIM</button>
                    								<a href="{{route('hukum_nonlitigasi')}}" class="btn btn-primary" type="button">BATAL</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
