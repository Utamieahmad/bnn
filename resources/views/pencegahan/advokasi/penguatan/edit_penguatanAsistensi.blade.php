@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Penguatan Asistensi')

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
                    <h2>Form Ubah Data Kegiatan Penguatan Asistensi Direktorat Advokasi</h2>
                    <div class="clearfix"></div>
                </div>
        <div class="x_content">
                    <br />
            <form action="{{URL('/pencegahan/dir_advokasi/update_penguatan_asistensi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
    {{-- <input type="hidden" name="form_method" value="create"> --}}
		{{ csrf_field() }}
		<input type="hidden" name="id" value="{{$id}}">
    <div class="form-body">

      				<div class="form-group">
      						<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
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
                  <label for="jenis_asistensi" class="col-md-3 control-label">Jenis Asistensi</label>
                  <div class="col-md-4">
                      <div class="mt-radio-list">
                          <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Bang_Wawan') ? 'checked="checked"':""}} value="Bang_Wawan" name="jenis_kegiatan">
                              <span>Bang Wawan</span>
                          </label>

                          <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['jenis_kegiatan'] == 'Penguatan') ? 'checked="checked"':""}} value="Penguatan" name="jenis_kegiatan">
                          <span>Penguatan</span>
                          </label>
                      </div>
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
      						<label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran</label>
      						<div class="col-md-4">
      								<div class="mt-radio-list">
      										<label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'INSTITUSI_PEMERINTAH') ? 'checked="checked"':""}} value="INSTITUSI_PEMERINTAH" name="sasaran">
      										<span>Institusi Pemerintah</span>
      										</label>

      										<label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'INSTITUSI_SWASTA') ? 'checked="checked"':""}} value="INSTITUSI_SWASTA" name="sasaran">
      										<span>Institusi Swasta</span>
      										</label>

      										<label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'LINGKUNGAN_PENDIDIKAN') ? 'checked="checked"':""}} value="LINGKUNGAN_PENDIDIKAN" name="sasaran">
      										<span>Lingkungan Pendidikan</span>
      										</label>

      										<label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'LINGKUNGAN_MASYARAKAT') ? 'checked="checked"':""}} value="LINGKUNGAN_MASYARAKAT" name="sasaran">
      										<span>Lingkungan Masyarakat</span>
      										</label>
      								</div>
      						</div>
      				</div>

      				<div class="form-group">
      						<label for="instansi" class="col-md-3 control-label">Instansi</label>
      						<div class="col-md-8">
      								<div class="mt-repeater">
      										<div data-repeater-list="group-c">
      											@foreach(json_decode($data_detail['data']['meta_instansi'],true) as $r1 => $c1)
      												<div data-repeater-item="" class="mt-repeater-item">
      														<div class="row mt-repeater-row">
      																<div class="col-md-3">
      																		<label class="control-label">Nama Instansi</label>
      																		<input name="group-c[{{$r1}}][list_nama_instansi]" value="{{$c1['list_nama_instansi']}}" type="text" class="form-control"> </div>
      																<!--div class="col-md-5">
      																		<label class="control-label">Alamat Instansi</label>
      																		<input name="group-c[{{$r1}}][list_alamat_instansi]" value="{{$c1['list_alamat_instansi']}}" type="text" class="form-control"> </div-->
      																<div class="col-md-3">
      																		<label class="control-label">Jumlah Peserta</label>
      																		<input name="group-c[{{$r1}}][list_jumlah_peserta]" value="{{$c1['list_jumlah_peserta']}}" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
      																<div class="col-md-1">
      																		<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
      																				<i class="fa fa-close"></i>
      																		</a>
      																</div>
      														</div>
      												</div>
      											@endforeach
      										</div>
      										<a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
      												<i class="fa fa-plus"></i> Tambah Instansi</a>
      								</div>
      						</div>
      				</div>

      				<div class="form-group">
      						<label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Lokasi Kegiatan </label>
      						<div class="col-md-6 col-sm-6 col-xs-12">
      								<input value="{{$data_detail['data']['lokasi_kegiatan']}}" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
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
      											<option value="{{$key}}" {{($key == $data_detail['data']['lokasi_kegiatan_idkabkota']) ? 'selected="selected"':""}} >{{$val}}</option>
      											@endforeach
      										</optgroup>
      										@endforeach
      								</select>
      						</div>
      				</div>

      				<div class="form-group">
      						<label for="narasumber" class="col-md-3 col-sm-3 col-xs-12 control-label">Narasumber</label>
      						<div class="col-md-6 col-sm-6 col-xs-12">
      								<input value="{{$data_detail['data']['narasumber']}}" id="narasumber" name="narasumber" type="text" class="form-control">
      						</div>
      				</div>

      				<div class="form-group">
      						<label for="panitia_monev" class="col-md-3 col-sm-3 col-xs-12 control-label">Panitia</label>
      						<div class="col-md-6 col-sm-6 col-xs-12">
      								<input value="{{$data_detail['data']['panitia_monev']}}" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
      						</div>
      				</div>

      				<div class="form-group">
      						<label for="materi" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi yang disampaikan</label>
      						<div class="col-md-6 col-sm-6 col-xs-12">
      								<input value="{{$data_detail['data']['materi']}}" id="materi" name="materi" type="text" class="form-control">
      						</div>
      				</div>

      				<div class="form-group">
      						<label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
      						<div class="col-md-4">
      								<div class="mt-radio-list">
      									<label class="mt-radio col-md-9"> <input disabled {{($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
      									<span>Dipa</span>
      									</label>
      									<label class="mt-radio col-md-9"> <input disabled {{($data_detail['data']['kodesumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
      									<span>Non Dipa</span>
      									</label>
      								</div>
      						</div>
      				</div>

      				@if($data_detail['data']['anggaran_id'] != '')
      				<div class="form-group" id="DetailAnggaran" >
      						<label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
      						<input type="hidden" name="asatker_code" id="kodeSatker" value="">
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
      				@endif

      				<div class="form-group">
      						<label for="hasil_yang_dicapai" class="col-md-3 control-label">hasil yang dicapai</label>
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
      										@if (!empty($data_detail['data']['file_upload']))
      												lihat file : <a style="color:yellow" href="{{\Storage::url('AdvokasiAsistensiPenguatan/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
      										@endif
      								</span>
      						</div>
      				</div>
    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('penguatan_asistensi')}}" class="btn btn-primary" type="button">BATAL</a>
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
