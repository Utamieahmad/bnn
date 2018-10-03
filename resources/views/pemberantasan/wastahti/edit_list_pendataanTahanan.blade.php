@extends('layouts.base_layout')
@section('title', 'Data Tahanan Pendataan Tahanan')

@section('content')
<div class="right_col" role="main">
	<div class="m-t-40">
		<div class="page-title">
			<div class="">
				{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
			</div>
		</div>
		<script>
		var TOKEN = '{{$token}}';
		var TITLE = '{{$title}}';
		</script>

		<div class="clearfix"></div>

		<div class="row">

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Data List Tahanan<small></small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content ">
						@if(count($tahanan))

						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Warga Negara</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@php $i = 1; @endphp
								@foreach($tahanan as $d)
								<tr>
									<td> {{$i}}</td>
									<td> {{$d->tersangka_nama}}</td>
									<td> {{$d->kode_jenis_kelamin}}</td>
									<td> {{$d->kode_warga_nagara}} </td>
									<td>
										<a href="{{url('pemberantasan/dir_wastahti/edit_pendataan_tahanan/'.$d->tahanan_id)}}"><i class="fa fa-pencil"></i></a><!--
										<button type="button" class="btn btn-primary button-delete" data-target="{{$d->tahanan_id}}" ><i class="fa fa-trash"></i></button> -->
									</td>
								</tr>
								@php $i = $i+1; @endphp
								@endforeach
							</tbody>
						</table>
						@else
						<div class="alert alert-warning">
							Data Kasus belum tersedia.
						</div>
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
			</div>
			<div class="modal-body">
				Apakah Anda ingin menghapus data ini ?
			</div>
			<input type="hidden" class="target_id" value=""/>
			<input type="hidden" class="audit_menu" value="Pemberantasan - Direktorat Wastahti - Tahanan"/>
			<input type="hidden" class="audit_url" value="http://{{ $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}"/>
			<input type="hidden" class="audit_ip_address" value="{{ $_SERVER['SERVER_ADDR'] }}"/>
			<input type="hidden" class="audit_user_agent" value="{{ $_SERVER['HTTP_USER_AGENT'] }}"/>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button type="button" class="btn btn-primary confirm" onclick="deleteData(1189)">Ya</button>
			</div>

		</div>
	</div>
</div>
@endsection
