@extends('layouts.base_layout')
@section('title', 'Tambah Data Pengembangan Kapasitas')

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
                    <h2>Form Tambah Data Pengembangan Kapasitas Direktorat Peran Serta Masyarakat</h2>
                    <div class="clearfix"></div>
                </div>
    <div class="x_content">
                    <br />
    <form action="{{URL('/pemberdayaan/dir_masyarakat/input_pendataan_anti_narkoba')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
        {{ csrf_field() }}
        <div class="form-body">

        <div class="form-group">
            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Periode Pelaksanaan</label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
                                <input type='text' name="tgl_pelaksanaan" class="form-control" value="" required/>
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
                                <input type='text' name="tgl_selesai" class="form-control" value="" required/>
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
            <div class="col-md-6">
                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required>
                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>


        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control select2 " name="lokasi_kegiatan_idkabkota">
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
            <label for="alamat_lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Lokasi Kegiatan </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <!-- <div class="form-group">
            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="jenis_kegiatan" name="jenis_kegiatan" type="text" class="form-control">
            </div>
        </div> -->
        <div class="form-group">
            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control select2" name="jenis_kegiatan">
                    <option value=""> -- Pilih Kegiatan --</option>
                    @if(isset($jenis_kegiatan_antinarkoba))
                        @if(count($jenis_kegiatan_antinarkoba))
                            @foreach($jenis_kegiatan_antinarkoba as $akey => $avalue)
                                <option value="{{$akey}}"> {{$avalue}}</option>
                            @endforeach
                        @endif
                    @endif
                </select>
                <!-- <input value="{{-- $data_detail['data']['jenis_kegiatan'] --}}" id="jenis_kegiatan" name="jenis_kegiatan" type="text" class="form-control"> -->
            </div>
        </div>

        <div class="form-group">
            <label for="jumlah_anggota_penggiat" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Anggota Penggiat</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
            </div>
        </div>

        <!-- <div class="form-group">
            <label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="materi" name="materi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="narasumber" name="narasumber" type="text" class="form-control">
            </div>
        </div> -->
        <div class="form-group m-t-20">
            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="materi">
                        <div data-repeater-item="" class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Narasumber</label>
                                    <input name="group-materi[0][narasumber]" value="" type="text" class="form-control" > </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="row">
                                        <label class="control-label">Judul Materi</label>
                                        <textarea name="group-materi[0][materi]" class="form-control"> </textarea>
                                    </div>
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
                        <i class="fa fa-plus"></i> Tambah Materi</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="asal_penggiat" class="col-md-3 col-sm-3 col-xs-12 control-label">Asal Penggiat Anti Narkotika</label>
            <div class="col-md-4 radio">
                <div class="mt-radio-list">
                    <label class="mt-radio col-md-9"> <input type="radio" value="INSTITUSI_PEMERINTAH" name="sasaran">
                    <span>Institusi Pemerintah</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" value="INSTITUSI_SWASTA" name="sasaran">
                    <span>Institusi Swasta</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" value="LINGKUNGAN_PENDIDIKAN" name="sasaran">
                    <span>Lingkungan Pendidikan</span>
                    </label>

                    <label class="mt-radio col-md-9"> <input type="radio" value="LINGKUNGAN_MASYARAKAT" name="sasaran">
                    <span>Lingkungan Masyarakat</span>
                    </label>
                </div>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_anti_narkoba')}}" class="btn btn-primary" type="button">BATAL</a>
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
