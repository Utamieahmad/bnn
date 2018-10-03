@extends('layouts.base_layout')
@section('title', 'Data Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan ')

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
							<h2>Data Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(100, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(100, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('puslitdatin/bidang_litbang/add_survey_narkoba_ketergantungan')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
								<a href="{{route('print_page_puslitdatin',['survey_narkoba_ketergantungan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
							@include('_templateFilter.puslit_survey_ketergantungan_filter')
								@php
									$i = $start_number;
								@endphp
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tahun Pelaksanaan</th>
											<th>Kategori</th>
											<th>Jumlah Responden</th>
											<th>Jenis Narkoba</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@foreach($data as $d)
										<tr>
											<td>{{$i}}</td>
											<td>{{$d->tahun}} </td>
											<td>{{(isset($kategori[$d->kategori]) ? $kategori[$d->kategori] : $d->kategori)}} </td>
											<td>{{($d->jumlah_responden ? number_format($d->jumlah_responden) : '') }}</td>
											<td>
												<?php
													$meta = [];
													$list_meta = "";
													if($d->meta_data_narkoba){
														$meta =json_decode($d->meta_data_narkoba,true);
														if(count($meta) > 0 ){
															$list_meta .= '<ul class="text-left">';
															foreach($meta as $k => $m){
																if(isset($m['jenis_narkoba'])){
																	$list_meta .= '<li>';
																	$det = getDetailBarangBukti($m['jenis_narkoba']);
																	$list_meta .=  strtoupper($det);
																	$list_meta .= '</li>';
																}
															}
															$list_meta .= '</ul>';
														}else{
															$list_meta = "";
														}
													}else{
														$list_meta = "";
													}
												?>
												{!! $list_meta !!}
											</td>
											<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(100, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('puslitdatin/bidang_litbang/edit_survey_narkoba_ketergantungan/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(100, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
										@php
											$i = $i+1;
										@endphp
									@endforeach
								@else
									<tr>
										<td colspan="7">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Data Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan  Yang Anda Cari Tidak Tersedia.
							                        @else
							                        	Data Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan Tidak tersedia.
							                        @endif
						                      	@else
						                          	Data Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan Tidak tersedia.
						                      	@endif
											</div>
										</td>
									</tr>
								@endif
							</tbody>
						</table>
						<div class="pagination_wrap">
							@if(count($data) > 0)
								{!! $pagination !!}
							@endif
						</div>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')
@endsection
