@extends('layouts.base_layout')
@section('title', 'Ubah Data Audit dengan Tujuan Tertentu')

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
          <h2>Form Ubah Data Audit dengan Tujuan Tertentu Inspektorat Utama</h2>
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
          <form action="{{url('/irtama/riktu/update_irtama_riktu')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}

            <input type="hidden" name="id" value="{{$riktu['id']}}">
            <div class="form-body">

              <div class="form-group">
                <label for="no_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['no_sprint']}}" id="no_sprint" name="no_sprint" type="text" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Hasil Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['no_hasil_laporan']}}" id="no_hasil_laporan" name="no_hasil_laporan" type="text" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                  <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Hasil Laporan</label>

                  <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>

                      <input type='text' value="{{ \Carbon\Carbon::parse($riktu['tgl_hasil_laporan'])->format('d/m/Y') }}" name="tgl_hasil_laporan" class="form-control" />
                      <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>

              <div class="form-group">
                <label for="judul_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Hasil Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['judul_hasil_laporan']}}" id="judul_hasil_laporan" name="judul_hasil_laporan" type="text" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label for="jenis_pelanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Pelanggaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['jenis_pelanggaran']}}" id="jenis_pelanggaran" name="jenis_pelanggaran" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="tempatkejadian_idprovinsi" class="col-md-3 col-sm-3 col-xs-12 control-label">Satker</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="tempatkejadian_idprovinsi" name="tempatkejadian_idprovinsi">

                    <option value="">-- Pilih Satker --</option>
                    @if(count($instansi))
                      @foreach($instansi as $wil)
                        <option value="{{$wil['id_instansi']}}" {{( ($wil['id_instansi'] == $riktu['tempatkejadian_idprovinsi'])? 'selected=selected' : '' )}}> {{$wil['nm_instansi']}}</option>
                      @endforeach
                    @endif

                </select>
                </div>
              </div>

              <div class="form-group">
                <label for="tempatkejadian_idkabkota" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2 selectKabupaten" name="tempatkejadian_idkabkota">
                    <option value="">-- Pilih Kabupaten --</option>
                    @if(count($propkab))
                      @foreach($propkab as $p => $pvalue)
                        <option value="" disabled> <strong> {{$p}}<strong> </option>
                        @if(count($pvalue))
                          @foreach($pvalue as $pkey => $pname)
                            <option value="{{$pkey}}" {{( ( $pkey == $riktu['tempatkejadian_idkabkota'])? 'selected=selected' : '' )}}> {{$pname}}</option>
                          @endforeach
                        @endif
                      @endforeach
                    @endif

                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="jumlah_terperiksa" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Terperiksa</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['jumlah_terperiksa']}}" id="jumlah_terperiksa" name="jumlah_terperiksa" type="number" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="jumlah_saksi" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Saksi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['jumlah_saksi']}}" id="jumlah_saksi" name="jumlah_saksi" type="number" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="kodebarangbukti" class="col-md-3 col-sm-3 col-xs-12 control-label">Barang Bukti</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="radio">
                  @if(count($barang_bukti))
                    @foreach($barang_bukti as $bkey=> $bvalue)
                      <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="{{$bkey}}" {{($riktu['kodebarangbukti'] == $bkey) ? 'checked=checked' : ''}} name="kodebarangbukti">
                        <span>{{$bvalue}}</span>
                      </label>
                    @endforeach
                  @endif

                </div>
                </div>
              </div>

              <div class="form-group">
                <label for="kodesumberinformasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Informasi</label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                  <div class="radio">
                  @if(count($informasi))
                    @foreach($informasi as $ikey=> $ivalue)
                      <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="{{$ikey}}" {{($riktu['kodesumberinformasi'] == $ikey) ? 'checked=checked' : ''}} name="kodesumberinformasi">
                        <span>{{$ivalue}}</span>
                      </label>
                    @endforeach
                  @endif

                </div>
                </div>
              </div>

              <div class="form-group">
                <label for="kriteria_perka" class="col-md-3 col-sm-3 col-xs-12 control-label">Kriteria &amp; perka</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$riktu['kriteria_perka']}}" id="kriteria_perka" name="kriteria_perka" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="file_hasil_pemeriksaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Pemeriksaan</label>
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
                        <input type="file" name="file_hasil_pemeriksaan"> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                      </div>
                    </div>
                     <span class="help-block white">

                        @if ($riktu['file_hasil_pemeriksaan'])
                            @php
                                $file = 'upload/IrtamaRiktu/'.$riktu['file_hasil_pemeriksaan'];
                            @endphp
                            @if(file_exists($file))
                                Lihat file : <a target="__blank" class="link_file" href="{{\Storage::url('IrtamaRiktu/'.$riktu['file_hasil_pemeriksaan'])}}"> {{$riktu['file_hasil_pemeriksaan']}} </a>
                            @endif
                        @endif
                    </span>
                  </div>
                </div>

                <div class="form-group">
                <label for="terbukti" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Pembuktian</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="radio">
                    @if(count($hasil_riktu))
                      @foreach ($hasil_riktu as $hkey => $hvalue)
                        <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" value="{{$hkey}}" {{( $riktu['terbukti'] == $hkey ? 'checked=checked' : '')}} name="terbukti">
                          <span>{{$hvalue}}</span>
                        </label>
                      @endforeach
                    @endif

                </div>
                </div>
              </div>

              </div>

              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
                    <a href="{{route('irtama_riktu')}}" class="btn btn-primary" type="button">BATAL</a>


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
