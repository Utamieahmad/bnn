@extends('layouts.base_layout')
@section('title', 'Data Magang')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
				</div>
			</div>


			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Balai Besar Magang<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(110, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>

								<li class="" @php if(!in_array(110, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{route('add_magang')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>

								<li class="">
									@if(count($data) && isset($current_page))
										<a href="{{route('print_balai_besar',['data_magang',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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

							@include('_templateFilter.balaibesar_filter')
								@php
									$i = $start_number;
								@endphp
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Jenis Kegiatan</th>
											<th>Nama Kegiatan</th>
											<th>Instansi</th>
					                        <th>Periode</th>
					                        <th>Jumlah Peserta</th>
					                        <th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>

								@if(count($data))
									@foreach($data as $d)
										<tr>
											<td>{{$i}}</td>
											<td>{{  (isset( $jenis_kegiatan[$d->jenis_kegiatan] ) ?  $jenis_kegiatan[$d->jenis_kegiatan]  : $d->jenis_kegiatan)  }}</td>
											<td>{{$d->nama_kegiatan}}</td>
											<td> {{( isset($instansi[$d->kode_instansi]) ? $instansi[$d->kode_instansi] :$d->kode_instansi)}}</td>
											<td>{{ ($d->tanggal_mulai ? date('d/m/Y',strtotime($d->tanggal_mulai)) : '') }} - {{ ($d->tanggal_selesai ? date('d/m/Y',strtotime($d->tanggal_selesai)) : '') }}</td>
											<td>{{$d->bnn_jumlah_peserta}}</td>
											<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(110, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_magang',$d->id)}}"><i class="fa fa-pencil"></i></a>
						                              	<button @php if(!in_array(110, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
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
