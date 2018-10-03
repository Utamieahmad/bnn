
@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Monitoring dan Evaluasi')

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
							<h2>Data Kegiatan Monitoring dan Evaluasi<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(71, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(71, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{url('pemberdayaan/dir_masyarakat/add_psm_supervisi')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									<a href="{{route('print_page_dayamas',['psm_supervisi',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
										<i class="fa fa-print"></i> Cetak
									</a>
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
						@include('_templateFilter.dayamas_monev_filter')
						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal</th>
									<th>Pelaksana</th>
									<th>Sasaran</th>
									<th>Instansi / Peserta </th>
									<th>Sumber Anggaran </th>
									<th>Status </th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data_supervisi))
									@php $i = $start_number; @endphp
									@foreach($data_supervisi as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{( $d['tgl_pelaksanaan'] ? date('d/m/Y', strtotime($d['tgl_pelaksanaan'])) : '')}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td> {{ ( isset($sasaran[$d['kodesasaran']]) ? $sasaran[$d['kodesasaran']] : $d['kodesasaran']  )}}</td>
											<td>
												@php
													$meta = json_decode($d['meta_instansi']);

													if(count($meta)){
														echo '<ol class="text-left">';
														for($j = 0 ; $j < count($meta); $j++){
															echo '<li>'.$meta[$j]->list_nama_instansi.' / '.$meta[$j]->list_jumlah_peserta.'</li>';
														}
														echo '</ol>';
													}else{
														echo '-';
													}
												@endphp
											</td>
											<td> {{ ( isset($kode_anggaran[$d['kodesumberanggaran']]) ? $kode_anggaran[$d['kodesumberanggaran']] : $d['kodesumberanggaran'])}} </td>
											<td> {{($d['status'] == 'Y' ? 'Lengkap' : ($d['status'] == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) )}}</td>
											<td>
												<a @php if(!in_array(71, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberdayaan/dir_masyarakat/edit_psm_supervisi/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(71, Session::get("candelete")))  echo 'style="display:none;"'; @endphp data-url='{{$title}}' type="button" class="btn btn-primary button-delete-form" data-target="{{$d['id']}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

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
							                        	Data Monitoring dan Evaluasi Yang Anda Cari Tidak Tersedia.
							                        @else
							                         	Data Monitoring dan Evaluasi Tidak tersedia.
							                        @endif
						                      	@else
					                          		Data Monitoring dan Evaluasi Tidak tersedia.
						                      	@endif

											</div>
										</td>
									</tr>

								@endif
							</tbody>
						</table>
						{!! (isset($pagination) ? $pagination : '') !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')
@endsection
