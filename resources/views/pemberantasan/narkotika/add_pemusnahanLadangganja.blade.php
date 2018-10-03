@extends('layouts.base_layout')

@section('title', 'Tambah Pemusnahan Tanaman Narkotika')
@section('content')
<div class="right_col" role="main">
	<div class="m-t-40">
		<div class="page-title">
			<div class="">
				{!! (isset($breadcrumps) ? $breadcrumps : '' )!!}
				<!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="title_right">
		</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Form Tambah Pendataan Pemusnahan Ladang Tanaman Narkotika Direktorat Narkotika</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<br />
					<form action="{{URL('/pemberantasan/dir_narkotika/input_pendataan_pemusnahan_ladangganja')}}" data-parsley-validate method="post" class="form-horizontal form-label-left" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >No. Surat Perintah Penyelidikan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="sprint" class="form-control col-md-7 col-xs-12" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal Penyelidikan</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
								<input type='text' name="tgl_penyelidikan" class="form-control" required/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi - Kabupaten/Kota</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2" name="kota_kabupaten">
									<option value="">-- Pilih Kabupaten -- </option>
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
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Kecamatan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="kecamatan" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Kelurahan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="kelurahan" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Desa/Dusun</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="desa" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Koordinat Latitude</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="koordinat_lat" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Koordinat Longitude</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="koordinat_lot" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
            				<label for="luas_lahan_ganja" class="col-md-3 col-sm-3 col-xs-12 control-label">Luas Lahan Tanaman Narkotika</label>
				            <div class="col-md-6 col-sm-6 col-xs-12">
				                <input value="" id="luas_lahan_ganja" name="luas_lahan_ganja" type="number" class="form-control mask_number" >
				            </div>
				            <span class="help-block white">m²/Hektar</span>
				        </div>

						<h4>Pemusnahan</h4>

				        <div class="form-group">
				            <label for="nomor_sprint_pemusnahan" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah Pemusnahan</label>
				            <div class="col-md-6 col-sm-6 col-xs-12">
				                <input value="" id="nomor_sprint_pemusnahan" name="nomor_sprint_pemusnahan" type="text" class="form-control" >
				            </div>
				        </div>

						<div class="form-group">
							<label for="tgl_pemusnahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pemusnahan</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
								<input type='text' name="tgl_pemusnahan" class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>

				        <div class="form-group">
				            <label for="luas_lahan_ganja_dimusnahkan" class="col-md-3 col-sm-3 col-xs-12 control-label">Luas Lahan Tanaman Narkotika yang Di Musnahkan</label>
				            <div class="col-md-6 col-sm-6 col-xs-12">
				                <input value="" id="luas_lahan_ganja_dimusnahkan" name="luas_lahan_ganja_dimusnahkan" type="number" class="form-control mask_number" >
				            </div>
				            <span class="help-block white">m²/Hektar</span>
				        </div>

						<div class="form-group" enctype="multipart/form-data">
							<label for="hasil_yang_dicapai" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Laporan Pelaksanaan Tugas</label>
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
											<input type="hidden" value="" name="file_laporan_kegiatan">
											<input type="file" name="file_laporan_kegiatan">
										</span>
										<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
									</div>
								</div>
								<span class="help-block">
								</span>
							</div>
						</div>


						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_pemusnahan_ladangganja')}}" class="btn btn-primary" type="button">BATAL</a>
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
