@extends('layouts.base_layout')
@section('title', 'Data Pendataan Jaringan')
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
							<h2>Data Jaringan Narkoba yang Sudah Diungkap<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								{{-- <li class=""> --}}
              					<li class="" @php if(!in_array(23, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								{{-- <li class=""> --}}
              					<li class="" @php if(!in_array(23, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{url('pemberantasan/dir_intelijen/add_pendataan_jaringan')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
					                  <a href="{{url('pemberantasan/dir_intelijen/print_pendataan_jaringan'.$kondisi)}}" class="btn btn-lg btn-round btn-dark">
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
				            @include('_templateFilter.intelijen_jaringan_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nomor LKN</th>
											<th>Jenis Jaringan</th>
											<!-- <th>Keterlibatan Jaringan</th> -->
											<th>Nama Jaringan</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data_kasus))
									@php $i = $start_number; @endphp
									@foreach($data_kasus as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nomor_lkn']}}</td>
											<td> {{($d['kode_jenisjaringan'])}}</td>
											<!-- <td> {{($d['keterlibatan_jaringan'] == 'Y' ? 'Ya' : 'Tidak')}} </td> -->
											<td> {{$d['nama_jaringan']}} </td>
											<td> {{( ($d['status'] == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}} </td>
											{{--<td>
												<a href="{{url('pemberantasan/dir_intelijen/edit_pendataan_jaringan/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
				                            </td>--}}
				                            <td>
												<a @php if(!in_array(23, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberantasan/dir_intelijen/edit_pendataan_jaringan/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(23, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
								@else
								<tr>
									<td colspan="6">
										<div class="alert-messages alert-warning">
											@if(isset($filter))
						                      @if(count($filter) >0)
						                        Data Pendataan Jaringan Narkoba Yang Anda Cari Tidak Tersedia.
						                      @else
						                        Data Pendataan Jaringan Narkoba Tidak tersedia.
						                      @endif
						                    @else
						                        Data Pendataan Jaringan Narkoba Tidak tersedia.
						                    @endif

										</div>
									</td>
								</tr>
								@endif
								</tbody>
								</table>
							@if(count($data_kasus) > 0)
				              {!! $pagination !!}
				            @endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

  @include('modal.modal_inputNihil')
  @include('modal.modal_delete_form')
@endsection
