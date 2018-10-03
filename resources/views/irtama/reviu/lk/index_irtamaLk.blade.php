@extends('layouts.base_layout')
@section('title', 'Data Reviu Laporan Keuangan')

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
						<h2>Data Reviu Laporan Keuangan<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(126, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							<li class="" @php if(!in_array(126, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('irtama/reviu/add_irtama_lk')}}" class="btn btn-lg btn-round btn-primary">
									<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">

								<a href="{{route('print_page_irtama',['irtama_lk',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
			        			{{ $session['message'] }}
			    			</div>
						@endif
						@include('_templateFilter.irtama_lk_filter')
						<table id="datatable-responsive" class="col-left table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Objek Reviu</th>
									<th>Surat Perintah</th>
									<th>Ketua Tim</th>
									<th>Hasil reviu</th>
									<th>Tanggal Laporan</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@php $i = $start_number; @endphp
								@if(count($reviulk) > 0)
									@foreach($reviulk as $d)
										<tr>
											<td> {{$i}}</td>
											<td> UAPPA : {{$d['uappa']}}<br>
													 UAPPA-E1 : {{$d['uappa_e1']}}<br>
													 UAPPA-W : {{$d['uappa_w']}}<br>
													 UAKPA : {{$d['uakpa']}}<br>
											</td>
											<td> {{$d['no_sprint']}} </td>
											<td> {{$d['ketua_tim']}} </td>
											<td>
													<b>Hasil Reviu LRA:</b> {{$d['lap_realisasi']}}<br>
													<b>Hasil Reviu Neraca:</b> {{$d['neraca']}}<br>
													<b>Hasil Reviu LO:</b> {{$d['lap_operasional']}}<br>
													<b>Hasil Reviu LPE:</b> {{$d['lap_perubahan']}}<br>
													<b>Hasil Reviu CaLK:</b> {{$d['catatan_lap']}}
											</td>
											<td>{{( $d['tanggal_lap'] ? date('d/m/Y',strtotime($d['tanggal_lap'])) :'')}}</td>
											<td>{{( ($d['status'] == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}}</td>


											<td>
												<a @php if(!in_array(126, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('irtama/reviu/edit_irtama_lk/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(126, Session::get("candelete")))  echo 'style="display:none;"'; @endphp data-url='reviulk' type="button" class="btn btn-primary button-delete" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
											</td>
										</tr>
										@php $i = $i+1; @endphp
									@endforeach
								@else
									<tr>
										<td colspan="8">
											<div class="alert-messages alert-warning">
												Data Irtama Laporan Keuangan Belum Tersedia
											</div>
										</td>
									</tr>
								@endif
							</tbody>
						</table>
						@if(count($reviulk)>0)
							{!! (isset($pagination) ? $pagination : '' )!!}
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
			</div>
			<div class="modal-body">
				Apakah Anda ingin menghapus data ini ?
			</div>
			<input type="hidden" class="target_id" value=""/>
			<input type="hidden" class="audit_menu" value="Inspektorat Utama - Reviu Laporan Keuangan"/>
			<input type="hidden" class="audit_url" value="http://{{ $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}"/>
			<input type="hidden" class="audit_ip_address" value="{{ $_SERVER['SERVER_ADDR'] }}"/>
			<input type="hidden" class="audit_user_agent" value="{{ $_SERVER['HTTP_USER_AGENT'] }}"/>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
			</div>
		</div>
	</div>
</div>


@include('modal.modal_inputNihil')
@endsection
