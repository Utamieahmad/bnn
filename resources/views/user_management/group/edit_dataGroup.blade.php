@extends('layouts.base_layout')
@section('title', 'Ubah Data User Role Group')

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
					<h2>Form Ubah Data User Role Group</h2>
					<div class="clearfix"></div>
				</div>
		<div class="x_content">
					<br />
			<form action="{{url('/user_management/update_group/')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{ csrf_field() }}
				<input type="hidden" name="form_method" value="create">
	      <div class="form-body">

		        <div class="form-group">
		            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Group</label>
		            <div class="col-md-6 col-sm-6 col-xs-12">
										<input name="id" value="{{$group[0]['group_id']}}" type="hidden" />
		                <input value="{{$group[0]['group_name']}}" id="group_name" name="group_name" type="text" class="form-control">
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
									<td><input @if(isset($role[$d['menu_id']])) {{($role[$d['menu_id']]['read']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="read[]" value="{{$d['menu_id']}}"/></td>
									<td><input @if(isset($role[$d['menu_id']])) {{($role[$d['menu_id']]['create']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="create[]" value="{{$d['menu_id']}}"/></td>
									<td><input @if(isset($role[$d['menu_id']])) {{($role[$d['menu_id']]['update']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="update[]" value="{{$d['menu_id']}}"/></td>
									<td><input @if(isset($role[$d['menu_id']])) {{($role[$d['menu_id']]['delete']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="delete[]" value="{{$d['menu_id']}}"/></td>
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
									<td><input @if(isset($role[$m['menu_id']])) {{($role[$m['menu_id']]['read']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="read[]" value="{{$m['menu_id']}}"/></td>
									<td><input @if(isset($role[$m['menu_id']])) {{($role[$m['menu_id']]['create']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="create[]" value="{{$m['menu_id']}}"/></td>
									<td><input @if(isset($role[$m['menu_id']])) {{($role[$m['menu_id']]['update']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="update[]" value="{{$m['menu_id']}}"/></td>
									<td><input @if(isset($role[$m['menu_id']])) {{($role[$m['menu_id']]['delete']==true ? 'checked="checked"' : '')}} @endif type="checkbox" name="delete[]" value="{{$m['menu_id']}}"/></td>
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
