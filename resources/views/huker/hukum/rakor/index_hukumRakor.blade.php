@extends('layouts.base_layout')
@section('title', 'Dir Hukum : Data Kegiatan Rakor dan Audiensi')

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
							<h2>Data Kegiatan Rakor dan Audiensi<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="">
							<a href="{{url('huker/dir_hukum/add_hukum_rakor')}}" class="btn btn-lg btn-round btn-primary">
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
							

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal Kegiatan</th>
											<th>Jenis Kegiatan</th>
											<th>Nomor Sprint</th>
											<th>Tema</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($data_irtarakoraudiensi))
										@php $i = 1; @endphp
										@foreach($data_irtarakoraudiensi as $d)
											<tr>
												<td> {{$i}}</td>
												<td> {{date('d-m-Y', strtotime($d['tgl_pelaksanaan']))}}</td>
												<td> {{$d['jenis_kegiatan']}}</td>
												<td> {{$d['nomor_sprint']}} </td>
												<td> {{$d['materi']}} </td>
												<td>
													<a href="{{url('huker/dir_hukum/edit_hukum_rakor/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
					                              	<button type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>

					                            </td>
											</tr>
										@php $i = $i+1; @endphp
										@endforeach
									@else
										<tr>
											<td colspan="6">
												<div class="alert-messages alert-warning">
													Data Kasus belum tersedia.
												</div>
											</td>
										</tr>
									@endif
								</tbody>
								</table>
								<ul id="pagination-demo" class="pagination-sm"></ul>
							

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
