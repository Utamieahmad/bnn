@extends('layouts.base_layout')
@section('title', 'Puslitdatin : Tambah Informasi Melalui Contact Center')

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
	                    <h2>Form Tambah Informasi Masuk Melalui Contact Center</h2>
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
					
	           			<form action="{{URL('/puslitdatin/bidang_tik/save_informasi_melalui_contact_center')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group datepicker-only'>
										<input type='text' name="tgl_dibuat" value="" class="form-control" />
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
											</span>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="nama_pelapor" value="" class="form-control" />
									</div>
					          	</div>
					          	
					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Media</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<div class="radio">
											@if($media_contact)
												@foreach($media_contact as $mkey=>$mvalue)
						                    		<label class="mt-radio col-md-9"> <input type="radio" value="{{$mkey}}" name="kodejenismedia" >
								                    <span>{{$mvalue}}</span>
								                    </label>
							                    @endforeach
							                @endif
						                </div>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Pelapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="alamatpelapor" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Pelapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
	              						{!! dropdownPropinsiKabupaten("", 'alamatpelapor_idkabkota') !!}
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Kode Sub Klarifikasi</label>
	              					<div class='col-md-9 col-sm-9 col-xs-12 input-group'>
										<div class="mt-radio-list">
											@if($codes)
												@foreach($codes as $ckey=>$cvalue)
								                    @if($cvalue)
									                    <div class="col-md-12 col-sm-12 col-xs-12">
								                    		<label class="mt-radio label-group"> <span>{{(isset($labels[$ckey]) ? $labels[$ckey] : $ckey )}}</span></label>
									                    	<div class="radio">
										                    	@foreach($cvalue as $kode => $kode_label)
										                    		<label class="mt-radio col-md-9"> <input type="radio" value="{{$kode}}" name="kodesubklasifikasi">
												                    <span>{{$kode_label}}</span>
												                    </label>
										                    	@endforeach
									                    	</div>
									                    </div>
									                @endif
							                    @endforeach
							                @endif
						                </div>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Agent</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="nama_agent" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Informasi</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="informasi" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tindak Lanjut</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<textarea name="tindak_lanjut"  class="form-control"> </textarea>
									</div>
					          	</div>

					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{url('puslitdatin/bidang_tik/informasi_melalui_contact_center')}}" class="btn btn-primary" type="button">BATAL</a>
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
