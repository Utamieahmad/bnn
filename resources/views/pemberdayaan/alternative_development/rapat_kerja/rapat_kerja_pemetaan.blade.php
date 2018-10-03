@extends('layouts.base_layout')
@section('title', 'Data Rapat Kerja Pemetaan')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Data Rapat Kerja Pemetaan<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(116, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(116, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{route('add_alv_rapat_kerja_pemetaan')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									<a href="{{route('print_page_dayamas',['alv_rapat_kerja_pemetaan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
										<i class="fa fa-print"></i> Cetak
									</a>
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

							@include('_templateFilter.dayamas_rapatkerja_filter')
							<table id="datatable-responsive" class="table table-striped dt-responsive nowrap " cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Tanggal Pelaksanaan</th>
										<th>Pelaksana</th>
										<th>Lokasi Kegiatan </th>
				                        <th>Jumlah Peserta</th>
										<th>Sasaran</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
								@if(count($data))
									@php
										$kd_jnswilayah = config('lookup.kd_jnswilayah');
										$i = $start_number;
									@endphp
									@foreach($data as $k=>$d)
										<tr>
											<td>{{$i}}</td>
											<td>{{( isset($d->tanggal_pemetaan) ? date('d/m/Y',strtotime($d->tanggal_pemetaan))  : '')}}</td>
											<td>{{ (isset($kd_jnswilayah[$d->kd_jnswilayah]) ? $kd_jnswilayah[$d->kd_jnswilayah] :'' ).' '.$d->wilayah_pelaksana.' '.$d->nm_wilayah_pelaksana}}</td>
											<td>{{ $d->nm_jnswilayah .' '.$d->nm_wilayah}}</td>
											<td>{{ $d->jumlah_peserta}}</td>
											<td>{{ (isset($sasaran[$d->kode_sasaran]) ? $sasaran[$d->kode_sasaran] : $d->kode_sasaran )}}</td>
											<td>{{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}}</td>
											<td>
												<a @php if(!in_array(116, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_alv_rapat_kerja_pemetaan',$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(116, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
										@php
											$i = $i+1;
										@endphp
									@endforeach
								@else
									<tr>
										<td  colspan="8">
											<div class="alert-warning alert-messages">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Data Rapat Kerja Pemetaan Yang Anda Cari Tidak Tersedia.
							                        @else
							                          Data Rapat Kerja Pemetaan Tidak tersedia.
							                        @endif
						                      	@else
						                          	Data Rapat Kerja Pemetaan Tidak tersedia.
						                      	@endif

											</div>
										</td>
									</tr>
								@endif
								</tbody>
							</table>
							<div class="pagination_wrap">
								@if(count($data))
									{!! $pagination !!}
								@endif
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
