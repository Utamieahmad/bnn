@extends('layouts.base_layout')
@section('title', 'Tambah Data Magang')

@section('content')
    <div class="right_col mSelect" role="main">
        <div class="m-t-40">
            <div class="page-title">
                <div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
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
	                    <h2>Form Tambah Magang Balai Besar</h2>
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
		           			<form action="{{route('save_magang')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
		           				{{ csrf_field()}}
			    				<div class="form-body">
			    					<div class="form-group">
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">

							            	<select class="form-control select2" name="jenis_kegiatan" required>
							            		<option value="">-- Pilih Kegiatan --</option>
							                	@if(isset($jenis_kegiatan))
								                	@if(count($jenis_kegiatan))
								              			@foreach($jenis_kegiatan as $jkode=>$jvalue)
								              				<option value="{{$jkode}}">{{$jvalue}}</option>
								              			@endforeach
								              		@endif
							              		@endif
						              		</select>
							            </div>
							        </div>

			    					<div class="form-group">
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
					             			<input type='text' name="nama_kegiatan" value="" class="form-control"/>
							            </div>
							        </div>

			    					<div class="form-group">
							            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Periode Pelaksanaan</label>

							            <div class='col-md-6 col-sm-6 col-xs-12'>
							            	<div class="row">
							            		<div class="col-md-6 col-sm-6 col-xs-12">
							            			<div class="row">
										            	<label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
											            <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
											                <input type='text' name="tanggal_mulai" class="form-control" value=""/>
											                <span class="input-group-addon">
											                <span class="glyphicon glyphicon-calendar"></span>
											                </span>
											            </div>
										            </div>
									            </div>
									            <div class="col-md-6 col-sm-6 col-xs-12">
									            	<div class="row">
										            	<label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
											            <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_end'>
											                <input type='text' name="tanggal_selesai" class="form-control" value=""/>
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
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomer Agenda</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
					             			<input type='text' name="nomor_agenda" value="" class="form-control"/>
							            </div>
							        </div>

			    					<div class="form-group">
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomer Surat</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
					             			<input type='text' name="nomor_surat" value="" class="form-control"/>
							            </div>
							        </div>

			    					<div class="form-group">
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Instansi</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
					             			<div class="radio" id='buttons'>
							                	@if(isset($instansi))
								                	@if(count($instansi))
								              			@foreach($instansi as $ikode=>$ivalue)
								              					@php
								              						if(strtolower($ikode) == 'bnn'){
								              							$show = "true";
									              					}else{
									              						$show = "false";
								              						}
								              					@endphp
								              					<label class="col-md-9">
									              					<input type="radio" value="{{$ikode}}" name="kode_instansi" onClick="select_instansi(event,this,{{$show}})">
												                    <span>{{$ivalue}}</span>
											                    </label>
								              			@endforeach
								              		@endif
							              		@endif
							                </div>
							            </div>
							        </div>

			    					<div class="form-group">
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Detail Instansi</label>
							            <div class="col-md-8 bnn-radio hide">
							                <div class="mt-repeater">
							                    <div data-repeater-list="bnn_detail_instansi">
							                        <div data-repeater-item="" class="mt-repeater-item">
							                            <div class="row mt-repeater-row">
							                                <div class="col-md-6 col-sm-6 col-xs-12">

							                                    <label class="control-label">Nama Peserta</label>
							                                    <select class="form-control mSelect2" name="bnn_detail_instansi[0][nama_peserta]" >
							                                    	<option value=""> -- Pilih Satuan Kerja --</option>
								                                    @if(isset($satker))
								                                    	@if(count($satker)>0)
								                                    		@foreach($satker as $s=>$val)
								                                    			<option value="{{$val->id}}-{{$val->nama}}">{{$val->nama}}</option>
								                                    		@endforeach
								                                    	@endif
								                                    @endif
							                                    </select>
							                                  </div>
						                                  	<div class="col-md-3 col-sm-3 col-xs-12">
							                                	<div class="row">
								                                    <label class="control-label">Jumlah Peserta</label>
								                                    <input name="bnn_detail_instansi[0][jumlah_peserta]" value="" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div>
							                                </div>
							                                <div class="col-md-1 col-sm-1 col-xs-12">
							                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
							                                        <i class="fa fa-close"></i>
							                                    </a>
							                                </div>
							                            </div>
							                        </div>
							                    </div>
							                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add" onClick="mSelect2(this)">
							                        <i class="fa fa-plus"></i> Tambah Instansi</a>
							                </div>
							            </div>

							            <div class="col-md-8 non-bnn-radio">
							                <div class="mt-repeater">
							                    <div data-repeater-list="detail_instansi">
							                        <div data-repeater-item="" class="mt-repeater-item">
							                            <div class="row mt-repeater-row">
							                                <div class="col-md-6 col-sm-6 col-xs-12">
							                                    <label class="control-label">Nama Instansi</label>
							                                    <input name="detail_instansi[0][nama_instansi]" value="" type="text" class="form-control"> </div>
							                                <div class="col-md-3 col-sm-3 col-xs-12">
							                                	<div class="row">
								                                    <label class="control-label">Jumlah Peserta</label>
								                                    <input name="detail_instansi[0][jumlah_peserta]" value="" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div>
								                                </div>
							                                <div class="col-md-1 col-sm-1 col-xs-12">
							                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
							                                        <i class="fa fa-close"></i>
							                                    </a>
							                                </div>
							                            </div>
							                        </div>
							                    </div>
							                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
							                        <i class="fa fa-plus"></i> Tambah Instansi</a>
							                </div>
							            </div>
						            </div>

			    					<div class="form-group m-t-20">
							            <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
							            <div class="col-md-8 col-sm-8 col-xs-12">
							                <div class="mt-repeater">
							                    <div data-repeater-list="materi">
							                        <div data-repeater-item="" class="mt-repeater-item">
							                            <div class="row mt-repeater-row">
							                                <div class="col-md-5 col-sm-5 col-xs-12">
						                                    	<label class="control-label">Narasumber</label>
							                                    <input name="materi[0][narasumber]" value="" type="text" class="form-control" > </div>
							                                <div class="col-md-4 col-sm-4 col-xs-12">
							                                	<div class="row">
								                                    <label class="control-label">Judul Materi</label>
								                                    <textarea name="materi[0][judul_materi]" class="form-control"> </textarea>
							                               		</div>
							                                </div>
							                                <div class="col-md-1 col-sm-1 col-xs-12">
							                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
							                                        <i class="fa fa-close"></i>
							                                    </a>
							                                </div>
							                            </div>
							                        </div>
							                    </div>
							                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
							                        <i class="fa fa-plus"></i> Tambah Materi</a>
							                </div>
							            </div>
							        </div>

			    					<div class="form-group m-t-20">
	                                    <label for="file_nspk" class="col-md-3 control-label">Laporan</label>
	                                    <div class="col-md-6 col-sm-6 col-xs-12">
	                                        <div class="fileinput fileinput-new" data-provides="fileinput">
	                                            <div class="input-group input-large">
	                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
	                                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
	                                                    <span class="fileinput-filename"> </span>
	                                                </div>
	                                                <span class="input-group-addon btn default btn-file">
	                                                    <span class="fileinput-new"> Pilih Berkas </span>
	                                                    <span class="fileinput-exists"> Ganti </span>
	                                                    <input type="file" name="file_laporan"> </span>
	                                                <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
							        </div>



					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{route('data_magang')}}" class="btn btn-primary" type="button">BATAL</a>
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
