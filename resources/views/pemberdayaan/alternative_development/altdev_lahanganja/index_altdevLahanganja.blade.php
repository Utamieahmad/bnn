@extends('layouts.base_layout')
@section('title', 'Data Alih Fungsi Lahan Ganja')

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
							<h2> Alih Fungsi Lahan Ganja<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(73, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(73, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{route('add_altdev_lahan_ganja')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									@if(count($data) && isset($current_page))
										<a href="{{route('print_page',['altdev_lahan_ganja',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
											<i class="fa fa-print"></i> Cetak
										</a>
									@endif
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
							@include('_templateFilter.dayamas_altlahanganja_filter')


								<table id="datatable-responsive" class="col-left table table-striped dt-responsive nowrap col-left2" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th width="10%">No</th>
											<th width="15%">Tanggal Kegiatan</th>
											<th width="20%" >Pelaksana</th>
											<th width="10%" >Penyelenggara </th>
											<th width="10%" >Komoditi </th>
											<th width="10%">Status </th>
											<th width="10%">Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data) > 0)
									@php $i = $start_number; @endphp
									@foreach($data as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{ ($d->tgl_kegiatan ? date('d/m/Y',strtotime($d->tgl_kegiatan))  : '' )}}</td>
											<td> {{$d->idpelaksana}}</td>
											<td>
												@php
													$meta_penyelenggara = "";
													if($d->meta_kode_penyelenggara){
														$json = json_decode($d->meta_kode_penyelenggara);
														$meta_penyelenggara .= '<ul class="dot">';
														foreach($json as $j => $jval){
															$meta_penyelenggara .= '<li>'.( isset($penyelenggara[$jval]) ? $penyelenggara[$jval] : $jval ).'</li>';
														}
														$meta_penyelenggara .= '</ul>';
													}else{
														$meta_penyelenggara = "";
													}
												@endphp
												{!!  $meta_penyelenggara  !!}
											</td>
											<td>
												<?php
													$komoditi =[];
													$komoditi_list = "";
													if($d->meta_kode_komoditi ){
														$komoditi = json_decode($d->meta_kode_komoditi,true);
														if(count($komoditi) > 0){
															$komoditi_list .= '<ul class="text-left">';
															foreach($komoditi as $k => $kval){
																$komoditi_list .= '<li>'.$kval.'</li>';
															}
															$komoditi_list .= '</ul>';
														}
													}
												?>
												{!! $komoditi_list !!}
											</td>
											<td> {{( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(73, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_altdev_lahan_ganja',$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(73, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

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
						                        	Data Alih Fungsi Lahan Ganja Yang Anda Cari Tidak Tersedia.
						                        @else
						                         	Data Alih Fungsi Lahan Ganja Tidak tersedia.
						                        @endif
					                      	@else
				                          		Data Alih Fungsi Lahan Ganja Tidak tersedia.
					                      	@endif
										</div>
									</td>
								</tr>
								@endif
								</tbody>
								</table>
								{!! $pagination !!}


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')

@endsection
