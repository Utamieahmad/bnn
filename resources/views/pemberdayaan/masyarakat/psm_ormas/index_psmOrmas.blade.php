@extends('layouts.base_layout')
@section('title', 'Dir Peran Serta Masyarakat : Data Kegiatan Ormas/LSM Anti Narkoba')

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
							<h2>Data Kegiatan Ormas/LSM Anti Narkoba<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="">
							<a href="{{url('pemberdayaan/dir_masyarakat/add_psm_ormas')}}" class="btn btn-lg btn-round btn-primary">
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
							@if(count($data_psmlsm))

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal Kegiatan</th>
											<th>Pelaksana</th>
											<th>Jenis Kegiatan</th>
											<th>Materi </th>
											<th>Nama Instansi </th>
											<th>Jumlah Peserta</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@php $i = 1; @endphp
									@foreach($data_psmlsm as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{date('d-m-Y', strtotime($d['tgl_pelaksanaan']))}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td> {{$d['jenis_kegiatan']}}</td>
											<td> {{$d['materi']}}</td>
											<td> {{$d['meta_instansi']}}</td>
											<td> {{$d['jumlah_peserta']}}</td>
											<td>
												<a href="{{url('pemberdayaan/dir_masyarakat/edit_psm_ormas/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button data-url='{{$title}}' type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>

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
@endsection
