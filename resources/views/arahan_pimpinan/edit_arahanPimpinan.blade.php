@extends('layouts.base_layout')
@section('title', 'Arahan Pimpinan : Tambah Data Arahan Pimpinan')

@section('content')
	<div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
				<div class="">
					{!! $breadcrumps !!}
				</div>
            </div>
        	<div class="clearfix"></div>

        	<div class="title_right"> </div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Form Tambah Data Arahan Pimpinan</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							 @if (session('status'))
	                            @php
	                                $session= session('status');
	                            @endphp
	                            <div class="alert alert-{{$session['status']}}">
	                                {{ $session['message'] }}
	                            </div>
	                        @endif
	                       
	                        <?php
	                        	if($disabled == false){
	                        		$disable = '';
	                        	}else{
	                        		$disable = 'disabled=disabled';
	                        	}
	                        	
	                        ?>
	                        @if($disabled == false)
								<form action="{{route('update_arahan_pimpinan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
							@else
								<form class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">

								<div class="form-group">
						            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
						           <div class='col-md-6 col-sm-6 col-xs-12'>
						           		<div class="alert-messages alert-warning m-b-20"> Anda tidak dapat melakukan perubahan</div>
						           </div>
						        </div>

								
							@endif
		        				{{csrf_field()}}
		        				<input type="hidden" value="{{$data->id}}" name="id"/>
		        				<div class="form-body">

						        <div class="form-group">
						            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Arahan</label>
						            <div class='col-md-6 col-sm-6 col-xs-12 input-group date date_start' >
					                    <input type='text' value="{{(isset($data) ? ($data->tgl_arahan ? date('d/m/Y',strtotime($data->tgl_arahan)):''):'' )}}" name="tgl_arahan" class="form-control" {{$disable}} required />
					                    <span class="input-group-addon" {{$disable}}>
					                    <span class="glyphicon glyphicon-calendar" {{$disable}}></span>
					                    </span>
					                </div>
						        </div>

						        <div class="form-group">
					                <label for="tempatkejadian_idprovinsi" class="col-md-3 col-sm-3 col-xs-12 control-label">Satker</label>
					                <div class="col-md-6 col-sm-6 col-xs-12"> 
					                	<input type='text' value="{{$nama_satker}}" class="form-control" disabled="disabled" />
					                </div>
				                </div>

				                <div class="form-group">
					                <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kadaluarsa</label>
					                <div class='col-md-6 col-sm-6 col-xs-12 input-group date date_end' {{$disable}}>
					                    <input type='text' value="{{(isset($data) ? ($data->tgl_kadaluarsa ? date('d/m/Y',strtotime($data->tgl_kadaluarsa)):''):'' )}}" name="tgl_kadaluarsa" class="form-control" {{$disable}}/>
					                    <span class="input-group-addon" {{$disable}}>
					                    <span class="glyphicon glyphicon-calendar" {{$disable}}></span>
					                    </span>
					                </div>
				             	</div>

				             	<div class="form-group">
						            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Arahan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{(isset($data) ? ($data->judul_arahan ? $data->judul_arahan:''):'' )}}" id="judul_arahan" name="judul_arahan" type="text" class="form-control" {{$disable}}>
						            </div>
						        </div>

				             	<div class="form-group">
                                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Isi Arahan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="isi_arahan" type="text" rows="10" class="form-control col-md-7 col-xs-12 " {{$disable}}> {{(isset($data) ? ($data->isi_arahan ? $data->isi_arahan:''):'' )}}</textarea> 
                                    </div>
                                </div>

							    <div class="form-actions fluid">
						            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						            	@if($disabled == false)
						                	<button type="submit" class="btn btn-success">KIRIM</button>
						                	<a href="{{route('arahan_pimpinan')}}" class="btn btn-primary" type="button">BATAL</a>
						                @else
						                	<a href="{{route('arahan_pimpinan')}}" class="btn btn-primary" type="button">Kembali</a>
						                @endif
						            </div>
						    	</div>
							</form>

						</div>
					</div>
				</div>
	        </div>
     	</div>
	</div>
@endsection
