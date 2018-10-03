@extends('layouts.base_layout')
@section('title', 'Data Log')
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
							<h2>Data Log<small></small></h2>
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
				            @include('_templateFilter.master_log_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Email</th>
											<th>Nama</th>
											<th>Waktu Login</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['email']}}</td>
											<td> {{$d['nama']}}</td>
											<td> {{($d['waktu_login'] ? date('d/m/Y H:i:s', strtotime($d['waktu_login'])) : '')}}</td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="4">
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
