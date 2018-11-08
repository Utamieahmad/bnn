@extends('layouts.base_layout')
@section('title', 'Pendataan Razia')
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
						<h2>Data Razia<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							{{-- <li class="">
              				<li class="" @php if(!in_array(153, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
								<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							<li class=""> --}}
              				<li class="" @php if(!in_array(153, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('pemberantasan/add_razia')}}" class="btn btn-lg btn-round btn-primary">
								<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">
							<a href="{{url('pemberantasan/print_razia'.$kondisi)}}" class="btn btn-lg btn-round btn-dark">
									<i class="fa fa-print"></i> Cetak
								</a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content ">
						<br/>
						@if (session('status'))
			              @php
			                $session= session('status');
			              @endphp

			              <div class="alert alert-{{$session['status']}}">
			                  {{ $session['message'] }}
			              </div>
			            @endif
			            @include('_templateFilter.razia_filter')

                                            <div style="overflow-x:auto;">
						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal Razia</th>
									<th>Lokasi</th>
									<th>Uraian Singkat</th>
									<th>Jumlah Dirazia</th>
									<th>Jumlah Terindikasi</th>
									<th>Jumlah Ditemukan</th>
									<th>Keterangan Lainnya</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data_razia))
								@php $i = $start_number; @endphp
								@foreach($data_razia as $d)
								<tr>
									<td> {{$i}}</td>
									<td> {{ ( $d['tgl_razia'] ? date('d/m/Y', strtotime($d['tgl_razia'])) : '') }}</td>
									<td> {{$d['lokasi']}}</td>
									<td> {{$d['uraian_singkat']}} </td>
									<td> {{$d['jumlah_dirazia']}} </td>
									<td> {{$d['jumlah_terindikasi']}} </td>
									<td> {{$d['jumlah_ditemukan']}} </td>
									<td> {{$d['keterangan_lainnya']}} </td>
									<td>
										<a @php if(!in_array(153, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberantasan/edit_razia/'.$d['id'])}}">
										<i class="fa fa-pencil"></i></a>
										<button @php if(!in_array(153, Session::get("candelete")))  echo 'style="display:none;"'; @endphp data-url='razia' type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
									</td>
								</tr>
											@php $i = $i+1; @endphp
											@endforeach
										@else
											<tr>
												<td colspan="9">
													<div class="alert-messages alert-warning">
														@if(isset($filter))
									                      @if(count($filter) >0)
									                        Data Razia Yang Anda Cari Tidak Tersedia.
									                      @else
									                        Data Razia Tidak tersedia.
									                      @endif
									                    @else
									                        Data Razia Tidak tersedia.
									                    @endif

													</div>
												</td>
											</tr>
										@endif
									</tbody>
								</table>
                                                    </div>
								@if(count($data_razia) > 0)
					              {!! $pagination !!}
					            @endif


								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{-- <div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">

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
							<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
						</div>
					</div>
				</div>
			</div> --}}
			@include('modal.modal_inputNihil')
			@include('modal.modal_delete_form')
			@endsection
