@extends('layouts.base_layout')
@section('title', 'Dir Peran Serta Masyarakat : Edit Data Kegiatan Ormas/LSM Anti Narkoba')

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
                    <h2>Form Ubah Data Kegiatan Ormas/LSM Anti Narkoba Direktorat Peran Serta Masyarakat</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/pemberdayaan/dir_masyarakat/update_psm_ormas')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">

        <div class="form-group">
            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
              @if($data_detail['data']['tgl_pelaksanaan'] != "kosong")
              <input type='text' name="tgl_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" />
              @else
              <input type="text" name="tgl_pelaksanaan" value="" class="form-control" />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
            <div class="col-md-6">
                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}" {{($in['id_instansi'] == $data_detail['data']['idpelaksana']) ? 'selected="selected"':""}} >{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ormas" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Ormas/LSM</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['meta_instansi']}}" id="meta_instansi" name="meta_instansi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_ormas" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Ormas/LSM</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['panitia_monev']}}" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['lokasi_kegiatan']}}" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan_idkabkota" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control select2 " name="lokasi_kegiatan_idkabkota">
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
            <label for="bentuk_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Bentuk Kegiatan</label>
            <div class="col-md-8">
                <div class="mt-radio-list">
                    <label class="mt-radio col-md-4"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Sosialisasi') ? 'checked="checked"':""}} value="Sosialisasi" name="jenis_kegiatan">
                    <span>Sosialisasi</span>
                    </label>

                    <label class="mt-radio col-md-5"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Kampanye') ? 'checked="checked"':""}} value="Kampanye" name="jenis_kegiatan">
                    <span>Kampanye</span>
                    </label>

                    <label class="mt-radio col-md-4"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Turnamen_Olahraga') ? 'checked="checked"':""}} value="Turnamen_Olahraga" name="jenis_kegiatan">
                    <span>Turnamen / Olahraga</span>
                    </label>

                    <label class="mt-radio col-md-4"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Pengembangan_Kapasitas') ? 'checked="checked"':""}} value="Pengembangan_Kapasitas" name="jenis_kegiatan">
                    <span>Pengembangan Kapasitas</span>
                    </label>

                    <label class="mt-radio col-md-5"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Pelatihan_Penggiat_Anti_Narkoba') ? 'checked="checked"':""}} value="Pelatihan_Penggiat_Anti_Narkoba" name="jenis_kegiatan">
                    <span>Pelatiahan Penggiat Anti Narkoba</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
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
            <label for="jumlah_peserta" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['jumlah_peserta']}}" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control col-md-7 col-xs-12 numeric" onkeydown="numeric(event)">
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('psm_ormas')}}" class="btn btn-primary" type="button">BATAL</a>
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
