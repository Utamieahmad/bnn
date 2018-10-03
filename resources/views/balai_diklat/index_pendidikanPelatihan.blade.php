@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat')

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
							<h2>Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(93, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(93, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{route('add_pendidikan_pelatihan')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									@if(count($data) && isset($current_page))
										<a href="{{route('page_balai_diklat',['pendidikan_pelatihan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
	                        @include('_templateFilter.balaidiklat_filter')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Kegiatan</th>
											<th>Periode Pelaksanaan</th>
											<th>Tempat </th>
					                        <th>Jumlah Peserta</th>
					                        <th>Tujuan Kegiatan</th>
					                        <th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
									@if(count($data))
										@php $i = $start_number; @endphp
										@foreach($data as $d)
											<tr>
												<td> {{$i}}</td>
												<td> {{$d->nama_kegiatan}}</td>
												<td>
													{{ ( $d->tgl_pelaksanaan ? date('d/m/Y',strtotime($d->tgl_pelaksanaan)) :'' )}}
													{{ ( ($d->tgl_pelaksanaan && $d->tgl_selesai) ? '- ' :'')}}
													{{ ( $d->tgl_selesai ? date('d/m/Y',strtotime($d->tgl_selesai)) :'' )}}
												</td>
												<td> {{$d->tempat}} </td>
												<td> {{$d->total_peserta}} </td>
												<td> {{$d->tujuan_kegiatan}} </td>
												<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
												<td>
													<a @php if(!in_array(93, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_pendidikan_pelatihan',$d->id)}}"><i class="fa fa-pencil"></i></a>
					                              	<button @php if(!in_array(93, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

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
								                        	Data Kegiatan Pendidikan dan Pelatihan  Yang Anda Cari Belum Tersedia.
								                        @else
								                         	Data Kegiatan Pendidikan dan Pelatihan belum tersedia.
								                        @endif
							                      	@else
						                          		Data Kegiatan Pendidikan dan Pelatihan belum tersedia.
							                      	@endif

												</div>
											</td>
										</tr>
									@endif
									</tbody>
								</table>
								{!! isset($pagination) ? $pagination : '' !!}

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


@include('modal.modal_inputNihil')
@include('modal.modal_delete_form')
@endsection
