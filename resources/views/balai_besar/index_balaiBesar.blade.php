@extends('layouts.base_layout')
@section('title', ' Balai Besar')

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
							<h2>Data Pengujian Bahan Sediaan, Spesimen Biologi dan Toksikologi<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="">
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>

								<li class="">
									<a href="{{route('add_balai_besar')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>

								<li class="">
									@if(count($data) && isset($current_page))
										<a href="{{url('/balai_lab/print_page/'.$current_page)}}" class="btn btn-lg btn-round btn-dark">
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

							
								@php
									$i = $start_number;
								@endphp
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Jenis Kegiatan</th>
											<th>Nama Kegiatan</th>
											<th>Instansi </th>
					                        <th>Materi</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@foreach($data as $d)
										<tr>
											<td>{{$i}}</td>
											<td>{{$d->nomor_surat_permohonan_pengajuan}}</td>
											<td>{{date('d-m-Y',strtotime($d->tgl_surat))}}</td>
											<td>{{$d->perihal_surat}}</td>
											<td>{{$d->no_lplkn}} / {{$d->tgl_lplkn}}</td>
											<td>
												<a href="{{url('balai_lab/pengujian/edit_pengujian_bahan/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
										@php
											$i = $i+1;
										@endphp
									@endforeach
								@else
									<tr>
										<td colspan="6">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Data Pengujian Bahan Sediaan, Spesimen Biologi dan Toksikologi Yang Anda Cari Belum Tersedia.
							                        @else
							                        	Data Pengujian Bahan Sediaan, Spesimen Biologi dan Toksikologi belum tersedia.
							                        @endif
						                      	@else
						                          	Data Pengujian Bahan Sediaan, Spesimen Biologi dan Toksikologi belum tersedia.
						                      	@endif
												
											</div>
										</td>
									</tr>
								@endif
								</tbody>
							</table>
						<div class="pagination_wrap text-right">
							{!! $pagination !!}
						</div>
							

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')
@endsection
