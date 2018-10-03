@extends('layouts.base_layout')
@section('title', 'Ubah Pendataan Jaringan')
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

	  	<div class="clearfix"></div>
	  	<div class="row">
		    <div class="col-md-12 col-sm-12 col-xs-12">
		      	<div class="x_panel">
			        <div class="x_title">
						<h2>Form Ubah Pendataan LKN Direktorat Narkotika</h2>
						<div class="clearfix"></div>
			        </div>
			        <div class="x_content">
			      		<br />
			      		@php
			      			$session = session('status');
			      		@endphp
			      		@if($session)
			      			<div class="alert alert-{{$session['status']}}">
			      				{{$session['message']}}
			      			</div>
			      		@endif
			          	<form data-parsley-validate class="form-horizontal form-label-left" action="#" method="post" >
				            {{ csrf_field() }}
				            <input type="hidden" name="id" value="{{$id}}">

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal LKN</label>
								<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
									<input type="text" value="{{ ($data_kasus['data']['kasus_tanggal'] ? \Carbon\Carbon::parse($data_kasus['data']['kasus_tanggal'] )->format('d/m/Y') :'')}}" class="form-control" disabled/>
									<span class="input-group-addon disabled">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" class="form-control" value="{{$instansi}}" disabled/>
								</div>
							</div>

				            <div class="form-group group-lkn">
								@php
								if(strlen($data_kasus['data']['kasus_no']) > "20"){
									$lkn = splitLKN($data_kasus['data']['kasus_no']);
								} else {
									$lkn = [
										0 => "",
										1 => "",
										2 => "",
										3 => "",
										4 => "",
										5 => ""
									];
								}
								@endphp

								<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN</label>
								<div class="col-md-2 col-sm-1 col-xs-1">
									<input type="text" id="noLKN0" value="{{(($lkn[0] != "")? $lkn[0] : "LKN")}}"  class="form-control" readonly>
								</div>

								<div class="col-md-2 col-sm-1 col-xs-1">
									<input type="text" id="noLKN1" value="{{(($lkn[1] != "")? $lkn[1] : "")}}" class="form-control" disabled>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2">
									<select class="form-control" id="noLKN2" disabled>
										<option value="P2" {{($lkn[2] == 'P2') ? 'selected' : ''}}>P2</option>
										<option value="INTD" {{($lkn[2] == 'INTD') ? 'selected' : ''}}>INTD</option>
										<option value="NAR" {{($lkn[2] == 'NAR') ? 'selected' : ''}}>NAR</option>
										<option value="TPPU" {{($lkn[2] == 'TPPU') ? 'selected' : ''}}>TPPU</option>
										<option value="BRNTS" {{($lkn[2] == 'BRNTS') ? 'selected' : ''}}>BRNTS</option>
									</select>
								</div>

								<div class="col-md-2 col-sm-1 col-xs-1">
									<input type="text"  id="noLKN3" value="{{(isset($lkn[3])? $lkn[3] : "")}}"   class="form-control" readonly>
								</div>

				            	<div class="col-md-1 col-sm-1 col-xs-1">
				                	<input type="text" id="noLKN4" value="{{(isset($lkn[4])? $lkn[4] : "")}}"  class="form-control" readonly>
				             	</div>

				            	<div class="col-md-2 col-sm-2 col-xs-2 last-child">
				                	<input type="text" id="noLKN5" value="{{(isset($lkn[5])? $lkn[5] : "")}}"  class="form-control" readonly>
				            	</div>
				            </div>

				            <div class="form-group">
				            	<label class="control-label col-md-3 col-sm-3 col-xs-12"  ></label>
				            	<div class="col-md-6 col-sm-6 col-xs-12">
				           			<input type="text" id="pelaksanaGenerate" value="{{$data_kasus['data']['kasus_no']}}"   class="form-control col-md-7 col-xs-12" disabled>
				              	</div>
				            </div>

				            <div class="form-group">
				            	<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Penanggung Jawab (Penyidik)</label>
					            <div class="col-md-6 col-sm-6 col-xs-12">
					            	<input type="text" value="{{$data_kasus['data']['nama_penyidik']}}"  class="form-control col-md-7 col-xs-12" disabled>
					            </div>
				            </div>

			            	<div class="form-group">
			             		<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Tanggal Kejadian</label>
			           			<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal disabled'>
			        				<input type='text' value="{{ ($data_kasus['data']['kasus_tanggal'] ? \Carbon\Carbon::parse($data_kasus['data']['kasus_tanggal'])->format('d/m/Y') : '') }}" class="form-control" disabled/>
			                		<span class="input-group-addon disabled">
			                  			<span class="glyphicon glyphicon-calendar"></span>
			                		</span>
			              		</div>
			            	</div>
				            <div class="form-group">
				            	<label class="control-label col-md-3 col-sm-3 col-xs-12"  >TKP Kasus</label>
				           			<div class="col-md-6 col-sm-6 col-xs-12">
				            		<input type="text" value="{{$data_kasus['data']['kasus_tkp']}}"  class="form-control col-md-7 col-xs-12" disabled>
				              	</div>
				            </div>

			            	<div class="form-group">
			              		<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			              		<div class="col-md-6 col-sm-6 col-xs-12">
				            		<input type="text" value="{{$nm_wilayah}}"  class="form-control col-md-7 col-xs-12" disabled>
			                	</div>
			             	</div>


			              	<div class="form-group">
			                	<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			                	<div class="col-md-6 col-sm-6 col-xs-12">
			                		<input type="text" value="{{$ktp_kabupaten}}"  class="form-control col-md-7 col-xs-12" disabled>
			                  	</div>
			                </div>

			                <div class="form-group">
			                  	<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Modus Operandi</label>
			              		<div class="col-md-6 col-sm-6 col-xs-12">
			                			<input type="text" name="modus" value="{{$data_kasus['data']['modus_operandi']}}" class="form-control col-md-7 col-xs-12" disabled>
			              		</div>
			                </div>

			                <div class="form-group">
			                  	<label class="control-label col-md-3 col-sm-3 col-xs-12">Negara Sumber Narkotika</label>
			                  	<div class="col-md-6 col-sm-6 col-xs-12">
		                			<input type="text" name="modus" value="{{$nama_negara}}" class="form-control col-md-7 col-xs-12" disabled>
			                	</div>
			                </div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jalur Masuk</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
		                			<input type="text" name="modus" value="{{$jalur_masuk}}" class="form-control col-md-7 col-xs-12" disabled>
			                	</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Asal</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="ruteAsal" value="{{$data_kasus['data']['rute_asal']}}" class="form-control col-md-7 col-xs-12" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Transit</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="ruteTransit" value="{{$data_kasus['data']['rute_transit']}}"  class="form-control col-md-7 col-xs-12" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Tujuan</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="ruteTujuan" value="{{$data_kasus['data']['rute_tujuan']}}"  class="form-control col-md-7 col-xs-12" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kasus</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="ruteTujuan" value="{{$kasus_jenis}}"  class="form-control col-md-7 col-xs-12" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Kasus</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select class="form-control select2 noSearch" name="kelompokKasus" disabled>
										<option value="">-- Kelompok Kasus -- </option>
										<option value="TPPU" {{($data_kasus['data']['kasus_kelompok'] == "TPPU") ? 'selected="selected"':""}}>TPPU</option>
										<option value="NARKOTIKA" {{($data_kasus['data']['kasus_kelompok'] == "NARKOTIKA") ? 'selected="selected"':""}}>NARKOTIKA</option>
									</select>
								</div>
							</div>

						</form>
						<hr/>
						<div class="x_content">
			                <h2>Data Jaringan Narkotika</h2>

			                <form class="form-horizontal form-label-left" action="{{route('update_pendataan_jaringan')}}" enctype="multipart/form-data" method="post">
			                  	{{ csrf_field() }}

			                  {{--	<div class="form-group">
			                    	<label class="control-label col-md-3">Keterlibatan Jaringan</label>
				                    <div class="col-md-9">
				                    	<div class="radio">
					                      	<label class="mt-radio col-md-2">
					                      		<input type="radio" id="keterlibatan_jaringan_ya" value="Y" name="keterlibatan_jaringan" {{ ( (strtolower($data_lkn['keterlibatan_jaringan']) ==strtolower('Y') ) ? 'checked=checked' : '') }}>
					                        	<span>Ya</span>
					                      	</label>
					                      	<label class="mt-radio col-md-2">
					                      		<input type="radio" id="keterlibatan_jaringan_tidak" value="T" name="keterlibatan_jaringan" {{ ( (strtolower($data_lkn['keterlibatan_jaringan']) ==strtolower('T') ) ? 'checked=checked' : '') }}>
					                        	<span>Tidak</span>
					                      	</label>
					                      	<span class="help-block"></span>
				                    	</div>
				                    </div>
			                  	</div> --}}

			                  	<div class="form-group">
			                    	<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Jaringan</label>
			                    	<div class="col-md-6 col-sm-6 col-xs-12">
			                      		<input type="text" name="nama_jaringan" value="{{$data_lkn['nama_jaringan']}}" class="form-control col-md-7 col-xs-12">
			                    	</div>
			                  	</div>

			                  	<div class="form-group">
			                    	<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Komandan Jaringan</label>
			                    	<div class="col-md-6 col-sm-6 col-xs-12">
			                      		<input type="text" name="nama_komandan_jaringan" value="{{$data_lkn['nama_komandan_jaringan']}}" class="form-control col-md-7 col-xs-12">
			                    	</div>
			                  	</div>

			                  	<div class="form-group">
			                    	<label class="control-label col-md-3">Jaringan</label>
			                    	<div class="col-md-9">
			                    		<div class="radio">
				                      		<label class="mt-radio col-md-2"> <input type="radio" data-id="jaringanNasional" data-hide="jaringanInternasional" id="kode_jenisjaringan_nas" value="Nasional" name="kode_jenisjaringan" onClick="jenis_jaringan(this)" {{ ( (strtolower($data_lkn['kode_jenisjaringan']) == strtolower("Nasional")) ? 'checked=checked' :'' ) }}>
				                    			<span>Nasional</span>
				                      		</label>
				                      		<label class="mt-radio col-md-2"> <input type="radio" data-id="jaringanInternasional" data-hide="jaringanNasional" id="kode_jenisjaringan_inter" value="Internasional" name="kode_jenisjaringan" onClick="jenis_jaringan(this)" {{ ( (strtolower($data_lkn['kode_jenisjaringan']) == strtolower("Internasional")) ? 'checked=checked' :'' ) }}>
				                        		<span>Internasioal</span>
				                      		</label>
				                      		<span class="help-block"></span>
			                			</div>
			                		</div>
			                  	</div>


			                  	<div style="display: none" id="jaringanNasional">
			                    	<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Provinsi Asal Wilayah Jaringan</label>
										<div class="col-md-6 col-sm-6 col-xs-12">

											<select class="form-control selectPropinsi select2" name="asal_wilayah_idprovinsi">
												<option>-- Pilih Propinsi -- </option>
												@foreach($propinsi as $p)
													<option value="{{$p['id_wilayah']}}" {{($p['id_wilayah'] == $data_lkn['asal_wilayah_idprovinsi']) ? 'selected=selected':''}}> {{$p['nm_wilayah']}}</p>
												@endforeach
											</select>
										</div>
									</div>

			                      	<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Kab/Kota Asal Wilayah Jaringan</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select class="form-control selectKabupaten select2" name="asal_wilayah_idkabkota">
												<option>-- Pilih Kabupaten -- </option>
												@if(count($wil_kabupaten) > 0 )
													@foreach($wil_kabupaten as $b)
														<option value="{{$b->id_wilayah}}" {{(($b->id_wilayah == $data_lkn['asal_wilayah_idkabkota']) ? 'selected=selected':'')}}> {{$b->nm_wilayah}}</p>
													@endforeach
												@endif
											</select>
										</div>
									</div>
			                   	</div>

		                    	<div style="display: none" id="jaringanInternasional">
			                    	<div class="form-group">
				                      	<label class="control-label col-md-3 col-sm-3 col-xs-12">Asal Negara Jaringan</label>
			                      		<div class="col-md-6 col-sm-6 col-xs-12">
											<select class="form-control select2" name="asal_negara_jaringan">
												<option>-- Pilih Negara -- </option>
												@foreach($negara as $n)
													<option value="{{$n['kode']}}" {{($n['kode'] == $data_lkn['asal_negara_jaringan']) ? 'selected=selected':''}}> {{$n['nama_negara']}}</p>
												@endforeach
											</select>
										</div>
			                      	</div>
		                      	</div>

			                    <div class="form-group">
			                      	<label class="control-label col-md-3">Keterlibatan Jaringan Lain</label>
			                  		<div class="col-md-9">
			                  			<div class="radio">
				                    		<label class="mt-radio col-md-2" >
				                    			<input type="radio" id="keterkaitan_jaringan_lain_ya keterkaitan_jaringan" value="Y" name="keterkaitan_jaringan_lain" {{ ( (strtolower($data_lkn['keterkaitan_jaringan_lain']) ==strtolower('Y') ) ? 'checked=checked' : '') }} onClick="keterlibatanJaringan(true)">
				                      			<span>Ya</span>
				                    		</label>
				                    		<label class="mt-radio col-md-2">
				                    			<input type="radio" id="keterkaitan_jaringan_lain_tidak keterkaitan_jaringan" value="T" name="keterkaitan_jaringan_lain" {{ ( (strtolower($data_lkn['keterkaitan_jaringan_lain']) ==strtolower('T') ) ? 'checked=checked' : '') }}  onClick="keterlibatanJaringan(false)">
				                      			<span>Tidak</span>
				                    		</label>
				                    		<span class="help-block"></span>
			                  			</div>
			                  		</div>
			                    </div>

	                        <div class="form-group">
	                            <label for="instansi" class="col-md-3 control-label">Nama Jaringan Terkait</label>
	                            <div class="col-md-9">
	                                <div class="mt-repeater">
	                                    <div data-repeater-list="nama_jaringan_terkait">
																				@if((json_decode($data_lkn['meta_jaringan_terkait'],true)))
																					@foreach(json_decode($data_lkn['meta_jaringan_terkait'],true) as $r1 => $c1)
		                                        <div data-repeater-item="" class="mt-repeater-item">
		                                            <div class="row mt-repeater-row">
		                                                <div class="col-md-4">
		                                                    <label class="control-label">Nama Jaringan</label>
		                                                    <input name="nama_jaringan_terkait[{{$r1}}][nama_jaringan]" value="{{$c1['nama_jaringan']}}" type="text" class="form-control"> </div>
		                                                <div class="col-md-4">
		                                                    <label class="control-label">Peran Jaringan</label>
		                                                    <select class="form-control" name="nama_jaringan_terkait[{{$r1}}][peran_jaringan]">
		                                                      <option value="">-- Peran Jaringan --</option>
		                                                      <option value="penyandang_dana" {{($c1['peran_jaringan'] == 'penyandang_dana') ? 'selected="selected"':""}} >Penyandang Dana</option>
		                                                      <option value="pengendali" {{($c1['peran_jaringan'] == 'pengendali') ? 'selected="selected"':""}} >Pengendali</option>
		                                                      <option value="kurir" {{($c1['peran_jaringan'] == 'kurir') ? 'selected="selected"':""}} >Kurir</option>
		                                                      <option value="pengawas" {{($c1['peran_jaringan'] == 'pengawas') ? 'selected="selected"':""}} >Pengawas / Checker</option>
		                                                    </select>
		                                                </div>
		                                                <div class="col-md-1">
		                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
		                                                        <i class="fa fa-close"></i>
		                                                    </a>
		                                                </div>
		                                            </div>
		                                        </div>
																					@endforeach
																				@else
																					<div data-repeater-item="" class="mt-repeater-item">
	                                            <div class="row mt-repeater-row">
	                                                <div class="col-md-4">
	                                                    <label class="control-label">Nama Jaringan</label>
	                                                    <input name="nama_jaringan_terkait[0][nama_jaringan]" type="text" class="form-control"> </div>
	                                                <div class="col-md-4">
	                                                    <label class="control-label">Peran Jaringan</label>
	                                                    <select class="form-control" name="nama_jaringan_terkait[0][peran_jaringan]">
	                                                      <option value="">-- Peran Jaringan --</option>
	                                                      <option value="penyandang_dana">Penyandang Dana</option>
	                                                      <option value="pengendali">Pengendali</option>
	                                                      <option value="kurir">Kurir</option>
	                                                      <option value="pengawas">Pengawas / Checker</option>
	                                                    </select>
	                                                </div>
	                                                <div class="col-md-1">
	                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
	                                                        <i class="fa fa-close"></i>
	                                                    </a>
	                                                </div>
	                                            </div>
	                                        </div>
																				@endif
	                                    </div>
	                                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
	                                        <i class="fa fa-plus"></i> Tambah Jaringan</a>
	                                </div>
	                            </div>
	                        </div>

													<div class="form-group">
															<label for="file_upload" class="col-md-3 control-label">File Upload</label>
															<div class="col-md-6">
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
																			@if ($data_lkn['file_upload'])
																					@if (\Storage::exists('IntelJaringan/'.$data_lkn['file_upload']))
																							Lihat File : <a  target="_blank" class="link_file" href="{{\Storage::url('IntelJaringan/'.$data_lkn['file_upload'])}}">{{$data_lkn['file_upload']}}</a>
																					@endif
																			@endif

																	</span>
															</div>
													</div>

			                    <input type="hidden" name="nomor_lkn" value="{{$data_kasus['data']['kasus_no']}}">
			                    <input type="hidden" name="id_kasus" value="{{$data_kasus['data']['kasus_id']}}">
			                    <input type="hidden" name="id" value="{{$data_lkn['id']}}">

			                    <div class="form-group mb-20">
			                      	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			                            <button type="submit" class="btn btn-success">KIRIM</button>
							                    <a href="{{route('pendataan_jaringan')}}" class="btn btn-primary" type="button">BATAL</a>
			                        </div>
			                    </div>
			              	</form>
		            	</div>
					</div>
					<div class="x_content tab-content m-t-20">
		            	<div class=" m-t-20" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#tersangka" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Tersangka</a>
								</li>
								<li role="presentation" class="">
									<a href="#barang_bukti_narkotika" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Barang Bukti Narkotika</a>
								</li>
								<li role="presentation" class="">
									<a href="#barang_bukti_prekursor" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Prekursor</a>
								</li>
								<li role="presentation" class="">
									<a href="#barang_bukti_aset" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Aset</a>
								</li>
								<li role="presentation" class="">
									<a href="#barang_bukti_nonnarkotika" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Non Narkotika</a>
								</li>

							</ul>

		                	<div id="myTabContent" class="tab-content">
			                    <div role="tabpanel" class="tab-pane fade active in" id="tersangka" aria-labelledby="home-tab">
									<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
										<thead>
											<tr role="row" class="heading">
												<th width="5%"> No </th>
												<th width="40%"> Nama Tersangka </th>
												<th width="15%"> Warga Negara </th>
												<th width="15%"> Peran Tersangka </th>
												<th width="15%"> Pendidikan Akhir </th>
												<th width="15%"> Pekerjaan </th>
												<th width="10%"> Actions </th>
											</tr>
										</thead>
					                    <tbody>
			                        	@if(count($tersangka['data']))
			                        		@php $i = 1; @endphp
					                          @foreach($tersangka['data'] as $t)
					                          <tr>
					                            <td>{{$i}}</td>
					                            <td>{{$t['tersangka_nama']}}</td>
					                            <td>{{$t['kode_warga_negara']}}</td>
					                            <td>{{$t['kode_peran_tersangka']}}</td>
					                            <td>{{$t['kode_pendidikan_akhir']}}</td>
					                            <td>{{$t['kode_pekerjaan']}}</td>
					                            <td class="actionTable">
                        							<button class="btn btn-action"  data-target="{{$t['tersangka_id']}}" onClick="open_modalEditPeserta(event,this)"><i class="fa fa-search f-18"></i></button>
					                            </td>
					                          </tr>
												@php $i = $i+1; @endphp
				                          	@endforeach
			                        	@else
			                        		<tr>
			                        			<td  colspan="7">
							                        <div class="">
							                          Data Tersangka belum tersedia.
							                        </div>
							                    </td>
			                        		</tr>
			                        	@endif
				                        </tbody>
			                      </table>

			                    </div>

			                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_narkotika" aria-labelledby="profile-tab">

			                    	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
				                        <thead>
											<tr role="row" class="heading">
												<th width="2%"> No </th>
												<th width="5%"> Jenis Barang Bukti </th>
												<th width="15%"> Nama Barang Bukti </th>
												<th width="20%"> Jumlah Barang Bukti </th>
											</tr>
				                        </thead>
			                        	<tbody>
			                        	@if(count($brgBuktiNarkotika['data']))
				                          		@php $i = 1; @endphp
				                          		@foreach($brgBuktiNarkotika['data'] as $brgBukti)
													<tr>
														<td>{{$i}}</td>
														<td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
														<td>{{$brgBukti['nm_brgbukti']}}</td>
														<td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
													</tr>
				                          			@php $i = $i+1; @endphp
				                          		@endforeach
			                        	@else
			                        		<tr>
			                        			<td  colspan="6">
							                        <div class="">
						                         		Data barang bukti belum tersedia.
							                        </div>
							                    </td>
			                        		</tr>
			                        	@endif
			                        	</tbody>
			                      	</table>
			                    </div>

			                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_prekursor" aria-labelledby="profile-tab">

			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
				                        <thead>
				                          	<tr role="row" class="heading">
					                            <th width="2%"> No </th>
					                            <th width="5%"> Jenis Barang Bukti </th>
					                            <th width="15%"> Nama Barang Bukti </th>
					                            <th width="20%"> Jumlah Barang Bukti </th>
				                          	</tr>
				                        </thead>
			                        	<tbody>
				                        	@if(count($brgBuktiPrekursor['data']))
				                        		@php $i = 1; @endphp
				                        		@foreach($brgBuktiPrekursor['data'] as $brgBukti)
							                        <tr>
							                          	<td>{{$i}}</td>
							                          	<td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
							                          	<td>{{$brgBukti['nm_brgbukti']}}</td>
							                          	<td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
							                        </tr>
				                        			@php $i = $i+1; @endphp
				                        		@endforeach
				                       	 	@else
				                       	 		<tr>
				                       	 			<td  colspan="6">
								                        <div class="">
					                          				Data barang bukti belum tersedia.
								                        </div>
								                    </td>
				                        		</tr>
				                			@endif
			                			</tbody>
			                      	</table>
			                    </div>

			                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_aset" aria-labelledby="profile-tab">

			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
			                        	<thead>
			                          		<tr role="row" class="heading">
			                            	<th width="5%"> No </th>
			                            	<th width="25%"> Nama Aset </th>
			                            	<th width="5%"> Jumlah </th>
			                            	<th width="25%"> Konversi (Rupiah) </th>
			                            	<th width="20%"> Keterangan </th>
			                          		</tr>
			                        	</thead>
			                    		<tbody>
			                        	@if(count($brgBuktiAsetBarang['data']))
			                          			@php $i = 1; @endphp
			                          			@foreach($brgBuktiAsetBarang['data'] as $val)
			                          				<tr>
			                            				<td>{{$i}}</td>
			                            				<td>{{$val['nama_aset']}}</td>
			                            				<td>{{$val['jumlah_barang_bukti_aset']}}</td>
			                            				<td>{{$val['nilai_aset']}}</td>
			                            				<td>{{$val['keterangan']}}</td>
			                          				</tr>
			                          			@php $i = $i+1; @endphp
			                          			@endforeach
			                       	 	@else
			                       	 		<tr>
			                       	 			<td colspan="6">
						                        	<div class="alert-messages">
						                          		Data barang bukti belum tersedia.
						                        	</div>
			                       	 			</td>
			                       	 		</tr>
			                        	@endif
			                        	</tbody>
			                      	</table>


			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
			                        	<thead>
											<tr role="row" class="heading">
												<th width="5%"> No </th>
												<th width="25%"> Nama Aset </th>
												<th width="5%"> Jumlah </th>
												<th width="25%"> Konversi (Rupiah) </th>
												<th width="20%"> Keterangan </th>
											</tr>
			                    		</thead>
			                        	<tbody>
			                        	@if(count($brgBuktiAsetTanah['data']))
			                          		@php $i = 1; @endphp
			                          		@foreach($brgBuktiAsetTanah['data'] as $val)
			                          			<tr>
													<td>{{$i}}</td>
													<td>{{$val['nama_aset']}}</td>
													<td>{{$val['jumlah_barang_bukti_aset']}}</td>
													<td>{{$val['nilai_aset']}}</td>
													<td>{{$val['keterangan']}}</td>
			                          			</tr>
			                          		@php $i = $i+1; @endphp
			                          		@endforeach
			                        	@else
			                        		<tr>
			                        			<td colspan="6">
					                        		<div class="alert-messages">
					                          			Data barang bukti belum tersedia.
					                        		</div>
					                        	</td>
				                        	</tr>
			                    		@endif
			                        	</tbody>
			                      	</table>




			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
				                        <thead>
											<tr role="row" class="heading">
												<th width="5%"> No </th>
												<th width="25%"> Nama Aset </th>
												<th width="5%"> Jumlah </th>
												<th width="25%"> Konversi (Rupiah) </th>
												<th width="20%"> Keterangan </th>
											</tr>
				                        </thead>

				                        <tbody>
				                        @if(count($brgBuktiAsetBangunan['data']))
				                          	@php $i = 1; @endphp
					                        @foreach($brgBuktiAsetBangunan['data'] as $val)
					                          	<tr>
						                            <td>{{$i}}</td>
						                            <td>{{$val['nama_aset']}}</td>
						                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
						                            <td>{{$val['nilai_aset']}}</td>
						                            <td>{{$val['keterangan']}}</td>
					                          	</tr>
				                          	@php $i = $i+1; @endphp
				                          	@endforeach
				                        @else
				                        	<tr>
			                        			<td colspan="6">
					                        		<div class="alert-messages">
					                          			Data barang bukti belum tersedia.
					                        		</div>
					                        	</td>
				                        	</tr>
				                        @endif
				                        </tbody>
			                  		</table>

			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
			                        	<thead>
				                          	<tr role="row" class="heading">
					                            <th width="5%"> No </th>
					                            <th width="25%"> Nama Aset </th>
					                            <th width="5%"> Jumlah </th>
					                            <th width="25%"> Konversi (Rupiah) </th>
					                            <th width="20%"> Keterangan </th>
				                          	</tr>
			                        	</thead>
			                        	<tbody>
			                        	@if(count($brgBuktiAsetLogam['data']))
			                          		@php $i = 1; @endphp
			                          		@foreach($brgBuktiAsetLogam['data'] as $val)
												<tr>
													<td>{{$i}}</td>
													<td>{{$val['nama_aset']}}</td>
													<td>{{$val['jumlah_barang_bukti_aset']}}</td>
													<td>{{$val['nilai_aset']}}</td>
													<td>{{$val['keterangan']}}</td>
												</tr>
			                          		@php $i = $i+1; @endphp
			                          		@endforeach
			                       		@else
			                       			<tr>
												<td colspan="6">
													<div class="alert-messages">
							                        	Data barang bukti belum tersedia.
							                        </div>
												</td>
											</tr>
			                        	@endif
			                        	</tbody>
			                      	</table>



			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
			                        	<thead>
				                          	<tr role="row" class="heading">
					                            <th width="5%"> No </th>
					                            <th width="25%"> Nama Aset </th>
					                            <th width="5%"> Jumlah </th>
					                            <th width="25%"> Konversi (Rupiah) </th>
					                            <th width="20%"> Keterangan </th>
				                          	</tr>
			                        	</thead>

			                        	<tbody>
			                        	@if(count($brgBuktiAsetUang['data']))
			                          		@php $i = 1; @endphp
			                          		@foreach($brgBuktiAsetUang['data'] as $val)
			                          		<tr>
					                            <td>{{$i}}</td>
					                            <td>{{$val['nama_aset']}}</td>
					                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
					                            <td>{{$val['nilai_aset']}}</td>
					                            <td>{{$val['keterangan']}}</td>
			                          		</tr>
			                          		@php $i = $i+1; @endphp
			                          		@endforeach
			                        	@else
			                        		<tr>
												<td colspan="6">
													<div class="alert-messages">
							                        	Data barang bukti belum tersedia.
							                        </div>
												</td>
											</tr>
			                        	@endif
			                        	</tbody>
			                      	</table>


			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
				                        <thead>
				                        	<tr role="row" class="heading">
					                            <th width="5%"> No </th>
					                            <th width="25%"> Nama Aset </th>
					                            <th width="5%"> Jumlah </th>
					                            <th width="25%"> Konversi (Rupiah) </th>
					                            <th width="20%"> Keterangan </th>
				                        	</tr>
				                        </thead>
			                        	<tbody>
			                        	@if(count($brgBuktiAsetRekening['data']))

			                        		@php $i = 1; @endphp
			                        		@foreach($brgBuktiAsetRekening['data'] as $val)
				                          		<tr>
				                            		<td>{{$i}}</td>
				                            		<td>{{$val['nama_aset']}}</td>
				                            		<td>{{$val['jumlah_barang_bukti_aset']}}</td>
				                            		<td>{{$val['nilai_aset']}}</td>
				                            		<td>{{$val['keterangan']}}</td>
				                          		</tr>
			                          		@php $i = $i+1; @endphp
			                          		@endforeach
				                        @else
				                        	<tr>
			                        			<td colspan="6">
					                        		<div class="alert-messages">
					                          			Data barang bukti belum tersedia.
					                        		</div>
					                        	</td>
				                        	</tr>
				                        @endif
			                       		</tbody>
				                    </table>



			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
			                        	<thead>
			                          		<tr role="row" class="heading">
					                            <th width="5%"> No </th>
					                            <th width="25%"> Nama Aset </th>
					                            <th width="5%"> Jumlah </th>
					                            <th width="25%"> Konversi (Rupiah) </th>
					                            <th width="20%"> Keterangan </th>
			                          		</tr>
			                        	</thead>

			                        	<tbody>
			                        	@if(count($brgBuktiAsetSurat['data']))
			                          		@php $i = 1; @endphp
			                          		@foreach($brgBuktiAsetSurat['data'] as $val)
			                          		<tr>
			                            		<td>{{$i}}</td>
			                            		<td>{{$val['nama_aset']}}</td>
			                            		<td>{{$val['jumlah_barang_bukti_aset']}}</td>
			                            		<td>{{$val['nilai_aset']}}</td>
			                            		<td>{{$val['keterangan']}}</td>

			                          		</tr>
			                          		@php $i = $i+1; @endphp
			                          		@endforeach
			                   			@else
			                   				<tr>
			                        			<td colspan="6">
					                        		<div class="alert-messages">
					                          			Data barang bukti belum tersedia.
					                        		</div>
					                        	</td>
				                        	</tr>
			                    		@endif
			                        	</tbody>
			                      	</table>
			                    </div>


			                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_nonnarkotika" aria-labelledby="profile-tab">


			                      	<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
			                        	<thead>
			                          		<tr role="row" class="heading">
			                            		<th width="2%"> No </th>
			                            		<th width="15%"> Nama Barang Bukti </th>
			                            		<th width="20%"> Jumlah Barang Bukti </th>
			                          		</tr>
			                        	</thead>
			                        	<tbody>
			                        	@if(count($brgBuktiNonNarkotika['data']))
				                          		@php $i = 1; @endphp
				                          		@foreach($brgBuktiNonNarkotika['data'] as $brgBukti)
				                          			<tr>
				                            			<td>{{$i}}</td>
				                            			<td>{{$brgBukti['keterangan']}}</td>
				                            			<td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
				                          			</tr>
				                          		@php $i = $i+1; @endphp
				                          		@endforeach
			                        	@else
			                        		<tr>
			                        			<td colspan="6">
					                        		<div class=" alert-messages">
					                          			Data barang bukti belum tersedia.
					                        		</div>
					                        	</td>
				                        	</tr>
			                    		@endif
				                       	</tbody>
			                      	</table>
			                    </div>
		                 	</div>
		            	</div>

		            </div>
		      	</div>
		    </div>
		</div>
	</div>
</div>
@include('pemberantasan.intelijen.modal_viewTersangka')
@endsection
