@extends('layouts.base_layout')
@section('title', 'Activity Log')
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
							<h2>Activity Log<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							{{-- <li class="">
								<a href="{{url('user_management/add_group')}}" class="btn btn-lg btn-round btn-primary">
								<i class="fa fa-plus-circle"></i> Tambah Data
							</a>
							</li> --}}
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
				            @include('_templateFilter.master_userlog_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama</th>
											<th>Satker</th>
											<th>Waktu</th>
											<th>Menu</th>
											<th>Event</th>

											<!-- <th>No</th>
											<th>Menu</th>
											<th>Event</th>
											<th>URL</th>
											<th>Ip</th>
											<th>Nama</th>
											<th>Waktu</th> -->
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nama']}}</td>
											<td> {{$d['satker']}}</td>
											<td> {{($d['created_at'] ? date('d/m/Y H:i:s', strtotime($d['created_at'])) : '')}}</td>
											<td> {{$d['audit_menu']}}</td>
											<td> {{$d['audit_event']}}</td>

											<!-- <td> {{$d['audit_menu']}}</td>
											<td> {{$d['audit_event']}}</td>
											<td> {{$d['audit_url']}}</td>
											<td> {{$d['audit_ip_address']}}</td>
											<td> {{$d['nama']}}</td>
											<td> {{($d['created_at'] ? date('d/m/Y H:i:s', strtotime($d['created_at'])) : '')}}</td> -->
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="7">
												<div class="alert-messages alert-warning">
													@if(isset($filter))
			                      @if(count($filter) >0)
			                        Data Log Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Log belum tersedia.
			                      @endif
			                    @else
			                        Data Log belum tersedia.
			                    @endif

												</div>
											</td>
										</tr>
										@endif
								</tbody>
								</table>

								@if(count($datamaster) > 0)
		                {!! $pagination !!}
		            @endif


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  @include('modal.modal_delete_form')
@endsection
