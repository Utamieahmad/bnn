@extends('layouts.base_layout')
@section('title', 'Puslitdatin : Tambah Penyalahguna Narkoba Teratur Pakai')

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
	                    <h2>Form Tambah Penyalahguna Narkoba Teratur Pakai</h2>
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
	           			<form action="{{URL('/puslitdatin/bidang_litbang/save_penyalahguna_teratur_pakai')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">
		    					<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Propinsi</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control select2" name="idprovinsi">
											<option>-- Pilih Propinsi -- </option>
											@if($propinsi)
												@foreach($propinsi as $p)
													<option value="{{$p->id_wilayah}}"> {{$p->nm_wilayah}}</option>
												@endforeach
											@endif
										</select>
									</div>
					          	</div>

		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 12 input-group date year-only'>
					             		<input type='text' name="tahun" value="@if(isset($data->tahun)) {{ $data->tahun }} @else {{''}} @endif" class="form-control" />
					             		<span class="input-group-addon">
						                <span class="glyphicon glyphicon-calendar"></span>
						                </span>
					              	</div>
					          	</div>
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Populasi</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="populasi" value="" class="form-control" onKeydown="numeric_only(event,this)"  onClick="reformatNumber(event,this)" onChange="reformatNumber(event,this)" />
					              	</div>
					          	</div>
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Penyalah Guna</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="jumlah_penyalahguna" value="" class="form-control" onClick="reformatNumber(event,this)" onChange="reformatNumber(event,this)" onKeydown="numeric_only(event,this)"/>
					              	</div>
					          	</div>
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Prevalensi</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="prevalensi" value="" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">KIRIM</button>
							                <a href="{{url('puslitdatin/bidang_litbang/penyalahguna_teratur_pakai')}}" class="btn btn-primary" type="button">BATAL</a>
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
