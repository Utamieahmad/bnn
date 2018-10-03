@extends('layouts.base_layout')
@section('title', 'Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika')

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
							<h2>Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(74, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(74, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{url('pemberdayaan/dir_alternative/add_altdev_alih_profesi')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									@if(count($data) && isset($current_page))
										<a href="{{route('print_page',['altdev_alih_profesi',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
								@include('_templateFilter.dayamas_altdevalihprofesi_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left2" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal Kegiatan</th>
											<th width="25%">Pelaksana</th>
											<th width="25%">Penyelenggara</th>
											<th>Lokasi Kawasan Rawan</th>
											<th> Status </th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@php $i = $start_number; @endphp
									@foreach($data as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{($d->tgl_kegiatan ? date('d/m/Y',strtotime($d->tgl_kegiatan)) : "")}}</td>
											<td> {{$d->nm_instansi}}</td>
											<td> {{ (isset($penyelenggara[$d->kodepenyelenggara]) ? $penyelenggara[$d->kodepenyelenggara] : $d->kodepenyelenggara) }}</td>
											<td> {{$d->nama_kegiatan}} </td>
											<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(74, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberdayaan/dir_alternative/edit_altdev_alih_profesi/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(74, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
								@else
									<tr>
										<td colspan="7">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika Yang Anda Cari Tidak Tersedia.
							                        @else
							                         	Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika Tidak tersedia.
							                        @endif
						                      	@else
					                          		Data Alih Jenis Profesi/Usaha Kawasan Rawan Narkotika Tidak tersedia.
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
