@extends('layouts.base_layout')
@section('title', 'Riset Operasional Penyalahgunaan Narkoba di Indonesia')

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
							<h2>Riset Operasional Penyalahgunaan Narkoba<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(105, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(105, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('puslitdatin/bidang_litbang/add_riset_penyalahgunaan_narkoba')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
								@if(count($data) && isset($current_page))
									<a href="{{route('print_page_puslitdatin',['riset_penyalahgunaan_narkoba',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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

							@include('_templateFilter.puslit_riset_penyalahgunaan_filter')
								@php
									$i = $start_number;
								@endphp
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tahun</th>
											<th>Judul Riset</th>
											<th>Lokasi Riset </th>
											<!--th>Lokasi Kabupaten </th-->
											<th>Jumlah Responden</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@foreach($data as $d)
										<tr>
											<td>{{$i}}</td>
											<td>{{$d->tahun}}</td>
											<td>{{$d->judul}}</td>
											<td>
											@php
													if($d->meta_lokasi){
						                foreach(json_decode($d->meta_lokasi,true) as $m => $val){
						                  echo '<li>' . getWilayahName($val['propinsi']).'</li>';
						                }
													}
											@endphp
											</td>
											<!--td>{{$d->nm_wilayah}}</td-->
											<td>{{number_format($d->jumlah_responden)}}</td>
											<td> {{ ( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap') }}</td>
											<td>
												<a @php if(!in_array(105, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('puslitdatin/bidang_litbang/edit_riset_penyalahgunaan_narkoba/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(105, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
										@php
											$i = $i+1;
										@endphp
									@endforeach
								@else
									<tr>
										<td colspan="8">
											<div class="alert-messages alert-warning">
												@if(isset($filter))
							                        @if(count($filter) >0)
							                        	Riset Operasional Penyalahgunaan Narkoba Yang Anda Cari Tidak Tersedia.
							                        @else
							                        	Riset Operasional Penyalahgunaan Narkoba Tidak tersedia.
							                        @endif
						                      	@else
						                          	Riset Operasional Penyalahgunaan Narkoba Tidak tersedia.
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
