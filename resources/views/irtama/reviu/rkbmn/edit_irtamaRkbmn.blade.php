@extends('layouts.base_layout')
@section('title', 'Ubah Data Reviu Rencana Kebutuhan Barang Milik Negara')

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
          <h2>Form Tambah Data Reviu Rencana Kebutuhan Barang Milik Negara Inspektorat Utama</h2>
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
          <form action="{{url('/irtama/reviu/update_irtama_rkbmn')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$reviurkbmn['id']}}">
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['no_sprint']}}" id="no_sprint" name="no_sprint" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="thn_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tahun_anggaran']}}" id="thn_anggaran" name="thn_anggaran" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h2>Data Satker</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="jmlh_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['jmlh_direviu']}}" id="jmlh_direviu" name="jmlh_direviu" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="keterangan_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan yang Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['keterangan_direviu']}}" id="keterangan_direviu" name="keterangan_direviu" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="jmlh_tdk_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Tidak Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['jmlh_tdk_direviu']}}" id="jmlh_tdk_direviu" name="jmlh_tdk_direviu" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="keterangan_tdk_direviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan Tidak Direviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['keterangan_tdk_direviu']}}" id="keterangan_tdk_direviu" name="keterangan_tdk_direviu" type="text" class="form-control">
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
										<option value="{{$s['nama']}}" {{ (($reviurkbmn['pengendali_teknis'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
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
										<option value="{{$s['nama']}}" {{ (($reviurkbmn['ketua_tim'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
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
										<option value="{{$s['nama']}}" {{ (($reviurkbmn['pereviu'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>


              <div class="form-group">
                <label for="kelengkapan_dok" class="col-md-3 col-sm-3 col-xs-12 control-label">Kelengkapan Dokumen Pendukung</label>
                <div class="col-md-4">
                  <div class="radio">
                    <div class="mt-radio-list">
                      <label class="mt-radio col-md-9"> <input type="radio" {{($reviurkbmn['kelengkapan'] == 'lengkap') ? 'checked="checked"':""}} value="lengkap" name="kelengkapan_dok" onclick="kelengkapan(this)">
                        <span>Lengkap</span>
                      </label>
                      <label class="mt-radio col-md-9"> <input type="radio" {{($reviurkbmn['kelengkapan'] == 'tidak_lengkap') ? 'checked="checked"':""}} value="tidak_lengkap" name="kelengkapan_dok" onclick="kelengkapan(this)">
                        <span>Tidak Lengkap</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group ket_lengkap">
                <label for="keterangan_dokumen" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['ket_kelengkapan']}}" id="keterangan_dokumen" name="keterangan_dokumen" type="text" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <label for="kesesuaian_lk" class="col-md-3 col-sm-3 col-xs-12 control-label">Kesesuaian dengan Renstra KL</label>
                <div class="col-md-4">
                  <div class="radio">
                    <div class="mt-radio-list">
                      <label class="mt-radio col-md-9"> <input type="radio" {{($reviurkbmn['kesesuaian'] == 'sesuai') ? 'checked="checked"':""}} value="sesuai" name="kesesuaian_lk" onclick="kesesuaian(this)">
                        <span>Sesuai</span>
                      </label>
                      <label class="mt-radio col-md-9"> <input type="radio" {{($reviurkbmn['kesesuaian'] == 'tidak_sesuai') ? 'checked="checked"':""}} value="tidak_sesuai" name="kesesuaian_lk" onclick="kesesuaian(this)">
                        <span>Tidak Sesuai</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group ket_sesuai">
                <label for="keterangan_kesesuaian" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['ket_kesesuaian']}}" id="keterangan_kesesuaian" name="keterangan_kesesuaian" type="text" class="form-control">
                </div>
              </div>

              {{-- <div class="form-group hide ket_tdk_sesuai">
                <label for="keterangan_kesesuaian" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['ket_kesesuaian']}}" id="keterangan_kesesuaian" name="keterangan_kesesuaian" type="text" class="form-control">
                </div>
              </div> --}}

              <div class="x_title">
                <h2>Hasil Reviu</h2>
                <div class="clearfix"></div>
                <h4>Bangunan Gedung Kantor</h4>
              </div>

              <div class="form-group">
                <label for="kantor_jmlh_usulan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Usulan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['kantor_jmlh_usulan']}}" id="kantor_jmlh_usulan" name="kantor_jmlh_usulan" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="kantor_jmlh_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['kantor_jmlh_disetujui']}}" id="kantor_jmlh_disetujui" name="kantor_jmlh_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="kantor_jmlh_tdk_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Tidak Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['kantor_jmlh_tdk_disetujui']}}" id="kantor_jmlh_tdk_disetujui" name="kantor_jmlh_tdk_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="kantor_alasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alasan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['kantor_alasan']}}" id="kantor_alasan" name="kantor_alasan" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h4>Bangunan Rumah Negara</h4>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="rumah_jmlh_usulan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Usulan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['rumah_jmlh_usulan']}}" id="rumah_jmlh_usulan" name="rumah_jmlh_usulan" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="rumah_jmlh_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['rumah_jmlh_disetujui']}}" id="rumah_jmlh_disetujui" name="rumah_jmlh_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="rumah_jmlh_tdk_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Tidak Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['rumah_jmlh_tdk_disetujui']}}" id="rumah_jmlh_tdk_disetujui" name="rumah_jmlh_tdk_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="rumah_alasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alasan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['rumah_alasan']}}" id="rumah_alasan" name="rumah_alasan" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h4>Tanah untuk Bangunan Gedung Kantor</h4>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="tanahkantor_jmlh_usulan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Usulan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahkantor_jmlh_usulan']}}" id="tanahkantor_jmlh_usulan" name="tanahkantor_jmlh_usulan" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="tanahkantor_jmlh_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahkantor_jmlh_disetujui']}}" id="tanahkantor_jmlh_disetujui" name="tanahkantor_jmlh_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="tanahkantor_jmlh_tdk_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Tidak Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahkantor_jmlh_tdk_disetujui']}}" id="tanahkantor_jmlh_tdk_disetujui" name="tanahkantor_jmlh_tdk_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="tanahkantor_alasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alasan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahkantor_alasan']}}" id="tanahkantor_alasan" name="tanahkantor_alasan" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h4>Tanah untuk Bangunan Rumah Negara</h4>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="tanahrumah_jmlh_usulan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Usulan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahrumah_jmlh_usulan']}}" id="tanahrumah_jmlh_usulan" name="tanahrumah_jmlh_usulan" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="tanahrumah_jmlh_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahrumah_jmlh_disetujui']}}" id="tanahrumah_jmlh_disetujui" name="tanahrumah_jmlh_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="tanahrumah_jmlh_tdk_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Tidak Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahrumah_jmlh_tdk_disetujui']}}" id="tanahrumah_jmlh_tdk_disetujui" name="tanahrumah_jmlh_tdk_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="tanahrumah_alasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alasan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['tanahrumah_alasan']}}" id="tanahrumah_alasan" name="tanahrumah_alasan" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h4>Alat Angkutan darat bermotor</h4>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="angkutan_jmlh_usulan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Usulan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['angkutan_jmlh_usulan']}}" id="angkutan_jmlh_usulan" name="angkutan_jmlh_usulan" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="angkutan_jmlh_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['angkutan_jmlh_disetujui']}}" id="angkutan_jmlh_disetujui" name="angkutan_jmlh_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="angkutan_jmlh_tdk_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Tidak Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['angkutan_jmlh_tdk_disetujui']}}" id="angkutan_jmlh_tdk_disetujui" name="angkutan_jmlh_tdk_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="angkutan_alasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alasan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['angkutan_alasan']}}" id="angkutan_alasan" name="angkutan_alasan" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h4>Pemeliharaan</h4>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="pemeliharaan_jmlh_usulan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Usulan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['pemeliharaan_jmlh_usulan']}}" id="pemeliharaan_jmlh_usulan" name="pemeliharaan_jmlh_usulan" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="pemeliharaan_jmlh_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['pemeliharaan_jmlh_disetujui']}}" id="pemeliharaan_jmlh_disetujui" name="pemeliharaan_jmlh_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="pemeliharaan_jmlh_tdk_disetujui" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah yang Tidak Disetujui</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['pemeliharaan_jmlh_tdk_disetujui']}}" id="pemeliharaan_jmlh_tdk_disetujui" name="pemeliharaan_jmlh_tdk_disetujui" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                </div>
              </div>

              <div class="form-group">
                <label for="pemeliharaan_alasan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alasan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['pemeliharaan_alasan']}}" id="pemeliharaan_alasan" name="pemeliharaan_alasan" type="text" class="form-control">
                </div>
              </div>

              <div class="x_title">
                <h4>&nbsp;</h4>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="nomor_lap" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviurkbmn['nomor_lap']}}" id="nomor_lap" name="nomor_lap" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tanggal_lap" class="form-control" value="{{( $reviurkbmn['tanggal_lap'] ? \Carbon\Carbon::parse($reviurkbmn['tanggal_lap'] )->format('d/m/Y') : '') }}"/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="file_pemeriksaan" class="col-md-3 control-label">Laporan Reviu RKBMN</label>
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
                        @if (!empty($reviurkbmn['lap_reviu']))
                            lihat file : <a style="color:yellow" href="{{\Storage::url('IrtamaReviuRkbmn/'.$reviurkbmn['lap_reviu'])}}">{{$reviurkbmn['lap_reviu']}}</a>
                        @endif
                    </span>
                  </div>
                </div>

            </div>

            <div class="form-actions fluid">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success">SIMPAN</button>
                  <a href="{{route('irtama_rkbmn')}}" class="btn btn-primary" type="button">BATAL</a>
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
