@extends('layouts.base_layout')
@section('title', 'Tambah Data Survey Nasional Penyalahgunaan Narkoba di Indonesia')

@section('content')
    <div class="right_col mSelect" role="main">
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
	                    <h2>Form Tambah Survey Nasional Penyalahgunaan Narkoba di Indonesia</h2>
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
	           			<form action="{{URL('/puslitdatin/bidang_litbang/save_survey_narkoba')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">

		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Pelaksanaan</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-group date year-only' >
										<input type='text' name="tahun" value="" class="form-control" required/>
						                <span class="input-group-addon">
						                <span class="glyphicon glyphicon-calendar"></span>
						                </span>
						            </div>
					          	</div>

					          	<div class="form-group">
						            <label for="jumlah_populasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Populasi (Usia 10-59)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="jumlah_populasi" name="jumlah_populasi" type="text" class="form-control" onkeydown="numeric_only(event,this)" required>
						            </div>
						        </div>


					          	<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Responden</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="jumlah_responden" name="jumlah_responden" type="text" class="form-control" onkeydown="numeric_only(event,this)" required>
						            </div>
						        </div>

						        <div class="clear"></div>
					          	<br/>
					          	<div class="form-group">
					          		<label class="control-label col-md-3 col-sm-3 col-xs-3"> Prevalensi dan Setara </label>

			                        {{-- <div class="col-md-9">
			                          <div class="col-md-4 col-sm-4 col-xs-12">
			                              <label class="">Angka Prevalensi Nasional (%)</label>
			                          </div>
			                          <div class="col-md-4 col-sm-4 col-xs-12">
			                              <label class="">Angka Absolut/Setara Nasional (Orang)</label>
			                          </div>
			                        </div> --}}

					          	</div>
					          	<div class="clear"></div>
					          	<br/>

					          	<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Prevalensi Nasional (%)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						               <input value="" id="angka_prevalensi_thn1" name="angka_prevalensi_thn1" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Absolut/Setara Nasional (Orang)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="angka_absolut_thn1" name="angka_prevalensi_thn1" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>
					          	
                      {{-- <div class="form-group">
  						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +1</label>
  						            <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_prevalensi_thn1" name="angka_prevalensi_thn1" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_absolut_thn1" name="angka_absolut_thn1" type="text" class="form-control" onKeypress="numeric_only(event,this)" Placeholder="00">
                            </div>
                          </div>
                      </div>

                      <div class="form-group">
  						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
  						            <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_prevalensi_thn2" name="angka_prevalensi_thn2" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_absolut_thn2" name="angka_absolut_thn2" type="text" class="form-control" onKeypress="numeric_only(event,this)" Placeholder="00">
                            </div>
                          </div>
                      </div>

                      <div class="form-group">
  						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
  						            <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_prevalensi_thn3" name="angka_prevalensi_thn3" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_absolut_thn3" name="angka_absolut_thn3" type="text" class="form-control" onKeypress="numeric_only(event,this)" Placeholder="00">
                            </div>
                          </div>
                      </div>

                      <div class="form-group">
  						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
  						            <div class="col-md-9 col-sm-4 col-xs-12">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_prevalensi_thn4" name="angka_prevalensi_thn4" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_absolut_thn4" name="angka_absolut_thn4" type="text" class="form-control" onKeypress="numeric_only(event,this)" Placeholder="00">
                            </div>
                          </div>
                      </div>

                      <div class="form-group">
  						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
  						            <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_prevalensi_thn5" name="angka_prevalensi_thn5" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="" id="angka_absolut_thn5" name="angka_absolut_thn5" type="text" class="form-control" onKeypress="numeric_only(event,this)" Placeholder="00">
                            </div>
                          </div>
                      </div> --}}
					          	{{-- <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Prevalensi Nasional (%)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="angka_prevalensi" name="angka_prevalensi" type="text" onKeypress="decimal_number(event,this)" Placeholder="00.00" class="form-control">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Absolut/Setara Nasional (orang)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="angka_absolut" name="angka_absolut" type="text" class="form-control" onKeydown="numeric_only(event,this)">
						            </div>
						        </div> --}}

					          	<div class="clear"></div>
					          	<br/>
					          	<div class="form-group">
					          		<label class="control-label col-md-3 col-sm-3 col-xs-3"> Proyeksi Prevalensi Nasional (%) </label>
					          		<div class="col-md-6 col-sm-6 col-xs-12">
					          		</div>
					          	</div>
					          	<div class="clear"></div>
					          	<br/>

					          	<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +1</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="prevalensi_thn1" name="prevalensi_thn1" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="prevalensi_thn2" name="prevalensi_thn2" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="prevalensi_thn3" name="prevalensi_thn3" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="prevalensi_thn4" name="prevalensi_thn4" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="prevalensi_thn5" name="prevalensi_thn5" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Kematian (Orang)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="angka_kematian" name="angka_kematian" type="text" class="form-control" onKeydown="numeric_only(event,this)">
						            </div>
						        </div>

						        <div class="form-group">
						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Narkoba</label>
						            <div class="col-md-8 col-sm-8 col-xs-12">
						                <div class="mt-repeater">
						                    <div data-repeater-list="meta_narkoba">
						                        <div data-repeater-item class="mt-repeater-item">
						                            <div class="row mt-repeater-row">
						                                <div class="col-md-5 col-sm-5 col-xs-12">
						                            	<div class="col-md-7 col-sm-7 col-xs-12">
														<label class="control-label">Jenis Narkoba</label>
														</div>
														
						                                    <label class="control-label"></label>
						                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0 m-t-15">
													          <select class="form-control mSelect2" id="jenisKasus" name="meta_narkoba[][jenisKasus]" >
													            <option value="">-- Pilih Jenis Narkoba -- </option>
													            @if(isset($jenisBrgBuktiNarkotika))
													              @foreach($jenisBrgBuktiNarkotika as $keyGroup => $jenis )
													                <optgroup label="{{$keyGroup}}">
													                  @foreach($jenis as $key => $val)
													                  <option value="{{preg_replace('/\s+/', '', $key)}}">{{$val}}</option>
													                  @endforeach
													                </optgroup>
													              @endforeach
													            @endif
													          </select>
													        </div>
													    </div>
													    <div class="col-md-5 col-sm-5 col-xs-12">
														<label class="">Angka Prevalensi Penyalah Guna Berdasarkan Jenis Narkoba</label>
														</div>
						                                <div class="col-md-4 col-sm-4 col-xs-12 p-r-0">
						                                    
						                                    <input name="meta_narkoba[][jumlah_prosentase]" type="text" class="form-control" onkeypress="decimal_number(event,this)" Placeholder="00.00"> </div>
						                                
						                                </div>
						                                <div class="row mt-repeater-row">
						                                <div class="col-md-5 col-sm-5 col-xs-12">
						                                    <label class="control-label">Jumlah (Orang)</label>
						                                    <input name="meta_narkoba[][jumlah_orang]" type="text" class="form-control" onkeydown="numeric_only(event,this)"> </div>
					                                    <div class="col-md-1 col-sm-1 col-xs-12">
						                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
						                                        <i class="fa fa-close"></i>
						                                    </a>
						                                </div>
						                                
						                            </div>
						                        </div>
						                    </div>
						                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add" onClick="mSelect2(this)">
						                        <i class="fa fa-plus"></i> Tambah Jenis Narkoba</a>
						                </div>
						            </div>
						        </div>

						        <br/>

						        <div class="x_title">
				                    <h5>Kerugian Biaya Ekonomi dan Sosial Akibat Penyalahgunaan Narkoba (Rupiah)</h5>
				                    <div class="clearfix"></div>
				                </div>
				                <div class="clearfix"></div>


					          	<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="kerugian_thn1" name="kerugian_thn1" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						       {{-- <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="kerugian_thn2" name="kerugian_thn2" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="kerugian_thn3" name="kerugian_thn3" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="kerugian_thn4" name="kerugian_thn4" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="kerugian_thn5" name="kerugian_thn5" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div> --}}

						        <div class="x_title">
				                    <h5>Proyeksi Kerugian Biaya Ekonomi dan Sosial (Rupiah)</h5>
				                    <div class="clearfix"></div>
				                </div>
				                <div class="clearfix"></div>

				                <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +1</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="proyeksi_thn1" name="proyeksi_thn1" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="proyeksi_thn2" name="proyeksi_thn2" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="proyeksi_thn3" name="proyeksi_thn3" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="proyeksi_thn4" name="proyeksi_thn4" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="" id="proyeksi_thn5" name="proyeksi_thn5" type="text" class="form-control" onKeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>


						    </div>

						    <div class="form-group">
						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Data Per Provinsi</label>
						            <div class="col-md-9 col-sm-9 col-xs-12">
						                <div class="mt-repeater">
						                    <div data-repeater-list="meta_data_provinsi">
						                        <div data-repeater-item class="mt-repeater-item">
						                            <div class="row mt-repeater-row">
						                                <div class="col-md-4 col-sm-4 col-xs-12">
						                                    <label class="control-label">Nama Provinsi</label>
						                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
													            <select class="form-control mSelect2" name="meta_data_provinsi[][id_provinsi]">
													              <option value="">-- Pilih Provinsi -- </option>
													              @if(count($propinsi) > 0)
													                @foreach($propinsi as $p)
													                  <option value="{{$p->id_wilayah}}" > {{$p->nm_wilayah}}</option>
													                @endforeach
													              @endif
													            </select>
													          </div>
													    </div>
						                                <div class="col-md-3 col-sm-3 col-xs-12">
						                                    <label class="control-label">Angka Prevalensi (%)</label>
						                                    <input name="meta_data_provinsi[][list_prevalensi]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00"> </div>
					                                    <div class="col-md-3 col-sm-3 col-xs-12">
						                                    <label class="control-label">Angka Absolut/Setara</label>
						                                    <input name="meta_data_provinsi[][list_absolut]" type="text" class="form-control numeric" onkeydown="numeric_only(event,this)"> </div>

						                                <div class="clearfix"></div>
						                                <div class="col-md-6 col-sm-6 col-xs-12">
						                                    <label class="control-label">Proyeksi Angka Prevalensi per Provinsi (%)</label>
						                                </div>
						                                <div class="clearfix"></div>
						                                <div class="row">
							                                <div class="col-md-12 col-sm-12 col-xs-12 ">
							                                	<div class="col-md-2 col-sm-2 col-xs-12 ">
							                                		<label class="control-label">Tahun +1</label>
							                                		<input name="meta_data_provinsi[][proyeksi_prevalensi_1]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12">
							                                		<label class="control-label">Tahun +2</label>
							                                		<input name="meta_data_provinsi[][proyeksi_prevalensi_2]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12">
							                                		<label class="control-label">Tahun +3</label>
							                                		<input name="meta_data_provinsi[][proyeksi_prevalensi_3]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12">
							                                		<label class="control-label">Tahun +4</label>
							                                		<input name="meta_data_provinsi[][proyeksi_prevalensi_4]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12 ">
							                                		<label class="control-label">Tahun +5</label>
							                                		<input name="meta_data_provinsi[][proyeksi_prevalensi_5]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                </div>
						                                </div>

					                                    <div class="col-md-10 col-sm-10 col-xs-12">
					                                    	<label class="control-label"><strong> Kerugian Biaya Ekonomi dan Sosial Provinsi (Rupiah) </strong></label>
						                                </div>
					                                    <div class="col-md-4 col-sm-4 col-xs-12">
						                                    <label class="control-label"> </label>
						                                    <input name="meta_data_provinsi[][kerugian]" type="text" class="form-control numeric" onkeypress="decimal_number(event,this)"  Placeholder="00.00">
						                                </div>

						                                <div class="clearfix"></div>
						                                <div class="col-md-7 col-sm-7 col-xs-12">
						                                    <label class="control-label">Proyeksi Kerugian Biaya Ekonomi dan Sosial per Provinsi (Rupiah)</label>
						                                </div>
						                                <div class="clearfix"></div>
						                                <div class="row">
							                                <div class="col-md-12 col-sm-12 col-xs-12 ">
							                                	<div class="col-md-2 col-sm-2 col-xs-12 ">
							                                		<label class="control-label">Tahun +1</label>
							                                		<input name="meta_data_provinsi[][proyeksi_kerugian_1]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12">
							                                		<label class="control-label">Tahun +2</label>
							                                		<input name="meta_data_provinsi[][proyeksi_kerugian_2]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12">
							                                		<label class="control-label">Tahun +3</label>
							                                		<input name="meta_data_provinsi[][proyeksi_kerugian_3]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12">
							                                		<label class="control-label">Tahun +4</label>
							                                		<input name="meta_data_provinsi[][proyeksi_kerugian_4]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-2 col-sm-2 col-xs-12 ">
							                                		<label class="control-label">Tahun +5</label>
							                                		<input name="meta_data_provinsi[][proyeksi_kerugian_5]" type="text" class="form-control " onkeypress="decimal_number(event,this)" Placeholder="00.00">
							                                	</div>
							                                	<div class="col-md-1 col-sm-1 col-xs-12">
								                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
								                                        <i class="fa fa-close"></i>
								                                    </a>
								                                </div>
							                                </div>
						                                </div>
						                                
						                            </div>
						                        </div>
						                    </div>
						                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add" onClick="mSelect2(this)">
						                        <i class="fa fa-plus"></i> Tambah Data</a>
						                </div>
						            </div>
					        </div>

				          	<div class="form-actions fluid">
						        <div class="m-t-20">
						            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						                <button type="submit" class="btn btn-success">SIMPAN</button>
						                <a href="{{url('puslitdatin/bidang_litbang/survey_narkoba')}}" class="btn btn-primary" type="button">BATAL</a>
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
