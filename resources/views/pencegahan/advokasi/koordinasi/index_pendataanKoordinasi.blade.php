@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Rapat Koordinasi')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
				</div>
			</div>
    </div>
			<script>
			var TOKEN = '{{$token}}';
			var TITLE = '{{$titledel}}';
			</script>

			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Data Kegiatan Rapat Koordinasi<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(51, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(51, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('pencegahan/dir_advokasi/add_pendataan_koordinasi')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							<a href="{{URL('/pencegahan/dir_advokasi/printkoordinasi?'.$forprint)}}" class="btn btn-lg btn-round btn-dark">
							<i class="fa fa-print"></i> Cetak
							</a>
							</li>
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-success" data-toggle="modal" data-target="#modal_report_excel" onClick="reportExcel(event,this)" data-url="{{url('pencegahan/dir_advokasi/downloadkoordinasi')}}">
									<i class="fa fa-file"></i> Excel
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
							@include('_templateFilter.cegah_advo_filter')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left2" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Pelaksana</th>
											<th>Sasaran  </th>
											<th>Instansi/Peserta</th>
											<th>Sumber Anggaran</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($data_advorakor))
									@php $i = $start_number; @endphp
									@foreach($data_advorakor as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{($d['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($d['tgl_pelaksanaan'])) : '')}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td>
												@php
												$meta = json_decode($d['meta_sasaran'],true);
												if(count($meta)){

													echo '<ol class="">';
														for($j = 0 ; $j < count($meta); $j++){

															echo '<li>'.$meta[$j].'</li>';
														}
														echo '</ol>';
													}else{
														echo '-';
													}
												@endphp
											</td>

											<td>
												@php
												$meta = json_decode($d['meta_instansi'],true);
												if(count($meta)){

													echo '<ol class="">';
														for($j = 0 ; $j < count($meta); $j++){

															echo '<li>'.$meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')</li>';
														}
														echo '</ol>';
													}else{
														echo '-';
													}
												@endphp
											</td>
											<td> {{$d['kodesumberanggaran']}} </td>
											<td>  @if($d['status'] == 'Y')
															Lengkap
														@elseif($d['status'] == 'N')
															Tidak Lengkap
														@endif </td>
											<td>
												<a @php if(!in_array(51, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pencegahan/dir_advokasi/edit_pendataan_koordinasi/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(51, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" onClick="delete_form(event,this)"><i class="fa fa-trash" ></i></button>


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
		                          Data Kegiatan Rapat Koordinasi Yang Anda Cari Tidak Tersedia.
		                        @else
		                          Data Kegiatan Rapat Koordinasi Tidak Tersedia.
		                        @endif
		                      @else
		                          Data Kegiatan Rapat Koordinasi Tidak Tersedia.
		                      @endif
		                    </div>
		                  </td>
		                </tr>
									@endif
								</tbody>
							</table>
							{{-- <ul id="pagination-demo" class="pagination-sm"></ul> --}}
						</div>
						@if(count($data_advorakor))
							<div class="pagination_wrap">
								{!! $pagination !!}
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
@include('modal.modal_delete_form')
	@include('modal.modal_input_nihil')
  @include('modal.modal_report_excel')
	@endsection
