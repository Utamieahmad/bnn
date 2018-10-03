@extends('layouts.base_layout')
@section('title', 'Ubah Permintaan Data Hasil Penelitian BNN')

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
	                    <h2>Form Ubah Permintaan Hasil Data Penelitian BNN</h2>
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
	           			<form action="{{URL('/puslitdatin/bidang_litbang/update_data_penelitian_bnn')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
	           				<input type="hidden" name="id" value="{{$data->id}}"/>
		    				<div class="form-body">
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="nama" value="@if(isset($data->nama)){{$data->nama}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Indentitas (KTP/SIM/KTA/KTM/Passport)</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="no_identitas" value="@if(isset($data->no_identitas)){{$data->no_identitas}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kelamin</label>
					              	<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="radio">
											@if(count($gender))
												@foreach($gender as $g=>$gvalue)
													<label class="mt-radio col-md-9"> <input type="radio" value="{{$g}}" name="kode_jeniskelamin" id="" {{(isset($data->kode_jeniskelamin) ? ($data->kode_jeniskelamin == $g ? 'checked=checked' : '') : '' )}}>
								                    <span>{{$gvalue}}</span>
								                    </label>
												@endforeach
											@endif
										</div>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Satker/ Instansi</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="satker_instansi" value="@if(isset($data->satker_instansi)){{$data->satker_instansi}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Sesuai KTP</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="alamat" value="@if(isset($data->alamat)){{$data->alamat}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Alamat</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					              		{!! dropdownPropinsiKabupaten($data->alamat_idkabkota,'alamat_idkabkota') !!}
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Telepon</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="no_telp" value="@if(isset($data->no_telp)){{$data->no_telp}}@else{{''}}@endif" class="form-control" onKeydown="phone(event,this)"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Kode Cara Permintaan</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
						              	<div class="radio">
						              		@if($kode_permintaan)
						              			@foreach($kode_permintaan as $kode=>$kvalue)
						              					<label class="mt-radio col-md-9"> <input type="radio" value="{{$kode}}" name="kode_carapermintaan" id="" {{(isset($data->kode_carapermintaan) ? (strtolower($data->kode_carapermintaan) == $kode ? 'checked=checked' : '') : '' )}}>
									                    <span>{{$kvalue}}</span>
									                    </label>
						              			@endforeach
						              		@endif
						              	</div>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Kode Tujuan</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					              		<div class="radio">
						              		@if($kode_tujuan)
						              			@foreach($kode_tujuan as $ktujuan=>$jvalue)
						              					<label class="mt-radio col-md-9"> <input type="radio" value="{{$ktujuan}}" name="kode_tujuan" id="" {{(isset($data->kode_tujuan) ? (strtolower($data->kode_tujuan) == $ktujuan ? 'checked=checked' : '') : '' )}}>
									                    <span>{{$jvalue}}</span>
									                    </label>
						              			@endforeach
						              		@endif
						              	</div>
					              	</div>
					          	</div>


					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Data yang Dibutuhkan</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="data_dibutuhkan" value="@if(isset($data->data_dibutuhkan)){{$data->data_dibutuhkan}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Bentuk Dokumen</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					              		<div class="radio">
						              		@if($dokumen)
						              			@foreach($dokumen as $dkode=>$dvalue)
						              					<label class="mt-radio col-md-9"> <input type="radio" value="{{$dkode}}" name="kode_bentukdokumen" id="" {{(isset($data->kode_bentukdokumen) ? (strtolower($data->kode_bentukdokumen) == $dkode ? 'checked=checked' : '') : '' )}}>
									                    <span>{{$dvalue}}</span>
									                    </label>
						              			@endforeach
						              		@endif
						              	</div>
					              	</div>
					          	</div>

					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{url('puslitdatin/bidang_litbang/data_penelitian_bnn')}}" class="btn btn-primary" type="button">BATAL</a>
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
