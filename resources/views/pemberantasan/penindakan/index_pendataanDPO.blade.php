@extends('layouts.base_layout')
@section('title', 'Daftar Pencarian Orang')

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
							<h2>Daftar Pencarian Orang (DPO)<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							{{-- <li class=""> --}}
              				<li class="" @php if(!in_array(26, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							{{-- <li class=""> --}}
              				<li class="" @php if(!in_array(26, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('pemberantasan/dir_penindakan/add_pendataan_dpo')}}" class="btn btn-lg btn-round btn-primary">
									<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">
								<a href="{{url('pemberantasan/dir_penindakan/print_pendataan_dpo'.$kondisi)}}" class="btn btn-lg btn-round btn-dark">
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
				            @include('_templateFilter.penindakan_dpo_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nomor Surat Perintah</th>
											<th>Nomor Identitas</th>
											<th>Alamat</th>
											<th>Jenis Kelamin</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data_dpo))
									@php $i = $start_number; @endphp
									@foreach($data_dpo as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nomor_sprint_dpo']}}</td>
											<td> {{$d['no_identitas']}}</td>
											<td> {{$d['alamat']}} </td>
											<td> {{$d['kode_jenis_kelamin']}} </td>
											<td> {{( ($d['status'] == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}} </td>
											{{--<td>
												<a href="{{url('pemberantasan/dir_penindakan/edit_pendataan_dpo/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
											</td>--}}
											<td>
												<a @php if(!in_array(26, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberantasan/dir_penindakan/edit_pendataan_dpo/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(26, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
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
								                        Data Pencarian Orang (DPO) Yang Anda Cari Tidak Tersedia.
								                      @else
								                        Data Pencarian Orang (DPO) Tidak tersedia.
								                      @endif
								                    @else
								                        Data Pencarian Orang (DPO) Tidak tersedia.
								                    @endif

												</div>
											</td>
										</tr>
									@endif
								</tbody>
								</table>
								@if(count($data_dpo) > 0)
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
