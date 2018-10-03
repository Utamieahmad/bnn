@extends('layouts.base_layout')
@section('title', 'Puslitdatin : Tindak Lanjut Contact Center BNN Pusat')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! $breadcrumps !!}
				</div>
			</div>
			

			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Tindak Lanjut Contact Center BNN Pusat<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="">
							<a href="{{url('puslitdatin/bidang_tik/add_tindak_lanjut_bnn_pusat')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
								@if(count($data) && isset($current_page))
									<a href="{{URL('/puslitdatin/bidang_tik/print_page/tindak_lanjut_bnn_pusat/'.$current_page)}}" class="btn btn-lg btn-round btn-dark">
										<i class="fa fa-print"></i> Cetak
									</a>
								@endif
							</li>
							</ul>
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
											<th>Tanggal</th>
											<th>Pelapor</th>
											<th>Media </th>
											<th>Agent</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@foreach($data as $d)
									<tr> 
										<td>{{$i}}</td>
										<td>{{$d->tgl_dibuat}}</td>
										<td>{{$d->nama_pelapor}}</td>
										<td>{{$d->kodejenismedia}}</td>
										<td>{{$d->nama_agent}}</td>
										<td>{{ (  ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap'     )  }}</td>
										<td>
											<a href="{{url('puslitdatin/bidang_tik/edit_tindak_lanjut_bnn_pusat/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
			                              	<button type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
			                            </td>
									</tr>
									@php
										$i = $i+1;
									@endphp
								@endforeach
								</tbody>
								</table>
								<div class="pagination_wrap text-right">
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


@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')
@endsection
