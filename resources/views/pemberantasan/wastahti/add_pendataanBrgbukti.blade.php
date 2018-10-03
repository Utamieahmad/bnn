@extends('layouts.base_layout')
@section('title', 'Tambah Pendataan Barang Bukti')

@section('content')
<div class="right_col" role="main">
	<div class="m-t-40">
		<div class="page-title">
			<div class="">
				{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="title_right">
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			@if (session('message'))
					@php
						$session = session('message');
						$error = $session['status'];
						$message = is_array($session['message']) ? implode(',',$session['message']) : $session['message'];
						echo '<div class="alert alert-'.$error.'">'.$message.'</div>';
					@endphp
			@endif
			<div class="x_panel">
				<div class="x_title">
					<h2>Form Tambah Pendataan Pemusnahan barang bukti di BNN dan BNNP Direktorat Wastahti</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<br />
					<form action="{{URL('/pemberantasan/dir_wastahti/add_data_pendataan_brgbukti')}}" method="post" data-parsley-validate class="form-horizontal form-label-left">
						{{ csrf_field() }}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2" id="kasus_no" name="kasus_no" required>
									<option value="">-- Pilih nomor LKN --</option>
								@foreach($lkn as $w)
									<option value="{{$w['kasus_no']}}">{{$w['kasus_no']}}</option>
								@endforeach
								</select>
							</div>
						</div>

						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">Cek Nomor LKN</button>
								<a href="{{url('/pemberantasan/dir_wastahti/pendataan_brgbukti')}}" class="btn btn-primary" type="button">Batal</a>
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
