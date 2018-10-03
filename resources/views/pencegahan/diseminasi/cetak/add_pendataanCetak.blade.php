@extends('layouts.base_layout')
@section('title', 'Tambah Data Kegiatan Media Cetak')

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
          <h2>Form Tambah Data Kegiatan Media Cetak Direktorat Diseminasi</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form action="{{url('pencegahan/dir_diseminasi/input_pendataan_cetak/')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="meta_media" class="col-md-3 col-sm-3 col-xs-12 control-label">Dasar Kegiatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                  <label class="mt-radio col-md-4 col-sm-4 col-xs-12"> <input required type="radio" value="sprint" name="dasar_kegiatan" onclick="dasarkegiatan(this)">
                    <span>Surat Perintah</span>
                  </label>

                  <div class="col-md-8 col-sm-8 col-xs-12 hide no_sprint">
                    <input value="" id="no_sprint" name="no_sprint" type="text" placeholder="No. Surat Perintah" class="form-control mask_number">
                  </div>
                  <div class="clearfix"></div>
                  <label class="mt-radio col-md-4 col-sm-4 col-xs-12"> <input type="radio" value="spk" name="dasar_kegiatan" onclick="dasarkegiatan(this)">
                    <span>Surat Perintah Kerja</span>
                  </label>

                  <div class="col-md-8 col-sm-8 col-xs-12 hide no_spk">
                      <input value="" id="no_spk" name="no_spk" type="text" placeholder="No. Surat Perintah Kerja" class="form-control mask_number">
                  </div>
                </div>
              </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Periode Publish</label>
                <div class='col-md-6 col-sm-6 col-xs-12'>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <label for="tgl_mulai" class="col-md-12 col-sm-12 col-xs-12 text-left">Mulai</label>
                                <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal-publish'>
                                  <input type='text' name="waktu_publish" class="form-control" required/>
                                  <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <label for="tgl_selesai" class="col-md-12 col-sm-12 col-xs-12 text-left"> Selesai</label>
                                <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal-publish'>
                                  <input type='text' name="selesai_publish" class="form-control" required/>
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
                <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="idpelaksana" required id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                    {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                    @foreach($instansi as $in)
                    <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi Penyiaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="materi" name="materi" class="form-control"></textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="meta_media" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Media Cetak</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  @foreach($media['data'] as $md)
                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12">
                      <input type="radio" value="{{$md['value_media']}}" data-id="{{$md['id']}}" data-nama="{{$md['nama_media']}}" name="kode_jenis_media">
                      <span>{{$md['nama_media']}}</span>
                    </label>
                  @endforeach
                </div>
              </div>

              <div class="form-group" id="jenisMediaCetak">
              </div>

              <div class="form-group">
                <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Media Cetak</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="nama_media" name="nama_media" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Cetak</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="jumlah_cetak" name="jumlah_cetak" type="text" class="form-control numeric" onKeydown="numeric(event)">
                </div>
              </div>

              <div class="form-group">
                <label for="durasi_penyiaran" class="col-md-3 control-label">Titik/Lokasi/Moda Penempatan Media Luar Ruang</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="durasi_penyiaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Titik Penempatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2 selectKabupaten" name="lokasi_kegiatan_idkabkota">
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab as $prop => $val)
                    <optgroup label="{{$prop}}">
                      @foreach($val as $id => $kab)
                      <option value="{{$id}}">{{$kab}}</option>
                      @endforeach
                    </optgroup>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="kodesumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="mt-radio-list" id='buttons'>
                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                      <span>Dipa</span>
                    </label>
                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
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
                <label for="hasil_yang_dicapai" class="col-md-3 col-sm-3 col-xs-12 control-label">File Laporan</label>
                <div class="col-md-5 col-sm-5 col-xs-12">
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
                <span class="help-block">
                </span>
            </div>
        </div>
    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_cetak')}}" class="btn btn-primary" type="button">BATAL</a>
            </div>
        </div>
    </div>
</form>

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
