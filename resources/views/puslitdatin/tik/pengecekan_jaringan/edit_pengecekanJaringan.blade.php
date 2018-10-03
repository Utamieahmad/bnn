@extends('layouts.base_layout')
@section('title', 'Ubah Data Pengecekan dan Pemeliharaan Jaringan LAN')

@section('content')
    <div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
                <div class="">
                    {!! $breadcrumps !!}
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
	                    <h2>Form Ubah Data Pengecekan dan Pemeliharaan Jaringan LAN</h2>
	                    <div class="clearfix"></div>
	                </div>
	    			<div class="x_content">
	                <br/>
	                @if (session('status'))
	                @php
	                	$session= session('status');
	                @endphp
    					<div class="alert alert-{{$session['status']}}">
		        			{{ $session['message'] }}
		    			</div>
					@endif
						
	           			<form action="{{route('update_pengecekan_jaringan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">
		    					<input type="hidden" name="id" value="{{$data->id}}"/>
					          	 <div class="form-group">
			                    	<label for="waktu_pengerjaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Waktu Pengerjaan</label>

				                    <div class='col-md-6 col-sm-6 col-xs-12 m-t-10'>
				                      <div class="row">
				                        <div class="col-md-6 col-sm-6 col-xs-12">
				                          <div class="row">
				                            <label for="waktu_pengerjaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
				                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
				                                <input type='text' name="tgl_mulai" class="form-control" value="{{( $data->tgl_mulai ? date('d/m/Y',strtotime($data->tgl_mulai)): '')}}"/>
				                                <span class="input-group-addon">
				                                <span class="glyphicon glyphicon-calendar"></span>
				                                </span>
				                            </div>
				                          </div>
				                        </div>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
				                          <div class="row">
				                            <label for="waktu_pengerjaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
				                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_end'>
				                                <input type='text' name="tgl_selesai" class="form-control" value="{{( $data->tgl_selesai ? date('d/m/Y',strtotime($data->tgl_selesai)): '')}}"/>
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
					                <label for="tempatkejadian_idprovinsi" class="col-md-3 col-sm-3 col-xs-12 control-label">Satuan Kerja</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                  <select class="form-control select2" id="tempatkejadian_idprovinsi" name="tempatkejadian_idprovinsi">
					                    <option value="">-- Pilih Satker --</option>
					                    @if(count($instansi))
					                      @foreach($instansi as $wil)
					                        <option value="{{$wil['id_instansi']}}" {{( ($data->tempatkejadian_idprovinsi == $wil['id_instansi']) ? 'selected=selected' : '')}}> {{$wil['nm_instansi']}}</option>
					                      @endforeach
					                    @endif
					                </select>
					                </div>
				                </div>

					          	<div class="form-group">
				                  	<label for="meta_indikator" class="col-md-3 col-sm-3 col-xs-12 control-label">Tim</label>
				                   <?php
	                          			$meta_tim = [];
	                          			if($data->meta_tim){
	                          				$meta_tim = json_decode($data->meta_tim,true);
	                          			}
	                          		?>
				                  <div class="col-md-6 col-sm-6 col-xs-12">
				                      <div class="mt-repeater">
				                          <div data-repeater-list="meta_tim">
				                          		@if(count($meta_tim) > 0)
				                          			@foreach($meta_tim as $m => $t)
						                              	<div data-repeater-item class="mt-repeater-item">
							                                <div class="row mt-repeater-row">
							                                  	<div class="col-md-11 col-sm-11 col-xs-12">
							                                          <label class="control-label"> Penanggung Jawab</label>
							                                          <input name="meta_tim[][penanggung_jawab]" type="text" class="form-control" value="{{$t['penanggung_jawab']}}"> </div>
							                                    <div class="col-md-11 col-sm-11 col-xs-12">
							                                          <label class="control-label"> Teknisi</label>
							                                          <input name="meta_teknisi[][teknisi]" type="text" class="form-control"  value="{{$t['teknisi']}}"> </div>
							                                    <div class="col-md-1 col-sm-1 col-xs-12">
							                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
							                                              <i class="fa fa-close"></i>
							                                          </a>
							                                    </div>
							                                </div>
				                              			</div>
						                            @endforeach
						                        @else
						                        	<div data-repeater-item class="mt-repeater-item">
						                                <div class="row mt-repeater-row">
						                                  	<div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Penanggung Jawab</label>
						                                          <input name="meta_tim[][penanggung_jawab]" type="text" class="form-control"> </div>
						                                    <div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Teknisi</label>
						                                          <input name="meta_teknisi[][teknisi]" type="text" class="form-control"> </div>
						                                    <div class="col-md-1 col-sm-1 col-xs-12">
						                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
						                                              <i class="fa fa-close"></i>
						                                          </a>
						                                    </div>
						                                </div>
				                              		</div>
						                        @endif
				                          </div>
				                          <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
				                              <i class="fa fa-plus"></i> Tambah </a>
				                      </div>
				                  </div>
				                </div>

					          	<div class="form-group">
				                	<label for="meta_indikator" class="col-md-3 col-sm-3 col-xs-12 control-label">Aktivitas</label>
				                    <?php
				                    	$meta_aktivitas = [];

				                    	if($data->meta_aktivitas){
				                    		$meta_aktivitas = json_decode($data->meta_aktivitas,true);
				                    	}
				                    
				                    ?>	
				                  	
				                	<div class="col-md-6 col-sm-6 col-xs-12">
				                      <div class="mt-repeater">
				                        <div data-repeater-list="meta_aktivitas">
				                        	@if(count($meta_aktivitas)>0)
				                        		@foreach($meta_aktivitas as $mp => $p)
					                            <div data-repeater-item class="mt-repeater-item">
				                                  <div class="row mt-repeater-row">
				                                  	  <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Nama Pengguna</label>
				                                          <input name="meta_aktivitas[][nama_pengguna]"  value="{{$p['nama_pengguna']}}" type="text" class="form-control">
			                                      	  </div>

			                                      	  <ul class="order-dash">
			                                      	  <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <h4><li> Perangkat Jaringan</li></h4>
				                                      </div>
				                                      <div class="clearfix"></div>
			                                          <div class="parent">
				                                           <div class="col-md-11 col-sm-11 col-xs-12">
					                                          <label class="control-label"> Cek Perangkat Jaringan</label>
				                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
											
																	<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  <input type="radio" value="baik" name="meta_aktivitas[][cek_jaringan]" onclick="showHideLabel(this,'baik','ket_jaringan','tdk_baik')" {{ ($p['cek_jaringan'] == 'baik') ? 'checked=checked':''}} >
																		<span>Baik</span>
																	</label>

																	<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> <input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_jaringan]" onclick="showHideLabel(this,'tdk_baik','ket_jaringan','baik')" {{ ($p['cek_jaringan'] == 'tdk_baik') ? 'checked=checked':''}}>
																		<span>Tidak Baik</span>
																	</label>

																</div>
					                                      </div>

					                                      
					                                      <div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_jaringan']) ? '':'hide'}} ket_jaringan">
					                                          <label class="control-label" for="nomor_nota_dinas"> <span class="{{ ($p['cek_jaringan'] == 'baik') ? '':'hide'}} ket_jaringan_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_jaringan'] == 'tdk_baik') ? '':'hide'}} ket_jaringan_tdk_baik">  Keterangan Tidak Baik </span> </label>
					                                          <input type='text'  value="{{$p['ket_jaringan']}}" name="meta_aktivitas[][ket_jaringan]" value="" class="form-control" />
					                                      </div>
					                                  </div>
					                                  	<div class="parent">
															<div class="col-md-11 col-sm-11 col-xs-12">
															  <label class="control-label"> Cek Alamat IP Perangkat</label>
																	<div class="col-md-12 col-sm-12 col-xs-12 radio">

																	<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																		<input type="radio" value="baik" name="meta_aktivitas[][cek_ip]" onclick="showHideLabel(this,'baik','ket_ip','tdk_baik')" {{ ($p['cek_ip'] == 'baik') ? 'checked=checked':''}}>
																		<span>Baik</span>
																	</label>

																	<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																		<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_ip]"  onclick="showHideLabel(this,'tdk_baik','ket_ip','baik')" {{ ($p['cek_ip'] == 'tdk_baik') ? 'checked=checked':''}}>
																		<span>Tidak Baik</span>
																	</label>

																</div>
															</div>

															<div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_ip']) ? '':'hide'}} ket_ip">
																<label class="control-label" for="nomor_nota_dinas"> 
															      	<span class="{{ ($p['cek_ip'] == 'baik') ? '':'hide'}} ket_ip_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_ip'] == 'tdk_baik') ? '':'hide'}} ket_ip_tdk_baik">  Keterangan Tidak Baik </span> 
															    </label>
															 	<input type='text' value="{{$p['ket_ip']}}" name="meta_aktivitas[][ket_ip]" value="" class="form-control" />
															</div>
														</div>

										                <div class="parent">                  
						                                      <div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Ping IP</label>
					                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
												
																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																			<input type="radio" value="baik" name="meta_aktivitas[][cek_ping]" onclick="showHideLabel(this,'baik','ket_ping','tdk_baik')" {{ ($p['cek_ping'] == 'baik') ? 'checked=checked':''}}>
																			<span>Baik</span>
																		</label>

																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																			<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_ping]" onclick="showHideLabel(this,'tdk_baik','ket_ping','baik')" {{ ($p['cek_ping'] == 'tdk_baik') ? 'checked=checked':''}}> 
																			<span>Tidak Baik</span>
																		</label>

																	</div>
						                                      </div>

						                                      <div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_ping']) ? '':'hide'}} ket_ping">
						                                          <label class="control-label" for="nomor_nota_dinas"> <span class="{{ ($p['cek_ping'] == 'baik') ? '':'hide'}} ket_ping_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_ping'] == 'tdk_baik') ? '':'hide'}} ket_ping_tdk_baik">  Keterangan Tidak Baik </span> </label>
						                                          <input type='text' value="{{$p['ket_ping']}}" name="meta_aktivitas[][ket_ping]" value="" class="form-control" />
						                                      </div>
						                                </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <h4><li> Pemeliharaan Fisik</li></h4>
				                                      </div>
				                                      <div class="clearfix"></div>
				                                      	<div class="parent">
						                                      <div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Cek Kondisi Switch</label>
					                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
												
																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																			<input type="radio" value="baik" name="meta_aktivitas[][cek_switch]" onclick="showHideLabel(this,'baik','ket_switch','tdk_baik')"  {{ ($p['cek_switch'] == 'baik') ? 'checked=checked':''}}>
																			<span>Baik</span>
																		</label>

																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																			<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_switch]" onclick="showHideLabel(this,'tdk_baik','ket_switch','baik')" {{ ($p['cek_switch'] == 'tdk_baik') ? 'checked=checked':''}}>
																			<span>Tidak Baik</span>
																		</label>
																	</div>
						                                      </div>

						                                      <div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_switch']) ? '':'hide'}} ket_switch">
						                                          <label class="control-label" for="nomor_nota_dinas"> <span class="{{ ($p['cek_switch'] == 'baik') ? '':'hide'}} ket_switch_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_switch'] == 'tdk_baik') ? '':'hide'}} ket_switch_tdk_baik">  Keterangan Tidak Baik </span></label>
						                                          <input type='text' value="{{$p['ket_switch']}}"  name="meta_aktivitas[][ket_switch]" value="" class="form-control" />
						                                      </div>
				                                      </div>
				                                      	<div class="parent">
						                                      <div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Cek Kondisi Switch Manageable</label>
					                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
												
																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																			<input type="radio" value="baik" name="meta_aktivitas[][cek_manageable]"  onclick="showHideLabel(this,'baik','ket_manageable','tdk_baik')" {{ ($p['cek_manageable'] == 'baik') ? 'checked=checked':''}}>
																			<span>Baik</span>
																		</label>

																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																			<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_manageable]"  onclick="showHideLabel(this,'tdk_baik','ket_manageable','baik')" {{ ($p['cek_manageable'] == 'tdk_baik') ? 'checked=checked':''}}>
																			<span>Tidak Baik</span>
																		</label>

																	</div>
						                                      </div>

						                                      <div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_manageable']) ? '':'hide'}} ket_manageable">
						                                          <label class="control-label" for="nomor_nota_dinas"> <span class="{{ ($p['cek_manageable'] == 'baik') ? '':'hide'}} ket_manageable_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_manageable'] == 'tdk_baik') ? '':'hide'}} ket_manageable_tdk_baik">  Keterangan Tidak Baik </span></label>
						                                          <input type='text' value="{{$p['ket_manageable']}}" name="meta_aktivitas[][ket_manageable]" value="" class="form-control" />
						                                      </div>
				                                      </div>
				                                      	<div class="parent">
						                                      <div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Cek Kondisi Kabel UTP</label>
					                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
												
																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																			<input type="radio" value="baik" name="meta_aktivitas[][cek_kabel]" onclick="showHideLabel(this,'baik','ket_kabel','tdk_baik')" {{ ($p['cek_kabel'] == 'baik') ? 'checked=checked':''}}>
																			<span>Baik</span>
																		</label>

																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																			<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_kabel]" onclick="showHideLabel(this,'tdk_baik','ket_kabel','baik')" {{ ($p['cek_kabel'] == 'tdk_baik') ? 'checked=checked':''}}>
																			<span>Tidak Baik</span>
																		</label>

																	</div>
						                                      </div>

						                                      <div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_kabel']) ? '':'hide'}} ket_kabel">
						                                          <label class="control-label" for="nomor_nota_dinas"> <span class="{{ ($p['cek_kabel'] == 'baik') ? '':'hide'}} ket_kabel_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_kabel'] == 'tdk_baik') ? '':'hide'}} ket_kabel_tdk_baik">  Keterangan Tidak Baik </span></label>
						                                          <input type='text' value="{{$p['ket_kabel']}}" name="meta_aktivitas[][ket_kabel]" value="" class="form-control" />
						                                      </div>
				                                      </div>
				                                      	<div class="parent">
						                                      <div class="col-md-11 col-sm-11 col-xs-12">
						                                          <label class="control-label"> Cek Kondisi Wireless</label>
					                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
												
																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																			<input type="radio" value="baik" name="meta_aktivitas[][cek_wireless]"  onclick="showHideLabel(this,'baik','ket_wireless','tdk_baik')" {{ ($p['cek_wireless'] == 'baik') ? 'checked=checked':''}}> 
																			<span>Baik</span>
																		</label>

																		<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																			<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_wireless]" onclick="showHideLabel(this,'tdk_baik','ket_wireless','baik')" {{ ($p['cek_wireless'] == 'tdk_baik') ? 'checked=checked':''}}>
																			<span>Tidak Baik</span>
																		</label>

																	</div>
						                                      </div>

						                                      <div class="col-md-11 col-sm-11 col-xs-12 {{ ($p['cek_wireless']) ? '':'hide'}} ket_wireless">
						                                          <label class="control-label" for="nomor_nota_dinas"> <span class="{{ ($p['cek_wireless'] == 'baik') ? '':'hide'}} ket_wireless_baik"> Keterangan Baik </span> <span class="{{ ($p['cek_wireless'] == 'tdk_baik') ? '':'hide'}} ket_wireless_tdk_baik">  Keterangan Tidak Baik </span></label>
						                                          <input type='text' value="{{$p['ket_wireless']}}"  name="meta_aktivitas[][ket_wireless]" value="" class="form-control" />
						                                      </div>
				                                      </div>
				                                  	</ul>

				                                      <div class="col-md-1 col-sm-1 col-xs-12">
				                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
				                                              <i class="fa fa-close"></i>
				                                          </a>
				                                      </div>
				                                  </div>
				                              </div>
						                        @endforeach
						                    @else
						                    	<div data-repeater-item class="mt-repeater-item">
				                                  <div class="row mt-repeater-row">
				                                  	  <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Nama Pengguna</label>
				                                          <input name="meta_aktivitas[][nama_pengguna]" type="text" class="form-control">
			                                      	  </div>

			                                      	  <ul class="order-dash">
			                                      	  <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <h4><li> Perangkat Jaringan</li></h4>
				                                      </div>
				                                      <div class="clearfix"></div>
			                                          
			                                          <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Cek Perangkat Jaringan</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  <input type="radio" value="baik" name="meta_aktivitas[][cek_jaringan]" onclick="showHideLabel(this,'baik','ket_jaringan','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> <input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_jaringan]" onclick="showHideLabel(this,'tdk_baik','ket_jaringan','baik')">
																	<span>Tidak Baik</span>
																</label>

															</div>
				                                      </div>

				                                      
				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_jaringan">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_jaringan_baik"> Keterangan Baik </span> <span class="hide ket_jaringan_tdk_baik">  Keterangan Tidak Baik </span> </label>
				                                          <input type='text' name="meta_aktivitas[][ket_jaringan]" value="" class="form-control" />
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Cek Alamat IP Perangkat</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																	<input type="radio" value="baik" name="meta_aktivitas[][cek_ip]" onclick="showHideLabel(this,'baik','ket_ip','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																	<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_ip]"  onclick="showHideLabel(this,'tdk_baik','ket_ip','baik')">
																	<span>Tidak Baik</span>
																</label>

															</div>
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_ip">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_ip_baik"> Keterangan Baik </span> <span class="hide ket_ip_tdk_baik">  Keterangan Tidak Baik </span> </label>
				                                          <input type='text' name="meta_aktivitas[][ket_ip]" value="" class="form-control" />
				                                      </div>

				                                      
				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Ping IP</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																	<input type="radio" value="baik" name="meta_aktivitas[][cek_ping]" onclick="showHideLabel(this,'baik','ket_ping','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																	<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_ping]" onclick="showHideLabel(this,'tdk_baik','ket_ping','baik')">
																	<span>Tidak Baik</span>
																</label>

															</div>
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_ping">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_ping_baik"> Keterangan Baik </span> <span class="hide ket_ping_tdk_baik">  Keterangan Tidak Baik </span> </label>
				                                          <input type='text' name="meta_aktivitas[][ket_ping]" value="" class="form-control" />
				                                      </div>


				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <h4><li> Pemeliharaan Fisik</li></h4>
				                                      </div>
				                                      <div class="clearfix"></div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Cek Kondisi Switch</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																	<input type="radio" value="baik" name="meta_aktivitas[][cek_switch]" onclick="showHideLabel(this,'baik','ket_switch','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																	<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_switch]" onclick="showHideLabel(this,'tdk_baik','ket_switch','baik')">
																	<span>Tidak Baik</span>
																</label>
															</div>
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_switch">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_switch_baik"> Keterangan Baik </span> <span class="hide ket_switch_tdk_baik">  Keterangan Tidak Baik </span></label>
				                                          <input type='text' name="meta_aktivitas[][ket_switch]" value="" class="form-control" />
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Cek Kondisi Switch Manageable</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																	<input type="radio" value="baik" name="meta_aktivitas[][cek_manageable]"  onclick="showHideLabel(this,'baik','ket_manageable','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																	<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_manageable]"  onclick="showHideLabel(this,'tdk_baik','ket_manageable','baik')">
																	<span>Tidak Baik</span>
																</label>

															</div>
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_manageable">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_manageable_baik"> Keterangan Baik </span> <span class="hide ket_manageable_tdk_baik">  Keterangan Tidak Baik </span></label>
				                                          <input type='text' name="meta_aktivitas[][ket_manageable]" value="" class="form-control" />
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Cek Kondisi Kabel UTP</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																	<input type="radio" value="baik" name="meta_aktivitas[][cek_kabel]" onclick="showHideLabel(this,'baik','ket_kabel','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																	<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_kabel]" onclick="showHideLabel(this,'tdk_baik','ket_kabel','baik')">
																	<span>Tidak Baik</span>
																</label>

															</div>
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_kabel">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_kabel_baik"> Keterangan Baik </span> <span class="hide ket_kabel_tdk_baik">  Keterangan Tidak Baik </span></label>
				                                          <input type='text' name="meta_aktivitas[][ket_kabel]" value="" class="form-control" />
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12">
				                                          <label class="control-label"> Cek Kondisi Wireless</label>
			                                          		<div class="col-md-12 col-sm-12 col-xs-12 radio">
										
																<label class="mt-radio col-md-3 col-sm-3 col-xs-12">  
																	<input type="radio" value="baik" name="meta_aktivitas[][cek_wireless]"  onclick="showHideLabel(this,'baik','ket_wireless','tdk_baik')">
																	<span>Baik</span>
																</label>

																<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
																	<input type="radio" value="tdk_baik" name="meta_aktivitas[][cek_wireless]" onclick="showHideLabel(this,'tdk_baik','ket_wireless','baik')">
																	<span>Tidak Baik</span>
																</label>

															</div>
				                                      </div>

				                                      <div class="col-md-11 col-sm-11 col-xs-12 hide ket_wireless">
				                                          <label class="control-label" for="nomor_nota_dinas"> <span class="hide ket_wireless_baik"> Keterangan Baik </span> <span class="hide ket_wireless_tdk_baik">  Keterangan Tidak Baik </span></label>
				                                          <input type='text' name="meta_aktivitas[][ket_wireless]" value="" class="form-control" />
				                                      </div>
				                                  	</ul>

				                                      <div class="col-md-1 col-sm-1 col-xs-12">
				                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
				                                              <i class="fa fa-close"></i>
				                                          </a>
				                                      </div>
				                                  </div>
				                              </div>
						                    @endif
				                        </div>
				                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
				                              <i class="fa fa-plus"></i> Tambah </a>
				                      </div>
				                  </div>
				                </div>

				                

					          	<div class="form-actions fluid">
							        <div class="m-t-20">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{route('pengecekan_jaringan')}}" class="btn btn-primary" type="button">BATAL</a>
							            </div>
							        </div>
							    </div>
					          	
		                	</div>
	                	</form>
	                </div>
	            </div>
            </div>
        </div>
    </div>
@endsection
