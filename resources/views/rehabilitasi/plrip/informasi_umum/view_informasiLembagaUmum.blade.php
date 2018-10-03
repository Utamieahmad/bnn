@extends('layouts.base_layout')
@section('title', 'Dir PLRIP : Lihat Informasi Lebaga Umum')

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
					<h2>{{ ($title2) ? $title2 : Form}}</h2>
					
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<br />
					<form  data-parsley-validate class="form-horizontal form-label-left text-left">

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" > Nama Lembaga </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['nama']) ? $data['nama'] : ''}}"  class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" > Legalitas Lembaga</label>
							
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" > <li> Nama Notaris </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['legalitas_lembaga_nama_notaris']) ? $data['legalitas_lembaga_nama_notaris'] : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" > <li> No. Akta Notaris </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['legalitas_lembaga_noakta_notaris']) ? $data['legalitas_lembaga_noakta_notaris'] : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>No. Ijin Operasional</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['legalitas_no_ijin_operasional']) ? $data['legalitas_no_ijin_operasional'] : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>
							<ul class="order-dash">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li><i class="fa fa-minus"></i>Instansi Pemberi Ijin</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['legalitas_lembaga_pemberi_ijin']) ? $data['legalitas_lembaga_pemberi_ijin'] : ''}}"  class="form-control col-md-7 col-xs-12">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li><i class="fa fa-minus"></i>Wilayah Pengeluaran Ijin</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['legalitas_lembaga_wilayah_ijin']) ? $data['legalitas_lembaga_wilayah_ijin'] : ''}}"  class="form-control col-md-7 col-xs-12">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li><i class="fa fa-minus"></i>Kodepos</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['legalitas_lembaga_wilayah_ijin_kodepos']) ? $data['legalitas_lembaga_wilayah_ijin_kodepos'] : ''}}"  class="form-control col-md-7 col-xs-12">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" > <li><i class="fa fa-minus"></i>Masa Berlaku Ijin</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										@php
											if(isset($data['tgl_legalitas_masa_berlaku'])){
												$date = formatDate($data['tgl_legalitas_masa_berlaku']);
											}else{
												$date = "";
											}
										@endphp
										<input type="text"  value="{{$date}}"  class="form-control col-md-7 col-xs-12 datepicker">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" > <li><i class="fa fa-minus"></i> No. NPWP (a.n) </li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['legalitas_lembaga_npwp']) ? $data['legalitas_lembaga_npwp'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
									</div>
								</div>
							</ul>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Alamat Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['alamat']) ? $data['alamat'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Kodepos Alamat</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['alamat_kodepos']) ? $data['alamat_kodepos'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor Telpon Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['nomor_telp']) ? $data['nomor_telp'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Alamat Email Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['email']) ? $data['email'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Penanggung Jawab Program</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['penanggung_jawab']) ? $data['penanggung_jawab'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Kontak Person Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li> Nama </li> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['cp_nama']) ? $data['cp_nama'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Nomor Telpon</li> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['cp_telp']) ? $data['cp_telp'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Alamat Email</li> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['cp_email']) ? $data['cp_email'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
								</div>
							</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Model dan Tatanan Layanan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								@if(count($lembaga))
									@foreach($lembaga as $l_key=>$l_value)
										@php
											if(isset($data['kode_model_layanan'])):
												if($data['kode_model_layanan'] == $l_key):
													$checked = 'checked="checked"';
												else:
													$checked = '';
												endif;
											endif;
										@endphp
										<div class="radio">
											<label>
												<input type="radio" {{$checked}} value="{{$l_key}}" id="optionsRadios1" name="model_layanan"> {{$l_value}}
											</label>
										</div>
									@endforeach
								@endif
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" > Periode Layanan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li> Rawat Inap </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['periode_layanan_inap']) ? $data['periode_layanan_inap'] : ''}}"  class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Bulan
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li> Rawat Jalan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['periode_layanan_jalan']) ? $data['periode_layanan_jalan'] : ''}}"  class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Kali Pertemuan/Periode Perawatan
								</div>
							</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Sumber Biaya</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								@if(count($biaya))
									@foreach($biaya as $b_key=>$b_value)

										<div class="radio">
											<label>
												<input type="radio" {{isset($data['kode_sumberbiaya']) ? (($data['kode_sumberbiaya'] == $b_key) ? 'checked="checked"' :'' ) : ''}} value="{{$b_key}}" id="optionsRadios1" name="sumber_biaya"> {{$b_value}}
											</label>
										</div>
									@endforeach
								@endif
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Pola Tarif Resmi Pelayanan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Rawat Inap </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['tarif_resmi_inap']) ? number_format($data['tarif_resmi_inap']) : ''}}"  class="form-control col-md-7 col-xs-12" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Rp. per bulan
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Rawat Jalan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['tarif_resmi_jalan']) ? number_format($data['tarif_resmi_jalan']) : ''}}"  class="form-control col-md-7 col-xs-12" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Rp. per Kali Pertemuan/Periode Perawatan
								</div>
							</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">SDM (Sumber Daya Manusia)</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Manajemen </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['sdm_manajemen']) ? number_format($data['sdm_manajemen']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Orang
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Petugas Layanan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>
							<ul class="order-dash">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Dokter Spesialis </li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_dokterspesialis']) ? number_format($data['sdm_dokterspesialis']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Dokter Umum</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_dokterumum']) ? number_format($data['sdm_dokterumum']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Psikolog</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_psikolog']) ? number_format($data['sdm_psikolog']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Perawat</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_perawat']) ? number_format($data['sdm_perawat']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pekerja Sosial</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_pekerjasosial']) ? number_format($data['sdm_pekerjasosial']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Konselor Adiksi</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_konseloradiksi']) ? number_format($data['sdm_konseloradiksi']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Instruktur</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_instruktur']) ? number_format($data['sdm_instruktur']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								</ul>
							
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Petugas Penunjang </li> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
								</div>

							</div>
							<ul class="order-dash">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Petugas Administrasi</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_penunjang_administrasi']) ? number_format($data['sdm_penunjang_administrasi']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Petugas Logistik</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_penunjang_logistik']) ? number_format($data['sdm_penunjang_logistik']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Keamanan</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text"  value="{{isset($data['sdm_penunjang_keamanan']) ? number_format($data['sdm_penunjang_keamanan']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
						</ul>		
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Saranaprasarana </li></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								@if(count($prasarana))
									@php
										$codes = [];
										$checked = '';
										if(isset($data['kode_saranaprasarana'])):
											$codes = explode(',',$data['kode_saranaprasarana']);
										else:
											$codes = [];
										endif;

									@endphp
									@foreach($prasarana as $p_key=>$p_value)
										@php
											if(in_array($p_key,$codes)){
												$checked = 'checked=checked';
											}else{
												$checked ='' ;
											}
										@endphp
										<div class="checkbox">
											<label>
												<input type="checkbox" {{$checked}} name="prasarana" value="{{$p_key}}"/> {{$p_value}}
											</label>
										</div>
									@endforeach
								@endif
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Kapasitas</li></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-dash">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Rawat Inap </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['kapasitas_inap']) ? number_format($data['kapasitas_inap']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Orang/Periode Pelayanan
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"> <li> Rawat Jalan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['kapasitas_jalan']) ? number_format($data['kapasitas_jalan']) : ''}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric(event)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Orang/Periode Pelayanan
								</div>
							</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan yang diterima</li></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-dash">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan Assessment</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['pelatihan_assessment']) ? number_format($data['pelatihan_assessment']) : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan Konseling (UTC)</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['pelatihan_konseling']) ? number_format($data['pelatihan_konseling']) : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Magang</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['pelatihan_magang']) ? number_format($data['pelatihan_magang']) : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan MI/CBT</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text"  value="{{isset($data['pelatihan_micbt']) ? number_format($data['pelatihan_micbt']) : ''}}"  class="form-control col-md-7 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan Lainnya (Sebutkan)</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
											<label> Nama Pelatihan</label>
											<input type="text"  value="{{isset($data['pelatihan_lainnya']) ? $data['pelatihan_lainnya'] : ''}}"  class="form-control col-md-3 col-xs-12">
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<label> Jumlah Orang</label>
											<input type="text"  value="{{isset($data['lepatihan_lainnya_jumlah']) ? $data['lepatihan_lainnya_jumlah'] : ''}}"  class="form-control col-md-3 col-xs-12" onKeydown="numeric(event)">
										</div>
									</div>
									</div>
								</div>

							

						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Jumlah Klien yang Telah dilayani sejak awal tahun berjalan</li></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{isset($data['jumlah_klien_dilayani_sejak_awal_tahun']) ? $data['jumlah_klien_dilayani_sejak_awal_tahun'] : ''}}"  class="form-control col-md-7 col-xs-12 datepicker">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">

								

								<input type="submit" class="btn btn-success" name="submit_form" value="Simpan"/>
								<input type="submit" class="btn btn-primary" name="cancel_form" value="Batal"/>
							</div>
						</div>
					</form>
					</ul>	
	              
	        </div>
     	</div>
	</div>
@endsection
