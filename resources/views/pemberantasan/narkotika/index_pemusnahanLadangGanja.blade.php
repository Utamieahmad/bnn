@extends('layouts.base_layout')
@section('title', 'Pemusnahan Ladang Tanaman Narkotika')
@section('content')
<div class="right_col" role="main">
	<div class="m-t-40">
		<div class="page-title">
			<div class="">
				{!! (isset($breadcrumps) ? $breadcrumps : '' )!!}
				<!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="row">

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Data Pemusnahan Ladang Tanaman Narkotika<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							{{-- <li class=""> --}}
              				<li class="" @php if(!in_array(20, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							{{-- <li class=""> --}}
          					<li class="" @php if(!in_array(20, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('pemberantasan/dir_narkotika/add_pendataan_pemusnahan_ladangganja')}}" class="btn btn-lg btn-round btn-primary">
									<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">
								<a href="{{url('pemberantasan/dir_narkotika/print_ladang'.$kondisi)}}" class="btn btn-lg btn-round btn-dark">
									<i class="fa fa-print"></i> Cetak
								</a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content ">
						@if(session('status'))
			                @php
			                    $session= session('status');
			                @endphp
			                <div class="alert alert-{{$session['status']}}">
			                    {{ $session['message'] }}
			                </div>
			            @endif
			            @include('_templateFilter.narkotika_lahan_filter')

						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Nomor Surat Perintah</th>
									<th>Tanggal Penyelidikan</th>
									<th>Luas Lahan Tanaman Narkotika (m²/Hektar)</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data_pemusnahanladang) > 0)
									@php $i = $start_number; @endphp
									@foreach($data_pemusnahanladang as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nomor_sprint_penyelidikan']}}</td>
											<td> {{ ( $d['tgl_penyelidikan'] ? date('d/m/Y', strtotime($d['tgl_penyelidikan'])) : '') }}</td>
											<td> {{ number_format($d['luas_lahan_ganja'])}} </td>
											<td> {{( ($d['status'] == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}} </td>
											{{--<td>
												<a href="{{url('pemberantasan/dir_narkotika/edit_pendataan_pemusnahan_ladangganja/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
											</td>--}}
											<td>
												<a @php if(!in_array(20, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberantasan/dir_narkotika/edit_pendataan_pemusnahan_ladangganja/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(20, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
											</td>
										</tr>
										@php $i = $i+1; @endphp
									@endforeach
								@else
									<tr>
										<td colspan="5">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                      @if(count($filter) >0)
							                        Data Kasus Yang Anda Cari Tidak Tersedia.
							                      @else
							                        Data Kasus Tidak tersedia.
							                      @endif
							                    @else
							                        Data Kasus Tidak tersedia.
							                    @endif

											</div>
										</td>
									</tr>
								@endif
							</tbody>
						</table>
						@if(count($data_pemusnahanladang))
							{!! $pagination !!}
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('modal.modal_delete_form')
@include('modal.modal_input_nihil')
@endsection
