@extends('layouts.base_layout')
@section('title', 'Data Pemantauan Tindak Lanjut')

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
						<h2>Data Pemantauan Tindak Lanjut<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(118, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>

							<li class="">

								@if(count($data) && isset($current_page))
									<a href="{{route('print_page_irtama',['irtama_ptl',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
										<i class="fa fa-print"></i> Cetak
									</a>
								@endif
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
								{{ $session['messages'] }}
							</div>
						@endif

						@include('_templateFilter.irtama_ptl')
						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>No. LHA</th>
									<th>Nama Satker</th>
									<th>Tanggal LHA</th>
									<th>Ketua Tim</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@php $i = $start_number; @endphp
								@if(count($data) > 0 )
									@foreach($data as $d)
									<tr>
										<td>{{$i}}</td>
										<td>{{$d->nomor_lha}}</td>
										<td>
											@php $satker_name = "" ; @endphp
											@if($d->nama_satker)
												@php
													$j = json_decode($d->nama_satker);
													$satker_name = $j->satker;
												@endphp
											@endif
											{{$satker_name }}
										</td>
										<td>{{($d->tanggal_lha ? ( date('d/m/Y',strtotime($d->tanggal_lha))):'')}}  </td>
										<td>
											@if($d->ketua_tim)
												@php
													$ketua_tim = "";
													$json = json_decode($d->ketua_tim,true);
													if(count($json) > 0 ){
														if(isset($json[0])){
															$j = $json[0];
															$ketua_tim = $j['nama'];
														}
													}else{
														$ketua_tim = "";
													}
												@endphp
												{{$ketua_tim}}
											@endif

										</td>
										<td>
											<a @php if(!in_array(118, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_irtama_ptl',$d->id_ptl)}}"><i class="fa fa-pencil"></i></a>
											<button @php if(!in_array(118, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-target="{{($d->id_ptl)}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>

										</td>
									</tr>
									@php $i = $i+1; @endphp
									@endforeach
								@else
								<tr>
									<td colspan="6">
										<div class="alert-messages alert-warning">
											Data Irtama Pemantauan Tindak Lanjut Belum Tersedia.
										</div>
									</td>
								</tr>
								@endif
							</tbody>
						</table>
						<div class="pagination-wrap">
							@if(count($data) > 0 )
								{!! (isset($pagination) ? $pagination : '') !!}
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
