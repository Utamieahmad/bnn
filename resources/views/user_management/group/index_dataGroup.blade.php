@extends('layouts.base_layout')
@section('title', 'Data Group')
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
							<h2>Master Data Group<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(133, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('user_management/add_group')}}" class="btn btn-lg btn-round btn-primary">
								<i class="fa fa-plus-circle"></i> Tambah Data
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
				            @include('_templateFilter.master_group_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Group</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['group_name']}}</td>
											<td>
												<a @php if(!in_array(133, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('user_management/edit_group').'/'.$d['group_id']}}">
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(133, Session::get("canedit")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['group_id']}}" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="3">
												<div class="alert-messages alert-warning">
													@if(isset($filter))
			                      @if(count($filter) >0)
			                        Data Group Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Group belum tersedia.
			                      @endif
			                    @else
			                        Data Group belum tersedia.
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
