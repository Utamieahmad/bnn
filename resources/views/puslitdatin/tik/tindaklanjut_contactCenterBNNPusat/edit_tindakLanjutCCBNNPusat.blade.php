@extends('layouts.base_layout')
@section('title', 'Puslitdatin : Ubah Tindak Lanjut Contact Center BNN Pusat')

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
	                    <h2>Form Tambah Tindak Lanjut Contact Center BNN Pusat</h2>
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
					
	           			<form action="{{URL('/puslitdatin/bidang_tik/update_tindak_lanjut_bnn_pusat')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
	           				<input type="hidden" name="id" value="{{$data->id}}"/>
		    				<div class="form-body">

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group datepicker-only'>
										<input type='text' name="tgl_dibuat" value="{{ (isset($data->tgl_dibuat) ? \Carbon\Carbon::parse($data->tgl_dibuat)->format('d/m/Y') : '')}}" class="form-control" />
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
											</span>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="nama_pelapor" value="{{ (isset($data->nama_pelapor) ? $data->nama_pelapor   : '')}}" class="form-control" />
									</div>
					          	</div>
					          	
					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Media</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<div class="radio">
											@if($media_contact)
												@foreach($media_contact as $mkey=>$mvalue)
						                    		<label class="mt-radio col-md-9"> <input type="radio" value="{{$mkey}}" name="kodejenismedia" id="" {{(isset($data->kodejenismedia) ? ($data->kodejenismedia == $mkey ? 'checked=checked' : '') : '' )}}>
								                    <span>{{$mvalue}} </span>
								                    </label>
							                    @endforeach
							                @endif
						                </div>
									</div>
					          	</div>


					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Agent</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="nama_agent" value="{{ (isset($data->nama_agent) ? $data->nama_agent: '')}}" class="form-control" />
									</div>
					          	</div>
					          	
					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="judul" value="{{ (isset($data->judul) ? $data->judul: '')}}" class="form-control" />
									</div>
					          	</div>
					          	
					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Deskripsi</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
	              						<textarea class="form-control" name="deskripsi"> {{ (isset($data->deskripsi) ? $data->deskripsi: '')}}</textarea>
									</div>
					          	</div>
					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
	              						<textarea class="form-control" name="status"> {{ (isset($data->status) ? $data->status: '')}}</textarea>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Klasifikasi Informasi</label>
	              					<div class='col-md-9 col-sm-9 col-xs-12 input-group'>
										<div class="mt-radio-list">
											@if($codes)
												@foreach($codes as $ckey=>$cvalue)
								                    @if($cvalue)
									                    <div class="col-md-12 col-sm-12 col-xs-12">
								                    		<label class="mt-radio label-group"> <span>{{(isset($labels[$ckey]) ? $labels[$ckey] : $ckey )}}</span></label>
									                    	<div class="radio">
										                    	@foreach($cvalue as $kode => $kode_label)
										                    		<label class="mt-radio col-md-9"> <input type="radio" value="{{$kode}}" name="kodesubklasifikasi" id="" {{(isset($data->kodesubklasifikasi) ? ($data->kodesubklasifikasi == $kode ? 'checked=checked' : '') : '' )}}>
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
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Close</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group datepicker-only'>
										<input type='text' name="tgl_close" value="{{ (isset($data->tgl_close) ?  \Carbon\Carbon::parse($data->tgl_close)->format('d/m/Y') : '')}}" class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Resolution Close</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<textarea name="resolusion_close"  class="form-control"> {{ (isset($data->resolusion_close) ? $data->resolusion_close : '')}} </textarea>
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
