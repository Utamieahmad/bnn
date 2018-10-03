@extends('layouts.base_layout')
@section('title', 'Ubah Data Reviu Rencana Kerja Anggaran Kementerian/Lembaga')

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
          <h2>Form Tambah Data Reviu RKAKL Inspektorat Utama</h2>
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
          <form action="{{url('/irtama/reviu/update_irtama_rkakl')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$reviurkakl['id']}}">
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">


              <div class="form-group">
                <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['no_sprint']}}" id="no_sprint" name="no_sprint" type="text" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <label for="thn_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['tahun_anggaran']}}" id="thn_anggaran" name="thn_anggaran" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h2>Data Satker</h2>
                <div class="clearfix"></div>
              </div>


              <div class="form-group">
                <label for="jmlh_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Direviu </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['jmlh_direviu']}}" id="jmlh_direviu" name="jmlh_direviu" type="text" class="form-control numeric" onkeydown="numeric(event)">
                </div>
              </div>


              <div class="form-group">
                <label for="keterangan_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan yang Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['keterangan_direviu']}}" id="keterangan_direviu" name="keterangan_direviu" type="text" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <label for="jmlh_tdk_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Tidak Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['jmlh_tdk_direviu']}}" id="jmlh_tdk_direviu" name="jmlh_tdk_direviu" type="text" class="form-control numeric" onkeydown="numeric(event)">
                </div>
              </div>


              <div class="form-group">
                <label for="keterangan_tdk_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan yang Tidak Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['keterangan_tdk_direviu']}}" id="keterangan_tdk_direviu" name="keterangan_tdk_direviu" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h2>Tim</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="pengendali_teknis" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="pengendali_teknis" name="pengendali_teknis" required>
										<option value="">-- Pilih Pengendali Mutu --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}" {{ (($reviurkakl['pengendali_teknis'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              <div class="form-group">
                <label for="ketua_tim" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="ketua_tim" name="ketua_tim" required>
										<option value="">-- Pilih Ketua Tim --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}" {{ (($reviurkakl['ketua_tim'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              <div class="form-group">
                <label for="pereviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Pereviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="pereviu" name="pereviu" required>
										<option value="">-- Pilih Pereviu --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}" {{ (($reviurkakl['pereviu'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>


              {{-- <div class="form-group">
                <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="nama_tim" name="nama_tim" type="text" class="form-control">
                </div>
              </div> --}}

              <div class="x_title">
                <h2>Pagu Indikatif</h2>
                <div class="clearfix"></div>
              </div>


              <div class="form-group">
                <label for="indikatif_dukungan" class="col-md-3 col-sm-3 col-xs-12 control-label">Dukungan Manajemen dan Pelaksanaan Tugas Teknis Lainnya</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['indikatif_dukungan']}}" id="indikatif_dukungan" name="indikatif_dukungan" type="text" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <label for="indikatif_p4gn" class="col-md-3 col-sm-3 col-xs-12 control-label">Program P4GN</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['indikatif_p4gn']}}" id="indikatif_p4gn" name="indikatif_p4gn" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h2>Pagu Sebaran</h2>
                <div class="clearfix"></div>
              </div>


              <div class="form-group">
                <label for="sebaran_dukungan" class="col-md-3 col-sm-3 col-xs-12 control-label">Dukungan Manajemen dan Pelaksanaan Tugas Teknis Lainnya</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['sebaran_dukungan']}}" id="sebaran_dukungan" name="sebaran_dukungan" type="text" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <label for="sebaran_p4gn" class="col-md-3 col-sm-3 col-xs-12 control-label">Program P4GN</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['sebaran_p4gn']}}" id="sebaran_p4gn" name="sebaran_p4gn" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                  <label for="instansi" class="col-md-3 control-label">Permasalahan</label>
                  <div class="col-md-6">
                      <div class="mt-repeater">
                          <div data-repeater-list="permasalahan">
    												@if((json_decode($reviurkakl['meta_permasalahan'],true)))
    												@foreach(json_decode($reviurkakl['meta_permasalahan'],true) as $r1 => $c1)
    													<div data-repeater-item class="mt-repeater-item">
                                  <div class="row mt-repeater-row">
                                      <div class="col-md-12">
                                          <label class="control-label">Judul Permasalahan</label>
                                          <input value="{{$c1['judul']}}" name="permasalahan[{{$r1}}][judul]" type="text" class="form-control"> </div>
                                      </div>

                                      <div class="row mt-repeater-row">
                                      <div class="col-md-4">
                                          <label class="control-label">Jumlah Permasalahan</label>
                                          <input value="{{$c1['jml_permasalahan']}}" name="permasalahan[{{$r1}}][jml_permasalahan]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-4">
                                          <label class="control-label">Jumlah Satker</label>
                                          <input value="{{$c1['jml_satker']}}" name="permasalahan[{{$r1}}][jml_satker]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-3">
                                          <label class="control-label">Prosentase</label>
                                          <input value="{{$c1['prosentase']}}" name="permasalahan[{{$r1}}][prosentase]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-1">
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
                                      <div class="col-md-12">
                                          <label class="control-label">Judul Permasalahan</label>
                                          <input name="permasalahan[][judul]" type="text" class="form-control"> </div>
                                      </div>

                                      <div class="row mt-repeater-row">
                                      <div class="col-md-4">
                                          <label class="control-label">Jumlah Permasalahan</label>
                                          <input name="permasalahan[][jml_permasalahan]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-4">
                                          <label class="control-label">Jumlah Satker</label>
                                          <input name="permasalahan[][jml_satker]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-3">
                                          <label class="control-label">Prosentase</label>
                                          <input name="permasalahan[][prosentase]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                      <div class="col-md-1">
                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                              <i class="fa fa-close"></i>
                                          </a>
                                      </div>
                                  </div>
                              </div>
                              @endif
                          </div>
                          <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                              <i class="fa fa-plus"></i> Tambah </a>
                      </div>
                  </div>
              </div>

              <div class="form-group">
                <label for="nomor_lap" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Laporan </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkakl['nomor_lap']}}" id="nomor_lap" name="nomor_lap" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tanggal_lap" class="form-control col-md-7 col-xs-12 datetimepicker" value="{{( $reviurkakl['tanggal_lap'] ? \Carbon\Carbon::parse($reviurkakl['tanggal_lap'] )->format('d/m/Y') : '') }}"/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="url_bukti_tindak_lanjut" class="col-md-3 control-label">Laporan Reviu RKA-KL</label>
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
                        @if (!empty($reviurkakl['lap_reviu']))
                            lihat file : <a style="color:yellow" href="{{\Storage::url('IrtamaReviuRkakl/'.$reviurkakl['lap_reviu'])}}">{{$reviurkakl['lap_reviu']}}</a>
                        @endif
                    </span>
                  </div>
                </div>




            </div>

            <div class="form-actions fluid">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success">SIMPAN</button>
                  <a href="{{route('irtama_rkakl')}}" class="btn btn-primary" type="button">BATAL</a>
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
