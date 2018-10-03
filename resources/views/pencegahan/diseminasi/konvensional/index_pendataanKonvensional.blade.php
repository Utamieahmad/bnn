@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Media Konvensional')

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
			var TITLE = '{{$titledel}}';
			</script>

			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Data Kegiatan Media Konvensional<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="">
							<a href="{{url('pencegahan/dir_diseminasi/add_pendataan_konvensional')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							<a href="{{URL('/pencegahan/dir_diseminasi/printkonvensional?'.$forprint)}}" class="btn btn-lg btn-round btn-dark">
							<i class="fa fa-print"></i> Cetak
							</a>
							</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">
	          @if(session('status'))
	            @php
	              $session = session('status');
	            @endphp
	            <div class="alert alert-{{$session['status']}}">
	              {{$session['message']}}
	            </div>
	          @endif
							@include('_templateFilter.cegah_disem_konven')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Pelaksana</th>
											<th>Waktu  </th>
											<th>Sasaran </th>
											<th>Materi</th>
											<th>Narasumber</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($data_disemkonven))
									@php $i = $start_number; @endphp
									@foreach($data_disemkonven as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td> {{( $d['waktu_publish'] ? date('d/m/Y H:i:s', strtotime($d['waktu_publish'])) :'' )}}</td>
											<td> {{$d['kodesasaran']}} </td>
											<td> {{$d['materi']}} </td>
											<td> {{$d['narasumber']}} </td>
											<td>  @if($d['status'] == 'Y')
															Lengkap
														@elseif($d['status'] == 'N')
															Tidak Lengkap
														@endif </td>
											<td>
												<a href="{{url('pencegahan/dir_diseminasi/edit_pendataan_konvensional/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button data-url='{{$titledel}}' type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>

				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
							@else
								<tr>
									<td colspan="8">
										<div class="alert-messages alert-warning">
										 @if(isset($filter))
												@if(count($filter) >0)
													Data Kegiatan Media Konvensional Yang Anda Cari Tidak Tersedia.
												@else
													Data Kegiatan Media Konvensional Tidak Tersedia.
												@endif
											@else
													Data Kegiatan Media Konvensional Tidak Tersedia.
											@endif
										</div>
									</td>
								</tr>
							@endif
						</tbody>
						</table>
						{{-- <ul id="pagination-demo" class="pagination-sm"></ul> --}}

						</div>
						@if(count($data_disemkonven))
							<div class="pagination_wrap">
								{!! $pagination !!}
							</div>
						@endif
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
				<input type="hidden" class="audit_menu" value="Pencegahan - Direktorat Alternative Development - Media Konvensional"/>
				<input type="hidden" class="audit_url" value="http://{{ $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}"/>
				<input type="hidden" class="audit_ip_address" value="{{ $_SERVER['SERVER_ADDR'] }}"/>
				<input type="hidden" class="audit_user_agent" value="{{ $_SERVER['HTTP_USER_AGENT'] }}"/>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
					<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
				</div>
			</div>
		</div>
	</div>
  @include('modal.modal_input_nihil')
@endsection
