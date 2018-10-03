@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Penguatan Asistensi')

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
			var TITLE = '{{$title}}';
			</script>

			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Data Kegiatan<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="">
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="">
							<a href="{{url('pencegahan/dir_advokasi/add_penguatan_asistensi')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							<a href="{{URL('/pencegahan/dir_advokasi/printpenguatan?page='.$page['page'].'')}}" class="btn btn-lg btn-round btn-dark">
							<i class="fa fa-print"></i> Cetak
							</a>
							</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">
							@if(count($data_advoasistensipenguatan))

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Pelaksana</th>
											<th>Sasaran  </th>
											<th>Instansi/Peserta</th>
											<th>Sumber Anggaran</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@php $i = 1; @endphp
									@foreach($data_advoasistensipenguatan as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{date('d-m-Y', strtotime($d['tgl_pelaksanaan']))}}</td>
											<td> {{$d['nm_instansi']}}</td>
											<td> {{$d['kodesasaran']}} </td>
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
											<td>
												<a href="{{url('pencegahan/dir_advokasi/edit_penguatan_asistensi/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
				                              	<button data-url='{{$title}}' type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
								</tbody>
								</table>
							@else
								<div class="alert alert-warning">
									Data Kasus belum tersedia.
								</div>
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
