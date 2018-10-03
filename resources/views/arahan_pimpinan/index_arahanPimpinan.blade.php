@extends('layouts.base_layout')
@section('title', 'Arahan Pimpinan')

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
						<h2>Data Arahan Pimpinan<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							<!-- <li class="">
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li> -->
							<li class="">
								<a href="{{url('arahan/pimpinan/add_arahan_pimpinan')}}" class="btn btn-lg btn-round btn-primary">
									<i class="fa fa-plus-circle c-yelow"></i> Tambah Arahan
								</a>
							</li>
							<li class="">
								<a href="{{route('print_arahan_pimpinan',[$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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

							@php
								$i = $start_number;
							@endphp
						@include('_templateFilter.arahan_pimpinan_filter')
						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal Arahan</th>
									<th>Satker</th>
									<th>Tanggal Kadaluarsa</th>
									<th>Judul Arahan</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							@if(count($data)>0)
								@foreach($data as $d)
								<tr>
									<td> {{$i}} </td>
									<td> {{( $d->tgl_arahan ? date('d/m/Y',strtotime($d->tgl_arahan)) : '')}} </td>
									<td>
										<?php
						                	$satker = $d->satker;
						                	$satker_name = "";
						                	if($satker){
						                		$j = json_decode($satker,true);
						                		$satker_name = $j['nama_satker'];
						                	}else{
						                		$satker_name = "";
						                	}
					                	?>
					                	{{$satker_name}}
									</td>
									<td> {{ ( $d->tgl_kadaluarsa ? date('d/m/Y',strtotime($d->tgl_kadaluarsa)) : '')}} </td>
									<td> {{$d->judul_arahan}} </td>
									<td> {{($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) )}}</td>
									<td>
										<a href="{{route('edit_arahan_pimpinan',$d->id)}}"><i class="fa fa-pencil"></i></a>
										<button type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d->id}}" ><i class="fa fa-trash"></i></button>
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
											Data Arahan Pimpinan belum tersedia.
										</div>
									</td>
								</tr>
							@endif
							</tbody>
						</table>
						<div class="pagination_wrap">
							@if(count($data))
								{!! $pagination !!}
							@endif
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@include('modal.modal_inputNihil')
@include('modal.modal_delete_form')
@endsection
