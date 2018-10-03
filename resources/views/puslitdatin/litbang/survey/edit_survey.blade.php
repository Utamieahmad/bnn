@extends('layouts.base_layout')
@section('title', 'Ubah Data Survey ')

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
	                    <h2>Form Ubah Data Survey </h2>
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
	           			<form action="{{URL('/puslitdatin/bidang_litbang/update_survey')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
	           				<input type="hidden" name="id" value="{{$data->id}}"/>
		    				<div class="form-body">

		    					<div class="form-group">
						            <label for="judul_penelitianx" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Penelitian</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{(isset($data->judul_penelitian) ? $data->judul_penelitian : "")}}" id="judul_penelitian" name="judul_penelitian" type="text" class="form-control">
						            </div>
						        </div>

		    					<div class="form-group">
		    						<label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Kelompok Survey</label>
		    						<div class="col-md-6 col-sm-6 col-xs-12">
							          <select class="form-control select2" id="kelompok_survey" name="kelompok_survey" required>
							            @if(isset($kelompok_survey))
							            	@if(count($kelompok_survey) > 0)
							            		@foreach($kelompok_survey as $kkey => $kval)
							           				<option value="{{$kkey}}" {{(( $data->kelompok_survey ==$kkey ) ?'selected=selected' : '')}}> {{$kval}} </option>
							            		@endforeach
							            	@endif
							            @endif
							          </select>
							        </div>
		    					</div>

		    					<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Pelaksanaan</label>
						            <div class='col-md-6 col-sm-6 col-xs-12 input-group date year-only' required>
										<input type='text' name="tahun" value="{{($data->tahun ? $data->tahun : "")}}" class="form-control" />
						                <span class="input-group-addon">
						                <span class="glyphicon glyphicon-calendar"></span>
						                </span>
						            </div>
						        </div>

		    					<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Responden</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{(isset($data->jumlah_responden) ? $data->jumlah_responden : "")}}" id="jumlah_responden" name="jumlah_responden" type="text" class="form-control">
						            </div>
						        </div>

		    					<div class="x_title">
				                    <h2>Prevalensi dan Setara </h2>
				                    <div class="clearfix"></div>
				                </div>

		    					<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Prevalensi Kelompok (%)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{(isset($data->angka_prevalensi) ? $data->angka_prevalensi : "")}}" id="angka_prevalensi" name="angka_prevalensi" type="text" class="form-control numeric" onkeypress="decimal_number(event,this)" Placeholder="00.00">
						            </div>
						        </div>

		    					<div class="form-group">
						            <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Absolut/Setara Kelompok (orang)</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{(isset($data->angka_absolut) ? $data->angka_absolut : "")}}" id="angka_absolut" name="angka_absolut" type="text" class="form-control" onKeydown="numric_only(event,this)">
						            </div>
						        </div>

		    					<div class="form-group">
						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Narkoba</label>
						            <?php
						            	$meta_narkoba = [];
						            	if($data->meta_narkoba){
						            		$meta_narkoba = json_decode($data->meta_narkoba,true);
						            	}else{
						            		$meta_narkoba = [];
						            	}
						            ?>
						            <div class="col-md-9 col-sm-9 col-xs-12">
						                <div class="mt-repeater">
						                    <div data-repeater-list="meta_narkoba">
						                    	@if(count($meta_narkoba) > 0)
						                    		@foreach($meta_narkoba as $nkey => $nval)
								                        <div data-repeater-item class="mt-repeater-item">
								                            <div class="row mt-repeater-row">
								                                <div class="col-md-5 col-sm-5 col-xs-12">
								                                    <label class="control-label">Jenis Narkoba</label>
								                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
															          <select class="form-control mSelect2" id="jenisKasus" name="meta_narkoba[0][jenis_narkoba]" >
															            <option value="">-- Pilih Jenis Narkoba -- </option>
															            @if(count($jenisBrgBuktiNarkotika) > 0)
															              @foreach($jenisBrgBuktiNarkotika as $keyGroup => $jenis )
															                <optgroup label="{{$keyGroup}}">
															                  @foreach($jenis as $key => $val)
															                  <option value="{{preg_replace('/\s+/', '', $key)}}" {{ ( isset($nval['jenis_narkoba']) ? ( (preg_replace('/\s+/', '', $key)== $nval['jenis_narkoba']) ? 'selected=selected' : '')  : '')}}>{{$val}}</option>
															                  @endforeach
															                </optgroup>
															              @endforeach
															            @endif
															          </select>
															        </div>
															    </div>
								                                <div class="col-md-2 col-sm-2 col-xs-12">
							                                    	<label class="control-label">Jumlah (%)</label>
								                                    <input value="{{( isset($nval['jumlah_orang']) ? $nval['jumlah_orang'] : '')}}" name="meta_narkoba[][jumlah_orang]" type="text" class="form-control" onkeypress="decimal_number(event,this)"  Placeholder="00.00">
								                                </div>
								                                <div class="col-md-1 col-sm-1 col-xs-12 text-right p-r-0">
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
								                                <div class="col-md-5 col-sm-5 col-xs-12">
								                                    <label class="control-label">Jenis Narkoba</label>
								                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
															          <select class="form-control mSelect2" id="jenisKasus" name="meta_narkoba[0][jenis_narkoba]" >
															            <option value="">-- Pilih Jenis Narkoba -- </option>
															            @if(count($jenisBrgBuktiNarkotika) > 0)
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
								                                <div class="col-md-2 col-sm-2 col-xs-12">
							                                    	<label class="control-label">Jumlah (%)</label>
								                                    <input value="" name="meta_narkoba[][jumlah_orang]" type="text" class="form-control" onkeypress="decimal_number(event,this)"  Placeholder="00.00">
								                                </div>
								                                <div class="col-md-1 col-sm-1 col-xs-12 text-right p-r-0">
								                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
								                                        <i class="fa fa-close"></i>
								                                    </a>
								                                </div>
								                            </div>
								                        </div>
							                    @endif
						                    </div>
						                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add"  onClick="mSelect2(this)">
						                        <i class="fa fa-plus"></i> Tambah Jenis Narkoba</a>
						                </div>
						            </div>
						        </div>

								<div class="form-group">
						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Data Per Provinsi</label>
						             <?php
						            	$meta_data_provinsi = [];
						            	if($data->meta_data_provinsi){
						            		$meta_data_provinsi = json_decode($data->meta_data_provinsi,true);
						            	}else{
						            		$meta_data_provinsi = [];
						            	}
						            ?>
						            <div class="col-md-9 col-sm-9 col-xs-12">
						                <div class="mt-repeater">
						                    <div data-repeater-list="meta_data">
						                    	@if(count($meta_data_provinsi) > 0 )
						                    		@foreach($meta_data_provinsi as $pkey => $pval)
								                        <div data-repeater-item class="mt-repeater-item">
								                            <div class="row mt-repeater-row">
								                                <div class="col-md-4 col-sm-4 col-xs-12">
								                                    <label class="control-label">Nama Provinsi</label>
								                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
															            <select class="form-control mSelect2" name="meta_data[][id_provinsi]">
															              <option value="">-- Pilih Provinsi -- </option>
															              @if(count($propinsi)>0)
															                @foreach($propinsi as $p)
															                  <option value="{{$p->id_wilayah}}" {{ ( isset($pval['id_provinsi']) ? ( ($p->id_wilayah == $pval['id_provinsi']) ? 'selected="selected"':"" ) : '' )}} > {{$p->nm_wilayah}}</option>
															                @endforeach
															              @endif
															            </select>
															          </div>
															    </div>
								                                <div class="col-md-3 col-sm-3 col-xs-12">
								                                    <label class="control-label">Angka Prevalensi (%)</label>
								                                    <input value="{{(isset($pval['list_prevalensi']) ? $pval['list_prevalensi'] : '' )}}" name="meta_data[][list_prevalensi]" type="text" class="form-control numeric" onkeypress="decimal_number(event,this)"  Placeholder="00.00"> </div>
							                                    <div class="col-md-3 col-sm-3 col-xs-12">
								                                    <label class="control-label">Angka Absolut/Setara</label>
								                                    <input value="{{(isset($pval['list_absolut']) ? $pval['list_absolut'] : '' )}}" name="meta_data[][list_absolut]" type="text" class="form-control" onkeydown="numeric_only(event,this)"> </div>
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
								                                <div class="col-md-4 col-sm-4 col-xs-12">
								                                    <label class="control-label">Nama Provinsi</label>
								                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
															            <select class="form-control mSelect2" name="meta_data[][id_provinsi]">
															              <option value="">-- Pilih Provinsi -- </option>
															              @if(count($propinsi)>0)
															                @foreach($propinsi as $p)
															                  <option value="{{$p->id_wilayah}}" > {{$p->nm_wilayah}}</option>
															                @endforeach
															              @endif
															            </select>
															          </div>
															    </div>
								                                <div class="col-md-3 col-sm-3 col-xs-12">
								                                    <label class="control-label">Angka Prevalensi (%)</label>
								                                    <input value="" name="meta_data[][list_prevalensi]" type="text" class="form-control numeric" onkeypress="decimal_number(event,this)"  Placeholder="00.00"> </div>
							                                    <div class="col-md-3 col-sm-3 col-xs-12">
								                                    <label class="control-label">Angka Absolut/Setara</label>
								                                    <input value="" name="meta_data[][list_absolut]" type="text" class="form-control" onkeydown="numeric_only(event,this)"> </div>
								                                <div class="col-md-1 col-sm-1 col-xs-12">
								                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
								                                        <i class="fa fa-close"></i>
								                                    </a>
								                                </div>
								                            </div>
								                        </div>
								                @endif
						                    </div>
						                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add"  onClick="mSelect2(this)">
						                        <i class="fa fa-plus"></i> Tambah Data</a>
						                </div>
						            </div>
						        </div>

					          	<div class="form-actions fluid">
							        <div class="m-t-20">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">KIRIM</button>
							                <a href="{{url('puslitdatin/bidang_litbang/survey')}}" class="btn btn-primary" type="button">BATAL</a>
							            </div>
							        </div>
							    </div>
		                	</div>
		                	</form>
		                </div>
	            </div>
            </div>

    </div>

@endsection
