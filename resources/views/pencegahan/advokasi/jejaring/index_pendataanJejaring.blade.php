@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Membangun Jejaring')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
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
							<h2>Kegiatan Membangun Jejaring<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(52, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(52, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('pencegahan/dir_advokasi/add_pendataan_jejaring')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							<a href="{{URL('/pencegahan/dir_advokasi/printjejaring?'.$forprint)}}" class="btn btn-lg btn-round btn-dark">
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
							@include('_templateFilter.cegah_advo_filter')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Pelaksana</th>
											<th>Sasaran  </th>
											<th>Instansi</th>
											<th>Sumber Anggaran</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($data_advojejaring))
									@php $i = $start_number; @endphp
									@foreach($data_advojejaring as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{($d['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($d['tgl_pelaksanaan'])) : '')}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td> {{$d['kodesasaran']}} </td>
											<td>
												@php
												$meta = json_decode($d['meta_instansi'],true);
												if(count($meta)){

													echo '<ol class="">';
														for($j = 0 ; $j < count($meta); $j++){

															echo '<li>'.$meta[$j]['list_nama_instansi'].'</li>';
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
												<a @php if(!in_array(52, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pencegahan/dir_advokasi/edit_pendataan_jejaring/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button @php if(!in_array(52, Session::get("candelete")))  echo 'style="display:none;"'; @endphp data-url='{{$titledel}}' type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

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
														Data Kegiatan Membangun Jejaring Yang Anda Cari Tidak Tersedia.
													@else
														Data Kegiatan Membangun Jejaring Tidak Tersedia.
													@endif
												@else
														Data Kegiatan Membangun Jejaring Tidak Tersedia.
												@endif
											</div>
										</td>
									</tr>
								@endif
						</tbody>
						</table>
						<ul id="pagination-demo" class="pagination-sm"></ul>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('modal.modal_delete_form')
  @include('modal.modal_input_nihil')
@endsection
