@extends('layouts.base_layout')
@section('title', 'Tambah Permintaan Data Hasil Penelitian BNN')

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
	                    <h2>Form Tambah Permintaan Data Hasil Penelitian BNN</h2>
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
	           			<form action="{{URL('/puslitdatin/bidang_litbang/save_data_penelitian_bnn')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="nama" value="" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Indentitas (KTP/SIM/KTA/KTM/Passport)</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="no_identitas" value="" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kelamin</label>
					              	<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="radio">
											@if(count($gender))
												@foreach($gender as $g=>$gvalue)
													<label class="mt-radio col-md-9"> <input type="radio" value="{{$g}}" name="kode_jeniskelamin" id="">
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
					             		<input type='text' name="satker_instansi" value="" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Sesuai KTP</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="alamat" value="" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Alamat</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					              		{!! $dropdownPropinsiKabupaten !!}
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Telepon</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="no_telp" value="@if(isset($data->no_telp)){{$data->no_telp}}@else{{''}}@endif" class="form-control"  onKeydown="phone(event,this)"/>
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
						              					<label class="mt-radio col-md-9"> <input type="radio" value="{{$ktujuan}}" name="kode_tujuan" id="" >
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
					             		<input type='text' name="data_dibutuhkan" value="" class="form-control"/>
					              	</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Bentuk Dokumen</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					              		<div class="radio">
						              		@if($dokumen)
						              			@foreach($dokumen as $dkode=>$dvalue)
						              					<label class="mt-radio col-md-9"> <input type="radio" value="{{$dkode}}" name="kode_bentukdokumen" id="">
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
							                <button type="submit" class="btn btn-success">Simpan</button>
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
