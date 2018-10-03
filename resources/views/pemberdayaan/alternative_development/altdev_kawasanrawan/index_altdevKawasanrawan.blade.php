@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Pemetaan Kawasan Rawan Narkoba')

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
				@if (session('status'))
                    @php
                        $session= session('status');
                    @endphp
                        <div class="alert alert-{{$session['status']}}">
                            {{ $session['message'] }}
                        </div>
                @endif
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Data Kegiatan Pemetaan Kawasan Rawan Narkoba<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(75, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(75, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('pemberdayaan/dir_alternative/add_altdev_kawasan_rawan')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
									@if(count($data) && isset($current_page))
										<a href="{{route('print_page',['altdev_kawasan_rawan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
											<i class="fa fa-print"></i> Cetak
										</a>
									@endif
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">
							@include('_templateFilter.dayamas_altdevkawasanrawan_filter')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal Kegiatan</th>
											<th>Pelaksana</th>
											<th>Lokasi Kawasan Rawan</th>
											<th>Jenis Geografis</th>
											<th>Luas Kawasan (m2) </th>
											<th>Status </th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data) > 0)
									@php $i = $start_number; @endphp
									@foreach($data as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d->tgl_pelaksanaan ? date('d/m/Y',strtotime($d->tgl_pelaksanaan)) : ''}} </td>
											<td> {{$d->nm_instansi}}</td>
											<td> {{$d->lokasi_kawasan_rawan}} </td>
											<td> {{(isset($jenis_geografis[$d->kode_geografis]) ? $jenis_geografis[$d->kode_geografis] : $d->kode_geografis)}}</td>
											<td> {{$d->luas_kawasan}} </td>
											<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(75, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberdayaan/dir_alternative/edit_altdev_kawasan_rawan/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(75, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)" ><i class="fa fa-trash"></i></button>

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
							                        	Data Kegiatan Pemetaan Kawasan Rawan Narkoba Yang Anda Cari Tidak Tersedia.
							                        @else
							                         	Data Kegiatan Pemetaan Kawasan Rawan Narkoba Tidak tersedia.
							                        @endif
						                      	@else
					                          		Data Kegiatan Pemetaan Kawasan Rawan Narkoba Tidak tersedia.
						                      	@endif

											</div>
										</td>
									</tr>
								@endif
								</tbody>
							</table>
							@if(count($data) > 0)
								{!! $pagination !!}
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
