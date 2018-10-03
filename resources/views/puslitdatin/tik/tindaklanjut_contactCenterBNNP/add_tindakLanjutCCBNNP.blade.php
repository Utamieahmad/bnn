@extends('layouts.base_layout') 
@section('title', 'Puslitdatin : Tambah Tindak Lanjut Contact Center BNNP/BNNK')


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
	                    <h2>Form Tambah Tindak Lanjut Contact Center BNNP/BNNK</h2>
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
					
	           			<form action="{{URL('/puslitdatin/bidang_tik/save_tindak_lanjut_bnn')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
	           				
		    				<div class="form-body">
		    					<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<select class="form-control select2" name="idpelaksana">
											{!! dropdownPropinsiPelaksana("") !!}
										</select>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group datepicker-only'>
										<input type='text' name="tgl_dibuat" value="" class="form-control" />
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
											</span>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Waktu Melapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group time-only'>
										<input type='text'  name="waktu_melapor" value="" class="form-control" />
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
											</span>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Pelapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text'  name="nama_pelapor" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Agent</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text'  name="nama_agent" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Media</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<div class="radio">
											@if($media_contact)
												@foreach($media_contact as $mkey=>$mvalue)
						                    		<label class="mt-radio col-md-9"> <input type="radio" value="{{$mkey}}" name="kodejenismedia" >
								                    <span>{{$mvalue}} </span>
								                    </label>
							                    @endforeach
							                @endif
						                </div>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Surat</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text'  name="nomor_surat" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Informasi</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text'  name="judul" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Informasi Detail</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<textarea name="deskripsi" class="form-control">  </textarea>

									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tindak Lanjut</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<textarea name="resolusion_close" class="form-control"></textarea>

									</div>
					          	</div>

					          	<div class="form-group">
	              					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text'  name="status" value="" class="form-control" />
									</div>
					          	</div>


					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{url('puslitdatin/bidang_tik/tindak_lanjut_bnn_pusat')}}" class="btn btn-primary" type="button">BATAL</a>
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
