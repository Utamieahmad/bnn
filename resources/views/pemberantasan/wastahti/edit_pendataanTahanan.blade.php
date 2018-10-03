@extends('layouts.base_layout')
@section('title', 'Ubah Pendataan Tahanan')

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
					<h2>Form Ubah Pendataan Tahanan di BNN dan BNNP Direktorat Wastahti</h2>
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

					<form action="{{url('/pemberantasan/dir_wastahti/input_pendataan_tahanan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
						{{ csrf_field() }}
						<input type="hidden" name="id" value="{{$tahanan['tahanan_id']}}">
						<input type="hidden" name="kode_jenistahanan" value="{{$tahanan['kode_jenistahanan']}}">
						<!-- <input type="hidden" name="nomor_kasus" value="{{$tahanan['nomor_kasus']}}"> -->
						<div class="form-body">
							<h4>Data Penanganan Kasus</h4>
							<div class="form-group">
								<label for="kode_jenistahanan" class="col-md-3 control-label">Jenis Tahanan</label>
								<div class="col-md-9">
									<label class="mt-radio col-md-3">  <input {{($tahanan['kode_jenistahanan'] == "TAHANAN_BNN") ? "checked" : ""}} type="radio" value="TAHANAN_BNN" name="kode_jenistahanan" id="kode_jenistahanan_TAHANAN_BNN" disabled=""> <span>Tahanan BNN</span>
									</label>
									<label class="mt-radio col-md-4">  <input {{($tahanan['kode_jenistahanan'] == "TAHANAN_TITIPAN") ? "checked" : ""}} type="radio" value="TAHANAN_TITIPAN" name="kode_jenistahanan" id="kode_jenistahanan_TAHANAN_TITIPAN" disabled=""> <span>Tahanan Titipan Instansi Lain</span>
									</label>
								</div>
							</div>

							<!-- <div class="form-group">
							<label for="kode_jenistahanan" class="col-md-3 control-label">Jenis Tahanan</label>
							<div class="col-md-9">
							<label class="mt-radio col-md-3"> Tahanan BNN <input checked="" type="radio" id="kode_jenistahanan_TAHANAN_BNN" value="TAHANAN_BNN" name="kode_jenistahanan" disabled="">
							<span></span>
						</label>

						<label class="mt-radio col-md-3"> Tahanan Titipan Instansi Lain <input type="radio" id="kode_jenistahanan_TAHANAN_TITIPAN" value="TAHANAN_TITIPAN" name="kode_jenistahanan" disabled="">
						<span></span>
					</label>
				</div>
			</div> -->

			<div class="form-group">
				<label for="nomor_kasus_display" class="col-md-3 control-label">No. LKN/No. Surat Permohonan Penitipan</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input disabled="" id="nomor_kasus_display" name="nomor_kasus" type="text" class="form-control" value="{{$tahanan['nomor_kasus']}}">
				</div>
			</div>
			<div class="form-group hide">
				<label for="id_kasus" class="col-md-3 control-label">id_kasus</label>
				<div class="col-md-4">
					<input id="id_kasus" name="id_kasus" type="text" class="form-control mask_number" value="{{$tahanan['id_kasus']}}">
				</div>
			</div>
			<hr>
			<h4>Identitas Tahanan</h4>
			<div class="form-group">
				<label for="kode_jenisidentitas" class="col-md-3 control-label">Jenis Identitas</label>
				<div class="col-md-9">
					<label class="mt-radio col-md-3">  <input {{($tahanan['kode_jenisidentitas'] == "KTP") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KTP" value="KTP" name="kode_jenisidentitas">
						<span>KTP</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenisidentitas'] == "DIM") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_SIM" value="SIM" name="kode_jenisidentitas">
						<span>SIM</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenisidentitas'] == "KITAS") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KITAS" value="KITAS" name="kode_jenisidentitas">
						<span>KITAS</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenisidentitas'] == "KITAP") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KITAP" value="KITAP" name="kode_jenisidentitas">
						<span>KITAP</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenisidentitas'] == "PASSPORT") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_PASSPORT" value="PASSPORT" name="kode_jenisidentitas">
						<span>Passport</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenisidentitas'] == "KARTU_PELAJAR") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KARTU_PELAJAR" value="KARTU_PELAJAR" name="kode_jenisidentitas">
						<span>Kartu Pelajar</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenisidentitas'] == "KARTU_MAHASISWA") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KARTU_MAHASISWA" value="KARTU_MAHASISWA" name="kode_jenisidentitas">
						<span>Kartu Mahasiswa</span>
					</label>
				</div>
			</div>

			<div class="form-group">
				<label for="no_identitas" class="col-md-3 control-label">No. Identitas</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="no_identitas" name="no_identitas" type="text" class="form-control" value="{{$tahanan['no_identitas']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="tersangka_nama" class="col-md-3 control-label">Nama Asli</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="tersangka_nama" name="tersangka_nama" type="text" class="form-control" value="{{$tahanan['tersangka_nama']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="tersangka_nama_alias" class="col-md-3 control-label">Nama Alias</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="tersangka_nama_alias" name="tersangka_nama_alias" type="text" class="form-control" value="{{$tahanan['tersangka_nama_alias']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="tersangka_alamat" class="col-md-3 control-label">Alamat KTP</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="tersangka_alamat" name="tersangka_alamat" type="text" class="form-control" value="{{$tahanan['tersangka_alamat']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="alamatktp_idprovinsi" class="col-md-3 control-label">alamatktp_idprovinsi</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatktp_idprovinsi" name="alamatktp_idprovinsi" type="text" class="form-control mask_number" value="{{$tahanan['alamatktp_idprovinsi']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="alamatktp_idkabkota" class="col-md-3 control-label">alamatktp_idkabkota</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatktp_idkabkota" name="alamatktp_idkabkota" type="text" class="form-control" value="{{$tahanan['alamatktp_idkabkota']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="alamatktp_kodepos" class="col-md-3 control-label">Kodepos Alamat KTP</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatktp_kodepos" name="alamatktp_kodepos" type="text" class="form-control" value="{{$tahanan['alamatktp_kodepos']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="alamatdomisili" class="col-md-3 control-label">Alamat Domisili</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatdomisili" name="alamatdomisili" type="text" class="form-control" value="{{$tahanan['alamatdomisili']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="alamatdomisili_idprovinsi" class="col-md-3 control-label">alamatdomisili_idprovinsi</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatdomisili_idprovinsi" name="alamatdomisili_idprovinsi" type="text" class="form-control mask_number" value="{{$tahanan['alamatdomisili_idprovinsi']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="alamatdomisili_idkabkota" class="col-md-3 control-label">alamatdomisili_idkabkota</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatdomisili_idkabkota" name="alamatdomisili_idkabkota" type="text" class="form-control mask_number" value="{{$tahanan['alamatdomisili_idkabkota']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="alamatdomisili_kodepos" class="col-md-3 control-label">Kodepos Alamat Domisili</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatdomisili_kodepos" name="alamatdomisili_kodepos" type="text" class="form-control" value="{{$tahanan['alamatdomisili_kodepos']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="alamatlainnya" class="col-md-3 control-label">Alamat Lainnya</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatlainnya" name="alamatlainnya" type="text" class="form-control" value="{{$tahanan['alamatlainnya']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="alamatlainnya_idprovinsi" class="col-md-3 control-label">alamatlainnya_idprovinsi</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatlainnya_idprovinsi" name="alamatlainnya_idprovinsi" type="text" class="form-control mask_number" value="{{$tahanan['alamatlainnya_idprovinsi']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="alamatlainnya_idkabkota" class="col-md-3 control-label">alamatlainnya_idkabkota</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatlainnya_idkabkota" name="alamatlainnya_idkabkota" type="text" class="form-control mask_number" value="{{$tahanan['alamatlainnya_idkabkota']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="alamatlainnya_kodepos" class="col-md-3 control-label">Kodepos Alamat Lainnya</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="alamatlainnya_kodepos" name="alamatlainnya_kodepos" type="text" class="form-control" value="{{$tahanan['alamatlainnya_kodepos']}}">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Jenis Kelamin</label>
				<div class="col-md-9">
					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenis_kelamin'] == "P") ? "checked" : ""}} type="radio" id="kode_jenis_kelamin_P" value="P" name="kode_jenis_kelamin">
						<span>Perempuan</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_jenis_kelamin'] == "L") ? "checked" : ""}} type="radio" id="kode_jenis_kelamin_L" value="L" name="kode_jenis_kelamin"><span> Laki-Laki</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
				<label for="tersangka_tempat_lahir" class="col-md-3 control-label">Tempat Lahir</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="tersangka_tempat_lahir" name="tersangka_tempat_lahir" type="text" class="form-control" value="{{$tahanan['tersangka_tempat_lahir']}}">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Tanggal Lahir</label>
				<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
					<input type='text' name="tersangka_tanggal_lahir" value="{{ \Carbon\Carbon::parse($tahanan['tersangka_tanggal_lahir'])->format('d/m/Y') }}" class="form-control" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>

			<div class="form-group">
				<label for="tersangka_usia" class="col-md-3 control-label">Umur Tersangka</label>
				<div class="col-md-4">
					<input id="tersangka_usia" name="tersangka_usia" type="text" class="form-control mask_number" value="{{$tahanan['tersangka_usia']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="kode_kelompok_usia" class="col-md-3 control-label">kode_kelompok_usia</label>
				<div class="col-md-4">
					<input id="kode_kelompok_usia" name="kode_kelompok_usia" type="text" class="form-control" value="{{$tahanan['kode_kelompok_usia']}}">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Pendidikan Akhir</label>
				<div class="col-md-9">
					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pendidikan_akhir'] == "SD") ? "checked" : ""}} type="radio" value="SD" id="kode_pendidikan_akhir_SD" name="kode_pendidikan_akhir">
						<span>SD</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pendidikan_akhir'] == "SLTP") ? "checked" : ""}} type="radio" value="SLTP" id="kode_pendidikan_akhir_SLTP" name="kode_pendidikan_akhir">
						<span>SLTP</span>
					</label>

					<label class="mt-radio col-md-3">  <input {{($tahanan['kode_pendidikan_akhir'] == "SLTA") ? "checked" : ""}} type="radio" value="SLTA" id="kode_pendidikan_akhir_SLTA" name="kode_pendidikan_akhir">
						<span>SLTA</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pendidikan_akhir'] == "PT") ? "checked" : ""}} type="radio" value="PT" id="kode_pendidikan_akhir_PT" name="kode_pendidikan_akhir">
						<span>Perguruan Tinggi</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pendidikan_akhir'] == "PTSKL") ? "checked" : ""}} type="radio" value="PTSKL" id="kode_pendidikan_akhir_PTSKL" name="kode_pendidikan_akhir">
						<span>Putus Sekolah</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pendidikan_akhir'] == "TDSKL") ? "checked" : ""}} type="radio" value="TDSKL" id="kode_pendidikan_akhir_TDSKL" name="kode_pendidikan_akhir">
						<span>Tidak Sekolah</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Pekerjaan</label>
				<div class="col-md-9">

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "TNI") ? "checked" : ""}} id="kode_pekerjaan_TNI" type="radio" value="TNI" name="kode_pekerjaan">
						<span>TNI</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "TANI") ? "checked" : ""}} id="kode_pekerjaan_TANI" type="radio" value="TANI" name="kode_pekerjaan">
						<span>TANI</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "PNS") ? "checked" : ""}} id="kode_pekerjaan_PNS" type="radio" value="PNS" name="kode_pekerjaan">
						<span>PNS</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "SWT") ? "checked" : ""}} id="kode_pekerjaan_SWT" type="radio" value="SWT" name="kode_pekerjaan">
						<span>Swasta</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "WST") ? "checked" : ""}} id="kode_pekerjaan_WST" type="radio" value="WST" name="kode_pekerjaan">
						<span>Wiraswasta</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "MHS") ? "checked" : ""}} id="kode_pekerjaan_MHS" type="radio" value="MHS" name="kode_pekerjaan">
						<span>Mahasiswa</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "BRH") ? "checked" : ""}} id="kode_pekerjaan_BRH" type="radio" value="BRH" name="kode_pekerjaan">
						<span>Buruh</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "PNG") ? "checked" : ""}} id="kode_pekerjaan_PNG" type="radio" value="PNG" name="kode_pekerjaan">
						<span>Pengangguran</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "POL") ? "checked" : ""}} id="kode_pekerjaan_POL" type="radio" value="POL" name="kode_pekerjaan">
						<span> Polisi</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_pekerjaan'] == "PLJ") ? "checked" : ""}} id="kode_pekerjaan_PLJ" type="radio" value="PLJ" name="kode_pekerjaan">
						<span>Pelajar</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Warga Negara</label>
				<div class="col-md-9">
					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_warga_negara'] == "WNI") ? "checked" : ""}} type="radio" value="WNI" id="kode_warga_negara_WNI" name="kode_warga_negara">
						<span>WNI</span>
					</label>

					<label class="mt-radio col-md-3"> <input {{($tahanan['kode_warga_negara'] == "WNA") ? "checked" : ""}} type="radio" value="WNA" id="kode_warga_negara_WNA" name="kode_warga_negara">
						<span>WNA</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
				<label for="kode_peran_tersangka" class="col-md-3 control-label">Peran</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<select name="kode_peran_tersangka" class="form-control">
						<option {{($tahanan['kode_peran_tersangka'] == "1") ? "selected" : ""}} value="1">Kultivasi</option>
						<option {{($tahanan['kode_peran_tersangka'] == "2") ? "selected" : ""}} value="2">Produksi</option>
						<option {{($tahanan['kode_peran_tersangka'] == "3") ? "selected" : ""}} value="3">Distribusi</option>
						<option {{($tahanan['kode_peran_tersangka'] == "4") ? "selected" : ""}} value="4" selected="selected">Konsumsi</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Negara</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<select name="kode_negara" class="form-control select2" placeholder="Pilih Negara">
						<option value=""> Pilih Negara </option>
						@foreach($negara as $n)
						<option value="{{$n['kode']}}">{{$n['nama_negara']}}</option>
						@endforeach
					</select>

					<span class="help-block"></span>
				</div>
			</div>

			<hr>
			<h4>Formulir AK-23</h4>
			<div class="form-group hide">
				<label for="fisik_tinggi_badan" class="col-md-3 control-label">Tinggi Badan</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="fisik_tinggi_badan" name="fisik_tinggi_badan" type="text" class="form-control mask-number" value="{{$tahanan['fisik_tinggi_badan']}}">
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					Cm
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_berat_badan" class="col-md-3 control-label">Berat Badan</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="fisik_berat_badan" name="fisik_berat_badan" type="text" class="form-control mask-number" value="{{$tahanan['fisik_berat_badan']}}">
					<!-- <span class="help-block c-white">Kg</span> -->
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					Kg
				</div>
			</div>

			<div class="form-group hide">
				<label for="fisik_warna_kulit" class="col-md-3 control-label">Warna Kulit</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_warna_kulit'] == "Hitam") ? "checked" : ""}} type="radio" value="Hitam" id="fisik_warna_kulit_Hitam" name="fisik_warna_kulit"><span> Hitam</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_warna_kulit'] == "Sawo Matang") ? "checked" : ""}} type="radio" value="Sawo Matang" id="fisik_warna_kulit_Sawo Matang" name="fisik_warna_kulit">
						<span> Sawo Matang</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_warna_kulit'] == "Kuning") ? "checked" : ""}} type="radio" value="Kuning" id="fisik_warna_kulit_Kuning" name="fisik_warna_kulit">
						<span>Kuning</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_warna_kulit'] == "Putih") ? "checked" : ""}} type="radio" value="Putih" id="fisik_warna_kulit_Putih" name="fisik_warna_kulit"><span> Putih</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_warna_kulit'] == "Albino") ? "checked" : ""}} type="radio" value="Albino" id="fisik_warna_kulit_Albino" name="fisik_warna_kulit">
						<span>Albino</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group hide">
				<label for="fisik_perawakan" class="col-md-3 control-label">Bentuk Tubuh</label>
				<div class="col-md-8">
					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_perawakan'] == "Kurus") ? "checked" : ""}} type="radio" value="Kurus" id="fisik_perawakan_Kurus" name="fisik_perawakan">
						<span>Kurus</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_perawakan'] == "Sedang") ? "checked" : ""}} type="radio" value="Sedang" id="fisik_perawakan_Sedang" name="fisik_perawakan">
						<span>Sedang</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_perawakan'] == "Kekar") ? "checked" : ""}} type="radio" value="Kekar" id="fisik_perawakan_Kekar" name="fisik_perawakan">
						<span>Kekar</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_perawakan'] == "Gemuk") ? "checked" : ""}} type="radio" value="Gemuk" id="fisik_perawakan_Gemuk" name="fisik_perawakan">
						<span>Gemuk</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group hide">
				<label for="fisik_lohat_bahasa" class="col-md-3 control-label">Bentuk Kepala</label>
				<div class="col-md-8">
					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_lohat_bahasa'] == "Datar Atas") ? "checked" : ""}} type="radio" value="Datar Atas" id="fisik_lohat_bahasa_Datar Atas" name="fisik_lohat_bahasa">
						<span>Datar Atas</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_lohat_bahasa'] == "Lurus Belakang") ? "checked" : ""}} type="radio" value="Lurus Belakang" id="fisik_lohat_bahasa_Lurus Belakang" name="fisik_lohat_bahasa">
						<span>Lurus Belakang</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_lohat_bahasa'] == "Nonjol Atas") ? "checked" : ""}} type="radio" value="Nonjol Atas" id="fisik_lohat_bahasa_Nonjol Atas" name="fisik_lohat_bahasa">
						<span>Nonjol Atas</span>
					</label>

					<label class="mt-radio col-md-4"> <input {{($tahanan['fisik_lohat_bahasa'] == "Nonjol Belakang") ? "checked" : ""}} type="radio" value="Nonjol Belakang" id="fisik_lohat_bahasa_Nonjol Belakang" name="fisik_lohat_bahasa">
						<span>Nonjol Belakang</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_warna_rambut" class="col-md-3 control-label">Warna Rambut</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4">  <input type="radio" {{($tahanan['fisik_warna_rambut'] == "Hitam") ? "checked" : ""}} value="Hitam" id="fisik_warna_rambut_Hitam" name="fisik_warna_rambut">
						<span>Hitam</span>
					</label>

					<label class="mt-radio col-md-4">  <input type="radio" {{($tahanan['fisik_warna_rambut'] == "Coklat") ? "checked" : ""}} value="Cokelat" id="fisik_warna_rambut_Cokelat" name="fisik_warna_rambut">
						<span>Cokelat</span>
					</label>

					<label class="mt-radio col-md-4">  <input type="radio" {{($tahanan['fisik_warna_rambut'] == "Merah") ? "checked" : ""}} value="Merah" id="fisik_warna_rambut_Merah" name="fisik_warna_rambut">
						<span>Merah</span>
					</label>

					<label class="mt-radio col-md-4">  <input type="radio" {{($tahanan['fisik_warna_rambut'] == "Putih") ? "checked" : ""}} value="Putih" id="fisik_warna_rambut_Putih" name="fisik_warna_rambut">
						<span>Putih</span>
					</label>

					<label class="mt-radio col-md-4">  <input type="radio" {{($tahanan['fisik_warna_rambut'] == "Pirang") ? "checked" : ""}} value="Pirang" id="fisik_warna_rambut_Pirang" name="fisik_warna_rambut">
						<span>Pirang</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_tipikal_rambut" class="col-md-3 control-label">Jenis Rambut</label>
				<div class="col-md-8">
					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_tipikal_rambut'] == "Lurus") ? "checked" : ""}} value="Lurus" id="fisik_tipikal_rambut_Lurus" name="fisik_tipikal_rambut">
						<span>Lurus</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_tipikal_rambut'] == "Berombak") ? "checked" : ""}} value="Berombak" id="fisik_tipikal_rambut_Berombak" name="fisik_tipikal_rambut">
						<span>Berombak</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_tipikal_rambut'] == "Keriting") ? "checked" : ""}} value="Keriting" id="fisik_tipikal_rambut_Keriting" name="fisik_tipikal_rambut">
						<span>Keriting</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_bentuk_wajah" class="col-md-3 control-label">Bentuk Muka</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_wajah'] == "Lonjong") ? "checked" : ""}} value="Lonjong" id="fisik_bentuk_wajah_Lonjong" name="fisik_bentuk_wajah">
						<span>Lonjong</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_wajah'] == "Bulat") ? "checked" : ""}} value="Bulat" id="fisik_bentuk_wajah_Bulat" name="fisik_bentuk_wajah">
						<span>Bulat</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_wajah'] == "Segi Tiga") ? "checked" : ""}} value="Segi Tiga" id="fisik_bentuk_wajah_Segi Tiga" name="fisik_bentuk_wajah">
						<span>Segi Tiga</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_wajah'] == "Segi Empat") ? "checked" : ""}} value="Segi Empat" id="fisik_bentuk_wajah_Segi Empat" name="fisik_bentuk_wajah">
						<span>Segi Empat</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_suku_ras" class="col-md-3 control-label">Dahi</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_suku_ras'] == "Lengkung") ? "checked" : ""}} value="Lengkung" id="fisik_suku_ras_Lengkung" name="fisik_suku_ras">
						<span>Lengkung</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_suku_ras'] == "Lurus") ? "checked" : ""}} value="Lurus" id="fisik_suku_ras_Lurus" name="fisik_suku_ras">
						<span>Lurus</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_suku_ras'] == "Menonjol") ? "checked" : ""}} value="Menonjol" id="fisik_suku_ras_Menonjol" name="fisik_suku_ras">
						<span>Menonjol</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_suku_ras'] == "Miring Ke Dalam") ? "checked" : ""}} value="Miring Ke Dalam" id="fisik_suku_ras_Miring Ke Dalam" name="fisik_suku_ras">
						<span>Miring Ke Dalam</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_bentuk_mata" class="col-md-3 control-label">Warna Mata</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_mata'] == "Hitam") ? "checked" : ""}} value="Hitam" id="fisik_bentuk_mata_Hitam" name="fisik_bentuk_mata">
						<span>Hitam</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_mata'] == "Coklat") ? "checked" : ""}} value="Cokelat" id="fisik_bentuk_mata_Cokelat" name="fisik_bentuk_mata">
						<span>Cokelat</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bentuk_mata'] == "Biru") ? "checked" : ""}} value="Biru" id="fisik_bentuk_mata_Biru" name="fisik_bentuk_mata">
						<span>Biru</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_kelainan_mata" class="col-md-3 control-label">Kelainan Pada Mata</label>
				<div class="col-md-8">
					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_kelainan_mata'] == "Juling Kanan") ? "checked" : ""}} value="Juling Kanan" id="fisik_kelainan_mata_Juling Kanan" name="fisik_kelainan_mata">
						<span>Juling Kanan</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_kelainan_mata'] == "Juling Kiri") ? "checked" : ""}} value="Juling Kiri" id="fisik_kelainan_mata_Juling Kiri" name="fisik_kelainan_mata">
						<span>Juling Kiri</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_kelainan_mata'] == "Berkaca Mata") ? "checked" : ""}} value="Berkaca Mata" id="fisik_kelainan_mata_Berkaca Mata" name="fisik_kelainan_mata">
						<span>Berkaca Mata</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_kelainan_mata'] == "Mata Kanan Rusak") ? "checked" : ""}} value="Mata Kanan Rusak" id="fisik_kelainan_mata_Mata Kanan Rusak" name="fisik_kelainan_mata">
						<span>Mata Kanan Rusak</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_kelainan_mata'] == "Mata Kiri Rusak") ? "checked" : ""}} value="Mata Kiri Rusak" id="fisik_kelainan_mata_Mata Kiri Rusak" name="fisik_kelainan_mata">
						<span>Mata Kiri Rusak</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_kelainan_mata'] == "Kedua Mata Rusak") ? "checked" : ""}} value="Kedua Mata Rusak" id="fisik_kelainan_mata_Kedua Mata Rusak" name="fisik_kelainan_mata">
						<span>Kedua Mata Rusak</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_hidung" class="col-md-3 control-label">Hidung</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_hidung'] == "Lengkung") ? "checked" : ""}} value="Lengkung" id="fisik_hidung_Lengkung" name="fisik_hidung">
						<span>Lengkung</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_hidung'] == "Lurus") ? "checked" : ""}} value="Lurus" id="fisik_hidung_Lurus" name="fisik_hidung">
						<span>Lurus</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_hidung'] == "Bergelombang") ? "checked" : ""}} value="Bergelombang" id="fisik_hidung_Bergelombang" name="fisik_hidung">
						<span>Bergelombang</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_hidung'] == "Berkait") ? "checked" : ""}} value="Berkait" id="fisik_hidung_Berkait" name="fisik_hidung">
						<span>Berkait</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_hidung'] == "Bulat Kecil") ? "checked" : ""}} value="Bulat Kecil" id="fisik_hidung_Bulat Kecil" name="fisik_hidung">
						<span>Bulat Kecil</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_hidung'] == "Bulat Besar") ? "checked" : ""}} value="Bulat Besar" id="fisik_hidung_Bulat Besar" name="fisik_hidung">
						<span>Bulat Besar</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_bibir" class="col-md-3 control-label">Bibir</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bibir'] == "Sedang/Biasa") ? "checked" : ""}} value="Sedang/Biasa" id="fisik_bibir_Sedang/Biasa" name="fisik_bibir">
						<span>Sedang/Biasa</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bibir'] == "Tipis") ? "checked" : ""}} value="Tipis" id="fisik_bibir_Tipis" name="fisik_bibir">
						<span>Tipis</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bibir'] == "Tebal") ? "checked" : ""}} value="Tebal" id="fisik_bibir_Tebal" name="fisik_bibir">
						<span>Tebal</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bibir'] == "Menonjol") ? "checked" : ""}} value="Menonjol" id="fisik_bibir_Menonjol" name="fisik_bibir">
						<span>Menonjol</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bibir'] == "Menonjol Ke Atas") ? "checked" : ""}} value="Menonjol Ke Atas" id="fisik_bibir_Menonjol Ke Atas" name="fisik_bibir">
						<span>Menonjol Ke Atas</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_bibir'] == "Menonjol Ke Bawah") ? "checked" : ""}} value="Menonjol Ke Bawah" id="fisik_bibir_Menonjol Ke Bawah" name="fisik_bibir">
						<span>Menonjol Ke Bawah</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_gigi" class="col-md-3 control-label">Gigi</label>
				<div class="col-md-8">

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Teratur") ? "checked" : ""}} value="Teratur" id="fisik_gigi_Teratur" name="fisik_gigi">
						<span>Teratur</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Tidak Teratur") ? "checked" : ""}} value="Tidak Teratur" id="fisik_gigi_Tidak Teratur" name="fisik_gigi">
						<span>Tidak Teratur</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Atas Nonjol") ? "checked" : ""}} value="Atas Nonjol" id="fisik_gigi_Atas Nonjol" name="fisik_gigi">
						<span>Atas Nonjol</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Bawah Nonjol") ? "checked" : ""}} value="Bawah Nonjol" id="fisik_gigi_Bawah Nonjol" name="fisik_gigi">
						<span>Bawah Nonjol</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Rusak") ? "checked" : ""}} value="Rusak" id="fisik_gigi_Rusak" name="fisik_gigi">
						<span>Rusak</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Palsu") ? "checked" : ""}} value="Palsu" id="fisik_gigi_Palsu" name="fisik_gigi">
						<span>Palsu</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_gigi'] == "Ompong") ? "checked" : ""}} value="Ompong" id="fisik_gigi_Ompong" name="fisik_gigi">
						<span>Ompong</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_dagu" class="col-md-3 control-label">Dagu</label>
				<div class="col-md-8">
					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_dagu'] == "Tajam") ? "checked" : ""}} value="Tajam" id="fisik_dagu_Tajam" name="fisik_dagu">
						<span>Tajam</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_dagu'] == "Berat") ? "checked" : ""}} value="Berat" id="fisik_dagu_Berat" name="fisik_dagu">
						<span>Berat</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_dagu'] == "Menonjol") ? "checked" : ""}} value="Menonjol" id="fisik_dagu_Menonjol" name="fisik_dagu">
						<span>Menonjol</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_dagu'] == "Miring Ke Dalam") ? "checked" : ""}} value="Miring Ke Dalam" id="fisik_dagu_Miring Ke Dalam" name="fisik_dagu">
						<span>Miring Ke Dalam</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_telinga" class="col-md-3 control-label">Telinga</label>
				<div class="col-md-8">
					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_telinga'] == "Bulat") ? "checked" : ""}} value="Bulat" id="fisik_telinga_Bulat" name="fisik_telinga">
						<span>Bulat</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_telinga'] == "Segi Tiga") ? "checked" : ""}} value="Segi Tiga" id="fisik_telinga_Segi Tiga" name="fisik_telinga">
						<span>Segi Tiga</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_telinga'] == "Segi Empat") ? "checked" : ""}} value="Segi Empat" id="fisik_telinga_Segi Empat" name="fisik_telinga">
						<span>Segi Empat</span>
					</label>

					<label class="mt-radio col-md-4"> <input type="radio" {{($tahanan['fisik_telinga'] == "Anting Nempel") ? "checked" : ""}} value="Anting Nempel" id="fisik_telinga_Anting Nempel" name="fisik_telinga">
						<span>Anting Nempel</span>
					</label>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_tatto" class="col-md-3 control-label">Tatto</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="fisik_tatto" name="fisik_tatto" type="text" class="form-control mask-number" value="{{$tahanan['fisik_tatto']}}">
				</div>
			</div>
			<div class="form-group hide">
				<label for="fisik_cacat" class="col-md-3 control-label">Dipotong &amp; Cacat</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="fisik_cacat" name="fisik_cacat" type="text" class="form-control mask-number" value="{{$tahanan['fisik_cacat']}}">
				</div>
			</div>

			<div class="form-group hide">
				<label for="kode_pasal" class="col-md-3 control-label">Kode Pasal</label>
				<div class="'col-md-6 col-sm-6 col-xs-12">
					<input id="kode_pasal" name="kode_pasal" type="text" class="form-control mask-number" value="{{$tahanan['kode_pasal']}}">
				</div>
			</div>

			<div class="form-group">
				<label for="document_ak23" class="col-md-3 control-label">Formulir AK 23</label>
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
								<input type="file" name="document_ak23"> </span>
								<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
							</div>
						</div>
						<span class="help-block" style="color:white">
							@if (!empty($tahanan['document_ak23']))
								lihat file : <a style="color:yellow" href="{{\Storage::url('wastahtiTahananDocument/'.$tahanan['document_ak23'])}}">{{$tahanan['document_ak23']}}</a>
							@endif
						</span>
					</div>
				</div>

			<hr>
			<h4>Foto</h4>
			<div class="form-group">
				<label for="file_foto_tampak_depan" class="col-md-3 control-label">Tampak Depan</label>
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
							<input type="file" name="file_foto_tampak_depan"> </span>
							<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
						</div>
					</div>
					<span class="help-block" style="color:white">
						@if (!empty($tahanan['file_foto_tampak_depan']))
							lihat file : <a style="color:yellow" href="{{\Storage::url('wastahtiTahananFoto/'.$tahanan['file_foto_tampak_depan'])}}">{{$tahanan['file_foto_tampak_depan']}}</a>
						@endif
					</span>
				</div>
			</div>
		<!-- <div class="form-group">
			<label for="hasil_yang_dicapai" class="col-md-3 control-label">Tampak Samping Kanan</label>
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
							<input type="file" name="file_foto_tampaksampingkanan"> </span>
							<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
						</div>
					</div>
					<span class="help-block">
					</span>
				</div>
			</div>
			<div class="form-group">
				<label for="hasil_yang_dicapai" class="col-md-3 control-label">Tampak Samping Kiri</label>
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
								<input type="file" name="file_foto_tampaksampingkiri"> </span>
								<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
							</div>
						</div>
						<span class="help-block">
						</span>
				</div>
			</div> -->
				<!-- <hr>
				<h4>Sidik Jari</h4>
			<div class="form-group">
				<label for="hasil_yang_dicapai" class="col-md-3 control-label">Jempol Kanan</label>
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
								<input type="file" name="file_sidikjari_kanan01"> </span>
								<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
							</div>
						</div>
						<span class="help-block">
						</span>
					</div>
				</div>
				<div class="form-group">
					<label for="hasil_yang_dicapai" class="col-md-3 control-label">Telunjuk Kanan</label>
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
									<input type="file" name="file_sidikjari_kanan02"> </span>
									<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
								</div>
							</div>
							<span class="help-block">
							</span>
						</div>
					</div>
					<div class="form-group">
						<label for="hasil_yang_dicapai" class="col-md-3 control-label">Jari Tengah Kanan</label>
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
										<input type="file" name="file_sidikjari_kanan03"> </span>
										<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
								</div>
							</div>
							<span class="help-block">
							</span>
						</div>
					</div>
					<div class="form-group">
						<label for="hasil_yang_dicapai" class="col-md-3 control-label">Jari Manis Kanan</label>
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
										<input type="file" name="file_sidikjari_kanan04"> </span>
										<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
									</div>
								</div>
								<span class="help-block">
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="hasil_yang_dicapai" class="col-md-3 control-label">Klingking Kanan</label>
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
											<input type="file" name="file_sidikjari_kanan05"> </span>
											<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
										</div>
									</div>
									<span class="help-block">
									</span>
							</div>
							</div>
							<div class="form-group">
								<label for="hasil_yang_dicapai" class="col-md-3 control-label">Jempol Kiri</label>
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
												<input type="file" name="file_sidikjari_kiri01"> </span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
											</div>
										</div>
										<span class="help-block">
										</span>
								</div>
							</div>
							<div class="form-group">
								<label for="hasil_yang_dicapai" class="col-md-3 control-label">Telunjuk Kiri</label>
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
												<input type="file" name="file_sidikjari_kiri02"> </span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
										</div>
									</div>
									<span class="help-block">
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="hasil_yang_dicapai" class="col-md-3 control-label">Jari Tengah Kiri</label>
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
												<input type="file" name="file_sidikjari_kiri03"> </span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
											</div>
									</div>
									<span class="help-block">
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="hasil_yang_dicapai" class="col-md-3 control-label">Jari Manis Kiri</label>
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
												<input type="file" name="file_sidikjari_kiri04"> </span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
											</div>
										</div>
										<span class="help-block">
										</span>
								</div>
							</div>
							<div class="form-group">
								<label for="hasil_yang_dicapai" class="col-md-3 control-label">Kelingking Kiri</label>
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
												<input type="file" name="file_sidikjari_kiri05"> </span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
											</div>
										</div>
										<span class="help-block">
										</span>
									</div>
							</div> -->
							<hr>
							<h4>Informasi Penahanan</h4>
							<div class="form-group">
								<label for="pasal_dikenakan" class="col-md-3 control-label">Pasal Penahanan</label>
								<div class="'col-md-6 col-sm-6 col-xs-12">
									<input id="pasal_dikenakan" name="pasal_dikenakan" type="text" class="form-control mask_number" value="{{$tahanan['pasal_dikenakan']}}">
								</div>
							</div>
							<div class="form-group">
								<label for="tgl_masuk" class="col-md-3 control-label">Tanggal Masuk</label>
								<div class='col-md-6 col-sm-6 col-xs-12 input-group date tgl_tahan_start'>
									<input type='text' id="tgl_masuk" name="tgl_masuk" value="{{ \Carbon\Carbon::parse($tahanan['tgl_masuk'])->format('d/m/Y') }}" class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								</div>
								<div class="form-group">
									<label for="masa_berlaku_penahanan" class="col-md-3 control-label">Tanggal Keluar Penahanan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12 input-group date tgl_tahan_end">
										<input id="tgl_keluar" name="tgl_keluar" type="text" class="form-control mask_number" value="{{ \Carbon\Carbon::parse($tahanan['tgl_keluar'])->format('d/m/Y') }}" >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label for="nomor_sp_penahanan" class="col-md-3 control-label">Lama Penahanan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="lama_penahanan" name="lama_penahanan" type="text" class="form-control"  value="{{$tahanan['lama_penahanan']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="nomor_sp_penahanan" class="col-md-3 control-label">No. SP Penahanan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="nomor_sp_penahanan" name="nomor_sp_penahanan" type="text" class="form-control" value="{{$tahanan['nomor_sp_penahanan']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="asal_tahanan" class="col-md-3 control-label">Asal Tahanan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="asal_tahanan" name="asal_tahanan" type="text" class="form-control" value="{{$tahanan['asal_tahanan']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="nama_penyidik" class="col-md-3 control-label">Nama Penyidik</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="nama_penyidik" name="nama_penyidik" type="text" class="form-control" value="{{$tahanan['nama_penyidik']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="nomor_sp_perpanjangan_penahanan_01" class="col-md-3 control-label">No. SP Perpanjangan Penahanan dari Kejaksaan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="nomor_sp_perpanjangan_penahanan_01" name="nomor_sp_perpanjangan_penahanan_01" type="text" class="form-control" value="{{$tahanan['nomor_sp_perpanjangan_penahanan_01']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="nomor_sp_perpanjangan_penahanan_02" class="col-md-3 control-label">No. SP Perpanjangan Penahanan dari Pengadilan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="nomor_sp_perpanjangan_penahanan_02" name="nomor_sp_perpanjangan_penahanan_02" type="text" class="form-control" value="{{$tahanan['nomor_sp_perpanjangan_penahanan_02']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="nomor_sp_perpanjangan_penahanan_03" class="col-md-3 control-label">No. SP Perpanjangan Penahanan dari Pengadilan</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="nomor_sp_perpanjangan_penahanan_03" name="nomor_sp_perpanjangan_penahanan_03" type="text" class="form-control" value="{{$tahanan['nomor_sp_perpanjangan_penahanan_03']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="tahanan_id" class="col-md-3 control-label">tahanan_id</label>
									<div class="'col-md-6 col-sm-6 col-xs-12">
										<input id="tahanan_id" name="tahanan_id" type="text" class="form-control" value="{{$tahanan['tahanan_id']}}">
									</div>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success">KIRIM</button>
										<a href="{{route('pendataan_tahanan')}}" class="btn btn-primary" type="button">BATAL</a>
									</div>
								</div>
							</div>
						</form>
					</div>

				</form>
			</div>
		</div>
	</div>

</div>
</div>
</div>
@endsection
