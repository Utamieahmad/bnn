@extends('layouts.base_layout')
@section('title','Monitoring Nihil')
@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			
			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Monitoring Nihil<small></small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">
							@if (session('status'))
				                @php
				                	$session= session('status');
				                @endphp
		    					<div class="alert alert-{{$session['status']}}">
				        			{{ $session['message'] }}
				    			</div>
							@endif
							
							@if(count($data))
								@php
									$i = $start_number;
								@endphp
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th width="15%">Tanggal</th>
											<th width="20%">Pelaksana</th>
											<th width="20%">Modul </th>
											<th>Status</th>
											<th width="20%">Dientri Oleh</th>
											<th width="20%">Waktu Entri</th>
										</tr>
									</thead>
								<tbody>
								@foreach($data as $d)
									<tr> 
										<td>{{$i}}</td>
										<td>{{$d->tgl_pelaksanaan}}</td>
										<td>{{$d->nm_instansi}}</td>
										<td>{{$d->nama_kegiatan}}</td>
										<td>{{$d->status_entri}}</td>
										<td>{{$d->nm_instansi}}</td>
										<td>{{$d->created_at}}</td>
										
									</tr>
									@php
										$i = $i+1;
									@endphp
								@endforeach
								</tbody>
								</table>
								<div class="pagination_wrap">
									{!! $pagination !!}
								</div>
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
       			<form action="/puslitdatin/bidang_litbang/delete_informasi_melalui_contact_center" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
       				{{csrf_field()}}
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
						<button type="button" class="btn btn-primary confirm" onclick="delete_row(event,this)">Ya</button>
					</div>
					<div class="modal-footer-loading hidden alert">
						<p> Loading ... </p>
					</div>

				</form>
			</div>
		</div>
	</div>
@include('modal.modal_inputNihil')
@endsection
