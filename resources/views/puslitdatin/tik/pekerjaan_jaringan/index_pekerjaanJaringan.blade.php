@extends('layouts.base_layout')
@section('title', 'Data Pekerjaan Jaringan')

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
							<h2>Pekerjaan Jaringan<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(106, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(106, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('puslitdatin/bidang_tik/add_pekerjaan_jaringan')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
								@if(count($data) && isset($current_page))
									<a href="{{route('print_page_puslitdatin',['pekerjaan_jaringan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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

								@include('_templateFilter.puslit_pekerjaanjaringan_filter')
								@php
									$i = $start_number;
								@endphp
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Jenis Kegiatan</th>
											<th>Tanggal Pelaporan</th>
											<th>Pelapor </th>
											<th>Satuan Kerja</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@foreach($data as $d)
										<tr>
											<td>{{$i}}</td>
											<td>{{isset($jenis_kegiatan[$d->jenis_kegiatan])  ?  $jenis_kegiatan[$d->jenis_kegiatan]: $d->jenis_kegiatan }}</td>
											<td>{{ ( $d->tgl_pelaporan? date('d/m/Y',strtotime($d->tgl_pelaporan)): ''  )}}</td>
											<td>{{$d->nama_pelapor}}</td>
											<td>{{ $d->nm_instansi}}</td>
											<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(106, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('puslitdatin/bidang_tik/edit_pekerjaan_jaringan/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(106, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
										@php
											$i = $i+1;
										@endphp
									@endforeach
								@else
									<tr>
										<td colspan="8">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Data Pekerjaan Jaringan  Yang Anda Cari Tidak Tersedia.
							                        @else
							                        	Data Pekerjaan Jaringan Tidak tersedia.
							                        @endif
						                      	@else
						                          	Data Pekerjaan Jaringan Tidak tersedia.
						                      	@endif
											</div>
										</td>
									</tr>
								@endif
								</tbody>
							</table>
							<div class="pagination_wrap">
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
