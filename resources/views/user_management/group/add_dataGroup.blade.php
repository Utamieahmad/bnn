@extends('layouts.base_layout')
@section('title', 'Tambah User Data Role Group')

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
			<div class="x_panel">
				<div class="x_title">
					<h2>Form Tambah Data User Role Group</h2>
					<div class="clearfix"></div>
				</div>
		<div class="x_content">
					<br />
			<form action="{{url('/user_management/input_group/')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{ csrf_field() }}
				<input type="hidden" name="form_method" value="create">
	      <div class="form-body">

		        <div class="form-group">
		            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Group</label>
		            <div class="col-md-6 col-sm-6 col-xs-12">
		                <input value="" id="group_name" name="group_name" type="text" class="form-control">
		            </div>
		        </div>
						<hr>
						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Menu Web</th>
									<th>Lihat Data</th>
									<th>Tambah Data</th>
									<th>Ubah Data</th>
									<th>Hapus Data</th>
								</tr>
							</thead>
						<tbody>
							{{-- @if(count($datamaster))
							@php $i = $start_number; @endphp--}}
							@foreach($datamenu as $d)
								<tr>
									<td style="text-align:left">{{$d['menu_title']}}</td>
									<td><input type="checkbox" name="read[]" value="{{$d['menu_id']}}" data-parent="{{$d['menu_id_parent']}}" data-kode="r" id="r{{$d['menu_id']}}" onclick="autocheck(this)"/></td>
									<td><input type="checkbox" name="create[]" value="{{$d['menu_id']}}" data-parent="{{$d['menu_id_parent']}}" data-kode="c" id="c{{$d['menu_id']}}" onclick="autocheck(this)"/></td>
									<td><input type="checkbox" name="update[]" value="{{$d['menu_id']}}" data-parent="{{$d['menu_id_parent']}}" data-kode="u" id="u{{$d['menu_id']}}" onclick="autocheck(this)"/></td>
									<td><input type="checkbox" name="delete[]" value="{{$d['menu_id']}}" data-parent="{{$d['menu_id_parent']}}" data-kode="d" id="d{{$d['menu_id']}}" onclick="autocheck(this)"/></td>
								</tr>
							@endforeach
							{{--@else
								<tr>
									<td colspan="9">
										<div class="alert-messages alert-warning">
											@if(isset($filter))
												@if(count($filter) >0)
													Data Group Yang Anda Cari Belum Tersedia.
												@else
													Data Group belum tersedia.
												@endif
											@else
													Data Group belum tersedia.
											@endif

										</div>
									</td>
								</tr>
								@endif --}}
						</tbody>
						</table>
						<br>
						<hr>
						<br>
						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Menu Mobile</th>
									<th>Lihat Data</th>
									<th>Tambah Data</th>
									<th>Ubah Data</th>
									<th>Hapus Data</th>
								</tr>
							</thead>
						<tbody>
							{{-- @if(count($datamaster))
							@php $i = $start_number; @endphp--}}
							@foreach($datamobile as $m)
								<tr>
									<td style="text-align:left">{{$m['menu_title']}}</td>
									<td><input type="checkbox" name="read[]" value="{{$m['menu_id']}}" data-parent="{{$m['menu_id_parent']}}" data-kode="r" id="r{{$m['menu_id']}}" onclick="autocheck(this)"/></td>
									<td><input type="checkbox" name="create[]" value="{{$m['menu_id']}}" data-parent="{{$m['menu_id_parent']}}" data-kode="c" id="r{{$m['menu_id']}}" onclick="autocheck(this)"/></td>
									<td><input type="checkbox" name="update[]" value="{{$m['menu_id']}}" data-parent="{{$m['menu_id_parent']}}" data-kode="u" id="r{{$m['menu_id']}}" onclick="autocheck(this)"/></td>
									<td><input type="checkbox" name="delete[]" value="{{$m['menu_id']}}" data-parent="{{$m['menu_id_parent']}}" data-kode="d" id="r{{$m['menu_id']}}" onclick="autocheck(this)"/></td>
								</tr>
							@endforeach
							{{--@else
								<tr>
									<td colspan="9">
										<div class="alert-messages alert-warning">
											@if(isset($filter))
												@if(count($filter) >0)
													Data Group Yang Anda Cari Belum Tersedia.
												@else
													Data Group belum tersedia.
												@endif
											@else
													Data Group belum tersedia.
											@endif

										</div>
									</td>
								</tr>
								@endif --}}
						</tbody>
						</table>

	    	</div>
		    <div class="form-actions fluid">
		        <div class="row">
		            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		                <button type="submit" class="btn btn-success">KIRIM</button>
										<a href="{{route('dataGroup')}}" class="btn btn-primary" type="button">BATAL</a>
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
