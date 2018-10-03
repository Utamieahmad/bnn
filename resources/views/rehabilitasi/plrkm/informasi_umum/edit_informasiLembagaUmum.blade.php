@extends('layouts.base_layout')
@section('title', 'Ubah Data Informasi Lembaga Umum ')
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
					<h2>Form Ubah Informasi Umum Lembaga Direktorat PLRKM</h2>

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
					<form  method="POST" action="{{route('update_informasi_lembaga_umum_plrkm')}}" data-parsley-validate class="form-horizontal form-label-left text-left">
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
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Status</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="radio">
										@if(isset($status_layanan))
											@if(count($status_layanan) > 0)
												@foreach ($status_layanan as $lkey => $lval)
													<label class="mt-radio col-md-9"> <input type="radio" value="{{$lkey}}" name="status_layanan" {{( ($data->status_layanan == $lkey) ? 'checked=checked' :'') }}>
														<span>{{$lval}}</span>
													</label>
												@endforeach
											@endif
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Pendekatan</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="checkbox">
										<?php
											$arr_pendekatan=[];
											if($data->layanan_pendekatan){
												$arr_data = json_decode($data->layanan_pendekatan,true);
												if(count($arr_data)>0){
													for($a = 0 ; $a < count($arr_data); $a++){
														$arr_pendekatan[] = $arr_data[$a];
													}
												}
											}
										?>
										
										@if(isset($layanan_pendekatan))
											@if(count($layanan_pendekatan) > 0)
												@foreach ($layanan_pendekatan as $lpkey => $lpval)
													<label class="mt-checkbox col-md-9"> 
														<input type="checkbox" value="{{$lpkey}}" name="layanan_pendekatan[]" {{(  (in_array($lpkey,$arr_pendekatan) ? 'checked=checked' :'') )}} {{ ($lpkey == 'lainnya') ? 'onClick=show_text(this)' :''}}>
														<span>{{$lpval}}</span>
													</label>
												@endforeach
											@endif
										@endif
										
										<input type="text"  name="layanan_pendekatan_lain" value="{{$data->layanan_pendekatan_lain}}" class="form-control layanan_pendekatan_lain {{(  (in_array('lainnya',$arr_pendekatan) ? '' :'hide') )}}"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Ketersediaan Layanan</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="checkbox">
										<?php
											$arr_ketersediaan=[];
											if($data->layanan_ketersediaan){
												$arr_data = json_decode($data->layanan_ketersediaan,true);
												if(count($arr_data)>0){
													for($a = 0 ; $a < count($arr_data); $a++){
														$arr_ketersediaan[] = $arr_data[$a];
													}
												}
											}
										?>
										@if(isset($layanan_ketersediaan))
											@if(count($layanan_ketersediaan) > 0)
												@foreach ($layanan_ketersediaan as $kkey => $kval)
													<label class="mt-checkbox col-md-9"> 
														<input type="checkbox" value="{{$kkey}}" name="layanan_ketersediaan[]" {{(  (in_array($kkey,$arr_ketersediaan) ? 'checked=checked' :'') )}}>
														<span>{{$kval}}</span>
													</label>
												@endforeach
											@endif
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" ><li>Periode Rawatan</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="radio">
										
										@if(isset($periode_rawatan))
											@if(count($periode_rawatan) > 0)
												@foreach ($periode_rawatan as $prkey => $prval)
													<label class="mt-radio col-md-9"> 
														<input type="radio" value="{{$prkey}}" name="periode_rawatan" {{($data->periode_rawatan == $prkey ? 'checked=checked' : '')}}>
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
									<div class="checkbox">
										<?php
											$arr_layanan=[];
											if($data->bentuk_layanan){
												$arr_data = json_decode($data->bentuk_layanan,true);
												if(count($arr_data)>0){
													for($a = 0 ; $a < count($arr_data); $a++){
														$arr_layanan[] = $arr_data[$a];
													}
												}
											}
										?>
										
										@if(isset($bentuk_layanan))
											@if(count($bentuk_layanan) > 0)
												@foreach ($bentuk_layanan as $blkey => $blval)
													<label class="mt-radio col-md-9"> 
														<input type="checkbox" name="bentuk_layanan[]" value="{{$blkey}}" {{( (in_array($blkey,$arr_layanan))?'checked=checked':'')}}/>
														<span>{{$blval}}</span>
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
						{{-- <div class="form-group">
							<label for="kode_sumberbiaya" class="control-label col-md-3 col-sm-3 col-xs-12" >Sumber Biaya</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="radio">
									@if(count($sumberbiaya)>=1)
										@foreach($sumberbiaya as $skey=> $svalue)
											<label class="mt-radio col-md-9"> <input type="radio" value="{{$skey}}" name="kode_sumberbiaya" {{(($data->kode_sumberbiaya == $skey)? 'checked=checked' : ''  )}}>
						                    <span>{{$svalue}}</span>
						                    </label>
						                @endforeach
					                @endif

								</div>
							</div>
						</div> --}}

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
										<input type="text" name="sdm_dokter_umum" value="{{$data->sdm_dokter_umum}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Spesialis Jiwa</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_spesialis_jiwa" value="{{$data->sdm_spesialis_jiwa}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Dokter Gigi</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_dokter_gigi" value="{{$data->sdm_dokter_gigi}}" class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Spesialis Lainnya</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_spesialis_lain" class="form-control col-md-7 col-xs-12" value="{{$data->sdm_spesialis_lain}}" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Psikolog</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_psikolog" value="{{$data->sdm_psikolog}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Perawat</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_perawat" value="{{$data->sdm_perawat}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Apoteker</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_apoteker" value="{{$data->sdm_apoteker}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Analis</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_analis" value="{{$data->sdm_analis}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pekerja Sosial</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_peksos" value="{{$data->sdm_peksos}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Konselor</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_konselor"  value="{{$data->sdm_konselor}}"   class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>S.Psi</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_spsi" value="{{$data->sdm_spsi}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>SKM</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_skm" value="{{$data->sdm_skm}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										Orang
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>S.Ag</li></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sdm_sag" value="{{$data->sdm_sag}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
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
								<div class="checkbox">
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
									<input type="text" name="pelatihan_assessment" value="{{$data->pelatihan_assessment}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pengetahuan dasar adiksi</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_adiksi" value="{{$data->pelatihan_adiksi}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Intervensi psikososial</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_intervensi" value="{{$data->pelatihan_intervensi}}" class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Fisiologi dan farmakologi</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_fisiologi"  value="{{$data->pelatihan_fisiologi}}" class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Tatalaksana medis terkait napza</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="pelatihan_tatalaksana"  value="{{$data->pelatihan_tatalaksana}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li>Pelatihan Lainnya (Sebutkan)</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
											<label> Nama Pelatihan</label>
											<input type="text" name="pelatihan_lainnya" value="{{$data->pelatihan_lainnya}}"  class="form-control col-md-3 col-xs-12">
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<label> Jumlah Orang</label>
											<input type="text" name="pelatihan_lainnya_jumlah"  value="{{$data->pelatihan_lainnya_jumlah}}" class="form-control col-md-3 col-xs-12" onKeydown="numeric_only(event,this)">
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
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Rawat Inap Sosial</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_inap_sosial" value="{{$data->jml_inap_sosial}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Rawat Jalan Sosial</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_jalan_sosial" value="{{$data->jml_jalan_sosial}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Rawat Inap Medis</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_inap_medis" value="{{$data->jml_inap_medis}}"   class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><li><i class="fa fa-minus"></i>Rawat Jalan Medis</li></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="jml_jalan_medis" value="{{$data->jml_jalan_medis}}"  class="form-control col-md-7 col-xs-12" onKeydown="numeric_only(event,this)">
								</div>
							</div>
						</ul>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="submit" class="btn btn-success" value="SIMPAN"/>
                                <a href="{{route('informasi_lembaga_umum_plrkm')}}" class="btn btn-primary" type="button">BATAL</a>
							</div>
						</div>
					</form>
				</ul>
	        </div>
     	</div>
	</div>
@endsection
