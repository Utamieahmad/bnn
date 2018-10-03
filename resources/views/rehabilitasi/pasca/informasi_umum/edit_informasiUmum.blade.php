@extends('layouts.base_layout')
@section('title', 'Ubah Data Informasi Lembaga Umum')

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
					<h2>Form Ubah Informasi Umum Lembaga Direktorat Pascarehabilitasi</h2>

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
					<form  method="POST" action="{{route('update_informasi_lembaga_umum_pascarehabilitasi')}}" data-parsley-validate class="form-horizontal form-label-left text-left">
						{{csrf_field()}}
						<input type="hidden" name="id" value="{{$data->id}}"/>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" > Nama Lembaga </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="nama" value="{{$data->nama}}"  class="form-control col-md-7 col-xs-12" required>
							</div>
						</div>

						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Alamat Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="alamat" value="{{$data->alamat}}"  class="form-control col-md-7 col-xs-12 ">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Kodepos Alamat</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="alamat_kodepos" value="{{$data->alamat_kodepos}}"  class="form-control col-md-7 col-xs-12 " onKeydown="numeric_only(event,this)">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor Telpon Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="nomor_telp" value="{{$data->nomor_telp}}"  class="form-control col-md-7 col-xs-12 " onkeydown="phone(event,this)">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Alamat Email Lembaga</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="email" name="email" value="{{$data->email}}"  class="form-control col-md-7 col-xs-12 ">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Penanggung Jawab Program</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="penanggung_jawab" value="{{$data->penanggung_jawab}}"  class="form-control col-md-7 col-xs-12 ">
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
									<input type="text" name="cp_nama" value="{{$data->cp_nama}}"  class="form-control col-md-7 col-xs-12 ">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Nomor Telpon</li> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="cp_telp" value="{{$data->cp_telp}}"  class="form-control col-md-7 col-xs-12 " onkeydown="phone(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Alamat Email</li> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="email" name="cp_email" value="{{$data->cp_email}}"  class="form-control col-md-7 col-xs-12 ">
								</div>
							</div>
						</ul>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Model dan Tatanan Layanan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Periode Rawatan</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="radio">
										@if(isset($periode_rawatan))
											@if(count($periode_rawatan) > 0)
												@foreach ($periode_rawatan as $prkey => $prval)
													<label class="mt-radio col-md-9"> 
														<input type="radio" value="{{$prkey}}" name="periode_rawatan" {{(  ($prkey == $data->periode_rawatan) ? 'checked=checked' : '')}}/>
														<span>{{$prval}}</span>
													</label>
												@endforeach
											@endif
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Bentuk Layanan</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									$array_layanan = [];
										if(isset($data)){
											if($data->bentuk_layanan){
												$d = json_decode($data->bentuk_layanan,true);
												if(count($d)>0){
													for($i = 0; $i < count($d); $i++){
														$array_layanan[] = $d[$i];
													}
												}else{
													$array_layanan = [];
												}
											}else{
												$array_layanan = [];
											}
										}else{
											$array_layanan = [];
										}
									?>
									<div class="checkbox">
										@if(isset($bentuk_layanan))
											@if(count($bentuk_layanan) > 0)
												@foreach ($bentuk_layanan as $brkey => $brval)
													<label class="mt-radio col-md-9"> 
														<input type="checkbox" name="bentuk_layanan[]" value="{{$brkey}}" {{(in_array($brkey,$array_layanan) ? 'checked=checked':'')}}/>
														<span>{{$brval}}</span>
													</label>
												@endforeach
											@endif
										@endif
									</div>
								</div>
							</div>
						</ul>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" > Periode Layanan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li> Rawat Inap </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="periode_layanan_inap" value="{{$data->periode_layanan_inap}}"  class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric_only(event,this)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Bulan
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li> Rawat Jalan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="periode_layanan_jalan" value="{{$data->periode_layanan_jalan}}"  class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric_only(event,this)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Kali Pertemuan/Periode Perawatan
								</div>
							</div>
						</ul>
						

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Pola Tarif Resmi Pelayanan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-abjad">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Rawat Inap </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="tarif_resmi_inap" value="{{number_format($data->tarif_resmi_inap)}}"  class="form-control col-md-7 col-xs-12" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)" onKeydown="numeric_only(event,this)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Rp. per bulan
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Rawat Jalan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="tarif_resmi_jalan" value="{{number_format($data->tarif_resmi_jalan)}}"  class="form-control col-md-7 col-xs-12" onClick="reformatNumber(event,this)"  onChange="reformatNumber(event,this)" onKeydown="numeric_only(event,this)">
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
									<input type="text" class="form-control col-md-7 col-xs-12" name="sdm_manajemen" value="{{$data->sdm_manajemen}}" onKeydown="numeric_only(event,this)">
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Dokter Umum </li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_dokter_umum" value="{{ ( isset($data)? $data->sdm_dokter_umum : '') }}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Spesialis Jiwa</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_spesialis_jiwa" value="{{ (isset($data)? $data->sdm_spesialis_jiwa : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Dokter Gigi</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_dokter_gigi" value="{{( isset($data)? $data->sdm_dokter_gigi : '' )}}" class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Spesialis Lainnya</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_spesialis_lain" class="form-control col-md-7 col-xs-12" value="{{ (isset($data)? $data->sdm_spesialis_lain : '')}}" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Psikolog</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_psikolog" value="{{ (isset($data)? $data->sdm_psikolog :'' ) }}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Perawat</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_perawat" value="{{ (isset($data)? $data->sdm_perawat : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Apoteker</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_apoteker" value="{{ (isset($data)? $data->sdm_apoteker : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Analis</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_analis" value="{{ (isset($data)? $data->sdm_analis : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pekerja Sosial</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_peksos" value="{{ (isset($data)? $data->sdm_peksos : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Konselor</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_konselor" value="{{ (isset($data)? $data->sdm_konselor : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>S.Psi</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_spsi" value="{{ (isset($data)? $data->sdm_spsi : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>SKM</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_skm" value="{{ (isset($data)? $data->sdm_skm : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>S.Ag</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_sag" value="{{ (isset($data)? $data->sdm_sag : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
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
										<input type="text" name="sdm_penunjang_administrasi" value="{{$data->sdm_penunjang_administrasi}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Petugas Logistik</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_penunjang_logistik" value="{{$data->sdm_penunjang_logistik}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li> Keamanan</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_penunjang_keamanan" value="{{$data->sdm_penunjang_keamanan}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>
						</ul>
						<div class="form-group">
							<label for="kode_saranaprasarana" class="control-label col-md-3 col-sm-3 col-xs-12"><li> Saranaprasarana </li></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="checkbox col-md-8">
									@php
										$codes = [];
										if($data->kode_saranaprasarana){
											$codes = explode(',',$data->kode_saranaprasarana);
										}
									@endphp
									@if(count($prasarana)>=1)
										@foreach($prasarana as $pkey=> $pvalue)
											<label class="control-label col-md-12 col-sm-3 col-xs-12">
												<input type="checkbox" name="kode_saranaprasarana[]" value="{{$pkey}}" {{(in_array($pkey,$codes ) ? 'checked=checked' : '')}}/>
												<span>&nbsp; {{$pvalue}}</span>
											</label>
						                @endforeach
					                @endif

								</div>
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
									<input type="text" name="kapasitas_inap" value="{{$data->kapasitas_inap}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									Orang/Periode Pelayanan
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"> <li> Rawat Jalan </li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" class="form-control col-md-7 col-xs-12" name="kapasitas_jalan" value="{{$data->kapasitas_jalan}}" onKeydown="numeric_only(event,this)">
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
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Assessment dan rencana terapi</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_assessment" value="{{ (isset($data)? $data->pelatihan_assessment : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pengetahuan dasar adiksi</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_adiksi" value="{{ (isset($data)? $data->pelatihan_adiksi : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Intervensi psikososial</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_intervensi" value="{{ (isset($data)? $data->pelatihan_intervensi : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Fisiologi dan farmakologi</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_fisiologi" value="{{ (isset($data)? $data->pelatihan_fisiologi : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Tatalaksana medis terkait napza</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_tatalaksana" value="{{ (isset($data)? $data->pelatihan_tatalaksana : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan Lainnya (Sebutkan)</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
											<label> Nama Pelatihan</label>
											<input type="text" name="pelatihan_lainnya" value="{{ (isset($data)? $data->pelatihan_lainnya : '')}}"  class="form-control col-md-3 col-xs-12">
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<label> Jumlah Orang</label>
											<input type="text" name="pelatihan_lainnya_jumlah" value="{{ (isset($data)? $data->pelatihan_lainnya_jumlah : '')}}"  class="form-control col-md-3 col-xs-12" onKeydown="numeric_only(event,this)">
										</div>
									</div>
									</div>
								</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Jumlah Klien yang Telah dilayani sejak awal tahun berjalan</li></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<ul class="order-dash">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Pasca Reguler</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_pasca_reg" value="{{ (isset($data)? $data->jml_pasca_reg : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Pasca Intensif</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_pasca_int" value="{{ (isset($data)? $data->jml_pasca_int : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Pasca Lanjut</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_pasca_lnj" value="{{ (isset($data)? $data->jml_pasca_lnj : '')}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="submit" class="btn btn-success" value="SIMPAN"/>
                                <a href="{{route('informasi_lembaga_umum_pascarehabilitasi')}}" class="btn btn-primary" type="button">BATAL</a>
							</div>
						</div>
					</form>
				</ul>
	        </div>
     	</div>
	</div>
@endsection
