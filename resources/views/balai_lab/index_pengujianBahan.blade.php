@extends('layouts.base_layout')
@section('title', 'Data Berkas Sampel')

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
							<h2>Data Berkas Sampel<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">

							<li class="">

									<a href="{{route('print_balai_lab',[$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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

								@php
									$i = $start_number;
								@endphp
								@include('_templateFilter.balailab_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th width="14%">No Lab</th>
											<th>No. Surat Permohonan</th>
						                    <th>No Laporan</th>
											<th>Tanggal Terima</th>
											<th>Instansi </th>
						                    <th width="10%">Posisi Berkas</th>
						                    <th width="10%">Jenis Berkas</th>
						                    <th width="10%">Status Berkas</th>

										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@foreach($data as $d)
										<tr>
											<td>{{$i}}</td>
											<td>{{$d->no_lab}}</td>
											<td>{{$d->no_surat_permohonan}}</td>
											<td>{{$d->no_lp}}</td>
											<td>{{ ( $d->tanggal_terima? date('d/m/Y',strtotime($d->tanggal_terima)) :'')}}</td>
											<td>{{$d->nama_instansi}}</td>
											<td>{{$d->provinsi}}</td>
											<td>{{$d->jenis_berkas}}</td>
											<td>{{$d->status_berkas}}</td>

										</tr>
										@php
											$i = $i+1;
										@endphp
									@endforeach
								@else
									<tr>
										<td colspan="9">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Data Berkas Sampel  Yang Anda Cari Belum Tersedia.
							                        @else
							                         	Data Berkas Sampel belum tersedia.
							                        @endif
						                      	@else
					                          		Data Berkas Sampel belum tersedia.
						                      	@endif

											</div>
										</td>
									</tr>

								@endif
								</tbody>
							</table>
							<div class="pagination_wrap ">
								@if(count($data)>0)
									{!! isset($pagination) ?  $pagination : '' !!}
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
