@extends('layouts.base_layout')
@section('title', 'Data Tes Narkoba')

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
							<h2>Data Tes Narkoba<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">

								<li class="" @php if(!in_array(67, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>

								<li class="" @php if(!in_array(67, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{url('pemberdayaan/dir_masyarakat/add_pendataan_tes_narkoba')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>

								<li class="">
									<a href="{{route('print_page_dayamas',['pendataan_tes_narkoba',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
					        			{{ $session['messages'] }}
					    			</div>
								@endif
								@include('_templateFilter.dayamas_tesnarkoba_filter')

								<table id="datatable-responsive" class="col-left table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Instansi</th>
											<th>Tanggal Tes </th>
											<th>Jumlah Peserta </th>
											<th>Sasaran</th>
											<th>Kode Anggaran</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data_tes))
									@php $i = $start_number; @endphp
									@foreach($data_tes as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td> {{ ( $d['tgl_test']  ? date('d/m/Y', strtotime($d['tgl_test'])) : '')}}</td>
											<td> {{$d['jmlh_peserta']}} </td>
											<td> {{$d['sasaran_values']}} </td>
											<td> {{(($d['kodesumberanggaran'] == 'DIPA' ) ? 'DIPA' : 'NON DIPA')}} </td>
											<td> {{($d['status'] == 'Y' ? 'Lengkap' : ($d['status'] == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) )}}</td>
											<td>
												<a @php if(!in_array(67, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberdayaan/dir_masyarakat/edit_pendataan_tes_narkoba/'.$d['header_id'])}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(67, Session::get("candelete")))  echo 'style="display:none;"'; @endphp data-url='{{$title}}' type="button" class="btn btn-primary button-delete-form" data-target="{{$d['header_id']}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

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
						                          Data Tes Narkoba Yang Anda Cari Tidak Tersedia.
						                        @else
						                          Data Tes Narkoba Tidak tersedia.
						                        @endif
					                      	@else
					                          	Data Tes Narkoba Tidak tersedia.
					                      	@endif

										</div>
									</td>
								</tr>
								@endif
								</tbody>
							</table>
							@if(count($data_tes))
								{!!  isset($pagination) ? $pagination :'' !!}
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
