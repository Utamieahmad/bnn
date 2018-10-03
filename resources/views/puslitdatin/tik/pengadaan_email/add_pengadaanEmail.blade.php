@extends('layouts.base_layout')
@section('title', 'Tambah Data Pembuatan Email BNN')

@section('content')
    <div class="right_col has-paste" role="main">
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
	                    <h2>Form Tambah Data Pembuatan Email BNN</h2>
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
					
	           			<form action="{{route('save_pengadaan_email')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">

		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group datepicker-only'>
										<input type='text' name="tgl_pelaporan" value="" class="form-control" required />
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
											</span>
									</div>
					          	</div>
								
								<div class="form-group">
					                <label for="tempatkejadian_idprovinsi" class="col-md-3 col-sm-3 col-xs-12 control-label">Satuan Kerja</label>
					                <div class="col-md-6 col-sm-6 col-xs-12 input-group">
					                  <select class="form-control select2" id="tempatkejadian_idprovinsi" name="tempatkejadian_idprovinsi" required>
					                    <option value="">-- Pilih Satker --</option>
					                    @if(count($instansi))
					                      @foreach($instansi as $wil)
					                        <option value="{{$wil['id_instansi']}}" > {{$wil['nm_instansi']}}</option>
					                      @endforeach
					                    @endif
					                </select>
					                </div>
				                </div>
								
								<div class="form-group">
	              					<label for="nomor_nota_dinas" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group'>
										<input type='text' name="nomor_nota_dinas" value="" class="form-control" />
									</div>
					          	</div>
								
								<div class="form-group">
									<label for="email" class="col-md-3 col-sm-3 col-xs-12 control-label">Email</label>
									<div class="col-md-5 col-sm-5 col-xs-12 p-l-0 p-r-0">
										<input name="email" type="text" class="form-control " value="" onkeypress="validate_emailname(event,this)">
									</div>
									<label class="col-md-1 col-sm-1 col-xs-12 control-label">
										@bnn.go.id
									</label>
								</div>
								
								<div class="form-group">
									<label for="jenis_kuota" class="col-md-3 col-sm-3 col-xs-12 control-label">Kuota</label>
									<div class="col-md-9 col-sm-9 col-xs-12 radio  input-group">
										<label class="mt-radio col-md-6 col-sm-6 col-xs-12">  
											<input type="radio" value="unlimited" name="jenis_kuota" onclick="kuota_jenis(this)">
											<span>Unlimited</span>
										</label>
										<div class="clearfix"></div>
										<label class="mt-radio col-md-3 col-sm-3 col-xs-12"> 
											<input type="radio" value="limited" name="jenis_kuota" onclick="kuota_jenis(this)">
											<span>Limited</span>
										</label>

										
											
									</div>
								</div>

								<div class="form-group hide limit_kuota">
									<label for="limit_quota" class="col-md-3 col-sm-3 col-xs-12 control-label">Kuota Limit</label>
									<div class="col-md-5 col-sm-5 col-xs-12 p-l-0 p-r-0">
										<input id="kuota" name="kuota" type="text" class="form-control " value="" onkeydown="numeric_only(event,this)">
									</div>
									<label class="col-md-1 col-sm-1 col-xs-12 control-label">
										<div class="text-left"> MB </div>
									</label>
								</div>
								
								
								<div class="form-group">
									<label for="status" class="col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
									<div class="col-md-9 col-sm-9 col-xs-12 radio input-group">
										<label class="mt-radio col-md-9 col-sm-9 col-xs-12">  
											<input type="radio" value="Y" name="status_aktif" >
											<span>Aktif</span>
										</label>

										<label class="mt-radio col-md-9 col-sm-9 col-xs-12"> 
											<input type="radio" value="N" name="status_aktif" >
											<span>Tidak Aktif</span>
										</label>
									</div>
								</div>

					          	
					          	<div class="form-actions fluid row">
							        <div class="m-t-20 row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{route('pengadaan_email')}}" class="btn btn-primary" type="button">BATAL</a>
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
