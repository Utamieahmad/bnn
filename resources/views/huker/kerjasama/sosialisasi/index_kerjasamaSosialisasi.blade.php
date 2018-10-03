@extends('layouts.base_layout')
@section('title', 'Dir Kerjasama : Data Kegiatan Sosialisasi')

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
							<h2>Data Kegiatan Sosialisasi<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="">
							<a href="{{url('huker/dir_kerjasama/add_kerjasama_sosialisasi')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-dark">
							<i class="fa fa-print"></i> Cetak
							</a>
							</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">
							@if(count($data_sosialisasi))

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal Kegiatan</th>
											<th>Jumlah Instansi</th>
											<th>Instansi</th>
											<th>Jumlah Peserta</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@php $i = 1; @endphp
									@foreach($data_sosialisasi as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{date('d-m-Y', strtotime($d['tanggal_pelaksanaan']))}}</td>
											<td> {{$d['jumlah_instansi']}}</td>
											<td>
												@php
												$meta = json_decode($d['meta_instansi'],true);
												if(count($meta)){

													echo '<ol class="">';
														for($j = 0 ; $j < count($meta); $j++){

															echo '<li>'.$meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')</li>';
														}
														echo '</ol>';
													}else{
														echo '-';
													}
												@endphp
											</td>
											<td> {{$d['jumlah_peserta']}}</td>
											<td>
												<a href="{{url('huker/dir_kerjasama/edit_kerjasama_sosialisasi/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>

				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
								</tbody>
								</table>
								<ul id="pagination-demo" class="pagination-sm"></ul>
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

	<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
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
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
					<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
				</div>
			</div>
		</div>
	</div>
	@include('modal.modal_input_nihil')
@endsection
