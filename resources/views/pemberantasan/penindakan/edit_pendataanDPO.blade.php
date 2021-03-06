@extends('layouts.base_layout')
@section('title', 'Ubah Daftar Pencarian Orang')

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
					<h2>Form Ubah Daftar Pencarian Orang Direktorat Penindakan dan Pengejaran</h2>
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

					<form action="{{url('/pemberantasan/dir_penindakan/update_pendataan_dpo')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
						{{ csrf_field() }}
						<input type="hidden" name="form_method" value="update">
						<input type="hidden" name="id" value="{{$dpo['id']}}">
						<div class="form-body">
							<div class="form-group">
								<label for="pelaksana" class="col-md-3 control-label">Pelaksana</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select name="id_instansi" id="id_instansi" class="form-control select2">
										<option value="">--List Pelaksana --</option>
										@foreach($instansi as $i)
										<option value="{{$i['id_instansi']}}" {{($dpo['id_instansi'] == $i['id_instansi']) ? "selected" : ""}}>{{$i['nm_instansi']}}</option>
										@endforeach
									</select>
									</div>
								</div>
								<div class="form-group">
									<label for="nomor_sprint_dpo" class="col-md-3 control-label">Nomor Surat Permintaan DPO</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="nomor_sprint_dpo" name="nomor_sprint_dpo" type="text" class="form-control" value="{{$dpo['nomor_sprint_dpo']}}">
									</div>
								</div>
								<hr>
								<h4>Identitas</h4>
								<div class="form-group">
									<label for="kode_jenisidentitas" class="col-md-3 control-label">Jenis Identitas</label>
									<div class="col-md-9">
										<label class="mt-radio col-md-3">  <input {{($dpo['kode_jenisidentitas'] == "KTP") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KTP" value="KTP" name="kode_jenisidentitas">
											<span>KTP</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenisidentitas'] == "SIM") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_SIM" value="SIM" name="kode_jenisidentitas">
											<span>SIM</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenisidentitas'] == "KITAS") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KITAS" value="KITAS" name="kode_jenisidentitas">
											<span>KITAS</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenisidentitas'] == "KITAP") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KITAP" value="KITAP" name="kode_jenisidentitas">
											<span>KITAP</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenisidentitas'] == "PASSPORT") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_PASSPORT" value="PASSPORT" name="kode_jenisidentitas">
											<span>Passport</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenisidentitas'] == "KARTU_PELAJAR") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KARTU_PELAJAR" value="KARTU_PELAJAR" name="kode_jenisidentitas">
											<span>Kartu Pelajar</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenisidentitas'] == "KARTU_MAHASISWA") ? "checked" : ""}} type="radio" id="kode_jenisidentitas_KARTU_MAHASISWA" value="KARTU_MAHASISWA" name="kode_jenisidentitas">
											<span>Kartu Mahasiswa</span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="no_identitas" class="col-md-3 control-label">No. Identitas</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="no_identitas" name="no_identitas" type="text" class="form-control" value="{{$dpo['no_identitas']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="nama" class="col-md-3 control-label">Nama Asli</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="nama" name="nama" type="text" class="form-control" value="{{$dpo['nama']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="nama_alias" class="col-md-3 control-label">Nama Alias</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="nama_alias" name="nama_alias" type="text" class="form-control" value="">
									</div>
								</div>
								<div class="form-group">
									<label for="alamat" class="col-md-3 control-label">Alamat KTP</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamat" name="alamat" type="text" class="form-control" value="{{$dpo['alamat']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="alamatktp_idprovinsi" class="col-md-3 control-label">alamatktp_idprovinsi</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatktp_idprovinsi" name="alamatktp_idprovinsi" type="text" class="form-control mask_number" value="{{$dpo['alamatktp_idprovinsi']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="alamatktp_idkabkota" class="col-md-3 control-label">alamatktp_idkabkota</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatktp_idkabkota" name="alamatktp_idkabkota" type="text" class="form-control" value="{{$dpo['alamatktp_idkabkota']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="alamatktp_kodepos" class="col-md-3 control-label">Kodepos Alamat KTP</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatktp_kodepos" name="alamatktp_kodepos" type="number" class="form-control numeric" onkeydown="numeric(event)" value="{{$dpo['alamatktp_kodepos']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="alamatdomisili" class="col-md-3 control-label">Alamat Domisili</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatdomisili" name="alamatdomisili" type="text" class="form-control" value="{{$dpo['alamatdomisili']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="alamatdomisili_idprovinsi" class="col-md-3 control-label">alamatdomisili_idprovinsi</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatdomisili_idprovinsi" name="alamatdomisili_idprovinsi" type="text" class="form-control mask_number" value="{{$dpo['alamatlainnya_idprovinsi']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="alamatdomisili_idkabkota" class="col-md-3 control-label">alamatdomisili_idkabkota</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatdomisili_idkabkota" name="alamatdomisili_idkabkota" type="text" class="form-control mask_number" value="{{$dpo['alamatdomisili_idkabkota']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="alamatdomisili_kodepos" class="col-md-3 control-label">Kodepos Alamat Domisili</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatdomisili_kodepos" name="alamatdomisili_kodepos" type="number" class="form-control numeric" onkeydown="numeric(event)" value="{{$dpo['alamatdomisili_kodepos']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="alamatlainnya" class="col-md-3 control-label">Alamat Lainnya</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatlainnya" name="alamatlainnya" type="text" class="form-control" value="{{$dpo['alamatlainnya']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="alamatlainnya_idprovinsi" class="col-md-3 control-label">alamatlainnya_idprovinsi</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatlainnya_idprovinsi" name="alamatlainnya_idprovinsi" type="text" class="form-control mask_number" value="{{$dpo['alamatlainnya_idprovinsi']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="alamatlainnya_idkabkota" class="col-md-3 control-label">alamatlainnya_idkabkota</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatlainnya_idkabkota" name="alamatlainnya_idkabkota" type="text" class="form-control mask_number" value="{{$dpo['alamatlainnya_idkabkota']}}">
									</div>
								</div>
								<div class="form-group">
									<label for="alamatlainnya_kodepos" class="col-md-3 control-label">Kodepos Alamat Lainnya</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="alamatlainnya_kodepos" name="alamatlainnya_kodepos" type="text" class="form-control" value="{{$dpo['alamatlainnya_kodepos']}}">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3">Jenis Kelamin</label>
									<div class="col-md-9">
										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenis_kelamin'] == "P") ? "checked" : ""}} type="radio" id="kode_jenis_kelamin_P" value="P" name="kode_jenis_kelamin">
											<span>Perempuan</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_jenis_kelamin'] == "L") ? "checked" : ""}} type="radio" id="kode_jenis_kelamin_L" value="L" name="kode_jenis_kelamin">
											<span> Laki-Laki</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="tempat_lahir" class="col-md-3 control-label">Tempat Lahir</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="tempat_lahir" name="tempat_lahir" type="text" class="form-control" value="{{$dpo['tempat_lahir']}}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Tanggal Lahir</label>
									<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
										<input type='text' name="tanggal_lahir" value="{{ \Carbon\Carbon::parse($dpo['tanggal_lahir'])->format('d/m/Y') }}" class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label for="usia" class="col-md-3 control-label">Umur Tersangka</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="usia" name="usia" type="text" class="form-control mask_number" value="{{$dpo['usia']}}">
									</div>
								</div>
								<div class="form-group hide">
									<label for="kode_kelompok_usia" class="col-md-3 control-label">kode_kelompok_usia</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="kode_kelompok_usia" name="kode_kelompok_usia" type="text" class="form-control" value="{{$dpo['kode_kelompok_usia']}}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Pendidikan Akhir</label>
									<div class="col-md-9">
										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pendidikan_akhir'] == "SD") ? "checked" : ""}} type="radio" value="SD" id="kode_pendidikan_akhir_SD" name="kode_pendidikan_akhir">
											<span>SD</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pendidikan_akhir'] == "SLTP") ? "checked" : ""}} type="radio" value="SLTP" id="kode_pendidikan_akhir_SLTP" name="kode_pendidikan_akhir">
											<span>SLTP</span>
										</label>

										<label class="mt-radio col-md-3">  <input {{($dpo['kode_pendidikan_akhir'] == "SLTA") ? "checked" : ""}} type="radio" value="SLTA" id="kode_pendidikan_akhir_SLTA" name="kode_pendidikan_akhir">
											<span>SLTA</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pendidikan_akhir'] == "PT") ? "checked" : ""}} type="radio" value="PT" id="kode_pendidikan_akhir_PT" name="kode_pendidikan_akhir">
											<span>Perguruan Tinggi</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pendidikan_akhir'] == "PTSKL") ? "checked" : ""}} type="radio" value="PTSKL" id="kode_pendidikan_akhir_PTSKL" name="kode_pendidikan_akhir">
											<span>Putus Sekolah</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pendidikan_akhir'] == "TDSKL") ? "checked" : ""}} type="radio" value="TDSKL" id="kode_pendidikan_akhir_TDSKL" name="kode_pendidikan_akhir">
											<span>Tidak Sekolah</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3">Pekerjaan</label>
									<div class="col-md-9">

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "TNI") ? "checked" : ""}} id="kode_pekerjaan_TNI" type="radio" value="TNI" name="kode_pekerjaan">
											<span>TNI</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "TANI") ? "checked" : ""}} id="kode_pekerjaan_TANI" type="radio" value="TANI" name="kode_pekerjaan">
											<span>TANI</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "PNS") ? "checked" : ""}} id="kode_pekerjaan_PNS" type="radio" value="PNS" name="kode_pekerjaan">
											<span>PNS</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "SWT") ? "checked" : ""}} id="kode_pekerjaan_SWT" type="radio" value="SWT" name="kode_pekerjaan">
											<span>Swasta</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "WST") ? "checked" : ""}} id="kode_pekerjaan_WST" type="radio" value="WST" name="kode_pekerjaan">
											<span>Wiraswasta</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "MHS") ? "checked" : ""}} id="kode_pekerjaan_MHS" type="radio" value="MHS" name="kode_pekerjaan">
											<span>Mahasiswa</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "BRH") ? "checked" : ""}} id="kode_pekerjaan_BRH" type="radio" value="BRH" name="kode_pekerjaan">
											<span>Buruh</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "PNG") ? "checked" : ""}} id="kode_pekerjaan_PNG" type="radio" value="PNG" name="kode_pekerjaan">
											<span>Pengangguran</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "POL") ? "checked" : ""}} id="kode_pekerjaan_POL" type="radio" value="POL" name="kode_pekerjaan">
											<span> Polisi</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_pekerjaan'] == "PLJ") ? "checked" : ""}} id="kode_pekerjaan_PLJ" type="radio" value="PLJ" name="kode_pekerjaan">
											<span>Pelajar</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3">Kode Warga Negara</label>
									<div class="col-md-9">
										<label class="mt-radio col-md-3"> <input {{($dpo['kode_warga_negara'] == "WNI") ? "checked" : ""}} type="radio" value="WNI" id="kode_warga_negara_WNI" name="kode_warga_negara">
											<span>WNI</span>
										</label>

										<label class="mt-radio col-md-3"> <input {{($dpo['kode_warga_negara'] == "WNA") ? "checked" : ""}} type="radio" value="WNA" id="kode_warga_negara_WNA" name="kode_warga_negara">
											<span>WNA</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label for="kode_peran_tersangka" class="col-md-3 control-label">Peran Tersangka</label>
									<div class="col-md-9">
										<label class="mt-radio col-md-12"> <input {{($dpo['kode_peran_tersangka'] == "PRODUKSI") ? "checked" : ""}} type="radio" id="kode_peran_PRODUKSI" value="PRODUKSI" name="kode_peran_tersangka">
											<span>Produksi</span>
										</label>

										<label class="mt-radio col-md-12"> <input {{($dpo['kode_peran_tersangka'] == "DISTRIBUSI") ? "checked" : ""}} type="radio" id="kode_peran_DISTRIBUSI" value="DISTRIBUSI" name="kode_peran_tersangka">
											<span>Distribusi</span>
										</label>

										<label class="mt-radio col-md-12"> <input {{($dpo['kode_peran_tersangka'] == "KULTIVASI") ? "checked" : ""}} type="radio" id="kode_peran_KULTIVASI" value="KULTIVASI" name="kode_peran_tersangka">
											<span>Kultivasi</span>
										</label>

										<label class="mt-radio col-md-12"> <input {{($dpo['kode_peran_tersangka'] == "KONSUMSI") ? "checked" : ""}} type="radio" id="kode_peran_KONSUMSI" value="KONSUMSI" name="kode_peran_tersangka">
											<span>Konsumsi</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3">Asal Negara</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode_negara" class="form-control select2">
											<option value="" >-- Pilih Negara --</option>
											@foreach($negara as $n)
												@if($n['kode']==$dpo['kode_negara'])
												<option value="{{$n['kode']}}" selected='selected'>{{$n['nama_negara']}}</option>
												@else
												<option value="{{$n['kode']}}">{{$n['nama_negara']}}</option>
												@endif
											@endforeach
										</select>

										<span class="help-block"></span>
									</div>
								</div>

								<hr>
								<h4>Ciri-ciri Fisik</h4>
								<div class="form-group">
									<label for="fisik_tinggi_badan" class="col-md-3 control-label">Tinggi Badan</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="fisik_tinggi_badan" name="fisik_tinggi_badan" type="text" class="form-control" onKeydown="numeric_only(event,this)" value="{{$dpo['fisik_tinggi_badan']}}">
										<span class="help-block c-white">Cm</span>
									</div>
								</div>

								<div class="form-group">
									<label for="fisik_warna_kulit" class="col-md-3 control-label">Warna Kulit</label>
									<div class="col-md-8">

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_warna_kulit'] == "Hitam") ? "checked" : ""}} type="radio" value="Hitam" id="fisik_warna_kulit_Hitam" name="fisik_warna_kulit"><span> Hitam</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_warna_kulit'] == "Sawo Matang") ? "checked" : ""}} type="radio" value="Sawo Matang" id="fisik_warna_kulit_Sawo Matang" name="fisik_warna_kulit">
											<span>Sawo Matang</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_warna_kulit'] == "Kuning") ? "checked" : ""}} type="radio" value="Kuning" id="fisik_warna_kulit_Kuning" name="fisik_warna_kulit">
											<span>Kuning</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_warna_kulit'] == "Putih") ? "checked" : ""}} type="radio" value="Putih" id="fisik_warna_kulit_Putih" name="fisik_warna_kulit">
											<span>Putih</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_warna_kulit'] == "Albino") ? "checked" : ""}} type="radio" value="Albino" id="fisik_warna_kulit_Albino" name="fisik_warna_kulit">
											<span>Albino</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="fisik_perawakan" class="col-md-3 control-label">Bentuk Tubuh</label>
									<div class="col-md-8">
										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_perawakan'] == "Kurus") ? "checked" : ""}} type="radio" value="Kurus" id="fisik_perawakan_Kurus" name="fisik_perawakan">
											<span>Kurus</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_perawakan'] == "Sedang") ? "checked" : ""}} type="radio" value="Sedang" id="fisik_perawakan_Sedang" name="fisik_perawakan">
											<span>Sedang</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_perawakan'] == "Kekar") ? "checked" : ""}} type="radio" value="Kekar" id="fisik_perawakan_Kekar" name="fisik_perawakan">
											<span>Kekar</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_perawakan'] == "Gemuk") ? "checked" : ""}} type="radio" value="Gemuk" id="fisik_perawakan_Gemuk" name="fisik_perawakan">
											<span>Gemuk</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="fisik_lohat_bahasa" class="col-md-3 control-label">Bentuk Kepala</label>
									<div class="col-md-8">
										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_lohat_bahasa'] == "Datar Atas") ? "checked" : ""}} type="radio" value="Datar Atas" id="fisik_lohat_bahasa_Datar Atas" name="fisik_lohat_bahasa">
											<span>Datar Atas</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_lohat_bahasa'] == "Lurus Belakang") ? "checked" : ""}} type="radio" value="Lurus Belakang" id="fisik_lohat_bahasa_Lurus Belakang" name="fisik_lohat_bahasa">
											<span>Lurus Belakang</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_lohat_bahasa'] == "Nonjol Atas") ? "checked" : ""}} type="radio" value="Nonjol Atas" id="fisik_lohat_bahasa_Nonjol Atas" name="fisik_lohat_bahasa">
											<span>Nonjol Atas</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_lohat_bahasa'] == "Nonjol Belakang") ? "checked" : ""}} type="radio" value="Nonjol Belakang" id="fisik_lohat_bahasa_Nonjol Belakang" name="fisik_lohat_bahasa">
											<span>Nonjol Belakang</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="fisik_warna_rambut" class="col-md-3 control-label">Warna Rambut</label>
									<div class="col-md-8">

										<label class="mt-radio col-md-4">  <input {{($dpo['fisik_warna_rambut'] == "Hitam") ? "checked" : ""}} type="radio" value="Hitam" id="fisik_warna_rambut_Hitam" name="fisik_warna_rambut">
											<span>Hitam</span>
										</label>

										<label class="mt-radio col-md-4">  <input {{($dpo['fisik_warna_rambut'] == "Cokelat") ? "checked" : ""}} type="radio" value="Cokelat" id="fisik_warna_rambut_Cokelat" name="fisik_warna_rambut">
											<span>Cokelat</span>
										</label>

										<label class="mt-radio col-md-4">  <input {{($dpo['fisik_warna_rambut'] == "Merah") ? "checked" : ""}} type="radio" value="Merah" id="fisik_warna_rambut_Merah" name="fisik_warna_rambut">
											<span>Merah</span>
										</label>

										<label class="mt-radio col-md-4">  <input {{($dpo['fisik_warna_rambut'] == "Putih") ? "checked" : ""}} type="radio" value="Putih" id="fisik_warna_rambut_Putih" name="fisik_warna_rambut">
											<span>Putih</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_warna_rambut'] == "Pirang") ? "checked" : ""}} type="radio" value="Pirang" id="fisik_warna_rambut_Pirang" name="fisik_warna_rambut">
											<span>Pirang</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="fisik_tipikal_rambut" class="col-md-3 control-label">Jenis Rambut</label>
									<div class="col-md-8">
										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_tipikal_rambut'] == "Lurus") ? "checked" : ""}} type="radio" value="Lurus" id="fisik_tipikal_rambut_Lurus" name="fisik_tipikal_rambut">
											<span>Lurus</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_tipikal_rambut'] == "Berombak") ? "checked" : ""}} type="radio" value="Berombak" id="fisik_tipikal_rambut_Berombak" name="fisik_tipikal_rambut">
											<span>Berombak</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_tipikal_rambut'] == "Keriting") ? "checked" : ""}} type="radio" value="Keriting" id="fisik_tipikal_rambut_Keriting" name="fisik_tipikal_rambut">
											<span>Keriting</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="fisik_bentuk_wajah" class="col-md-3 control-label">Bentuk Muka</label>
									<div class="col-md-8">

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_bentuk_wajah'] == "Lonjong") ? "checked" : ""}} type="radio" value="Lonjong" id="fisik_bentuk_wajah_Lonjong" name="fisik_bentuk_wajah">
											<span>Lonjong</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_bentuk_wajah'] == "Bulat") ? "checked" : ""}} type="radio" value="Bulat" id="fisik_bentuk_wajah_Bulat" name="fisik_bentuk_wajah">
											<span>Bulat</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_bentuk_wajah'] == "Segi Tiga") ? "checked" : ""}} type="radio" value="Segi Tiga" id="fisik_bentuk_wajah_Segi Tiga" name="fisik_bentuk_wajah">
											<span>Segi Tiga</span>
										</label>

										<label class="mt-radio col-md-4"> <input {{($dpo['fisik_bentuk_wajah'] == "Segi Empat") ? "checked" : ""}} type="radio" value="Segi Empat" id="fisik_bentuk_wajah_Segi Empat" name="fisik_bentuk_wajah">
											<span>Segi Empat</span>
										</label>
										<span class="help-block"></span>
									</div>
								</div>

								<hr>
								<h4>Foto</h4>
								<div class="form-group">
									<label for="hasil_yang_dicapai" class="col-md-3 control-label">Tampak Depan</label>
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
													@if (!empty($dpo['file_foto_tampak_depan']))
															lihat file : <a style="color:yellow" href="{{\Storage::url('penindakanDanPengejaran/'.$dpo['file_foto_tampak_depan'])}}">{{$dpo['file_foto_tampak_depan']}}</a>
													@endif
											</span>
										</div>
									</div>
									<div class="form-group">
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
														<input type="file" name="file_foto_tampak_sampingkanan"> </span>
														<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
													</div>
												</div>
												<span class="help-block" style="color:white">
														@if (!empty($dpo['file_foto_tampak_sampingkanan']))
																lihat file : <a style="color:yellow" href="{{\Storage::url('penindakanDanPengejaran/'.$dpo['file_foto_tampak_sampingkanan'])}}">{{$dpo['file_foto_tampak_sampingkanan']}}</a>
														@endif
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
															<input type="file" name="file_foto_tampak_sampingkiri"> </span>
															<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
														</div>
													</div>
													<span class="help-block" style="color:white">
															@if (!empty($dpo['file_foto_tampak_sampingkiri']))
																	lihat file : <a style="color:yellow" href="{{\Storage::url('penindakanDanPengejaran/'.$dpo['file_foto_tampak_sampingkiri'])}}">{{$dpo['file_foto_tampak_sampingkiri']}}</a>
															@endif
													</span>
												</div>
											</div>
											<hr>
										</div>
										<div class="form-actions fluid">
											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
													<button type="submit" class="btn btn-success">KIRIM</button>
													<a href="{{url('/pemberantasan/dir_penindakan/pendataan_dpo')}}" class="btn btn-primary" type="button">BATAL</a>
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
