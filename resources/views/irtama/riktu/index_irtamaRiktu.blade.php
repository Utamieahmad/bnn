@extends('layouts.base_layout')
@section('title', 'Data Audit dengan Tujuan Tertentu')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
				</div>

			</div>
		</div>


		<div class="clearfix"></div>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Data Audit dengan Tujuan Tertentu<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(119, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							<li class="" @php if(!in_array(119, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('irtama/riktu/add_irtama_riktu')}}" class="btn btn-lg btn-round btn-primary">
									<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">
								<a href="{{route('print_page_irtama',['irtama_riktu',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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

						@include('_templateFilter.irtama_riktu')
						<table id="datatable-responsive" class="col-left table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>No. Surat Perintah</th>
									<th>No. Hasil Laporan</th>
									<th width="10%">Tgl. Hasil Laporan</th>
									<th>Judul Hasil Laporan</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data_riktu))
								@php $i = $start_number; @endphp
								@foreach($data_riktu as $a)
								<tr>
									<td> {{$i}}</td>
									<td> {{$a->no_sprint}}</td>
									<td> {{$a->no_hasil_laporan}}</td>
									<td> {{($a->tgl_hasil_laporan ? date('d/m/Y', strtotime($a->tgl_hasil_laporan)) : '')}} </td>
									<td> {{$a->judul_hasil_laporan}} </td>
									<td> {{($a->status == 'Y' ? 'Lengkap' : ($a->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) )}}</td>
									<td>
										<a @php if(!in_array(119, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('irtama/riktu/edit_irtama_riktu/'.$a->id)}}"><i class="fa fa-pencil"></i></a>
										<button @php if(!in_array(119, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$a->id}}" ><i class="fa fa-trash"></i></button>

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
						                        	Data Audit dengan Tujuan Yang Anda Cari Tidak tersedia.
						                        @else
						                        	Data Audit dengan Tujuan Tertentu Tidak tersedia.
						                        @endif
					                      	@else
					                          		Data Audit dengan Tujuan Tertentu Tidak tersedia.
					                      	@endif

										</div>
									</td>
								</tr>
								@endif
							</tbody>
						</table>
						@if(count($data_riktu) > 0)
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
