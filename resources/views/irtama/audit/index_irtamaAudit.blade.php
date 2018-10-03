@extends('layouts.base_layout')
@section('title', 'Data Audit Laporan Hasil Audit')

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
						<h2>Data Audit Laporan Hasil Audit<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(117, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
									<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							<li class="" @php if(!in_array(117, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('irtama/audit/add_irtama_audit')}}" class="btn btn-lg btn-round btn-primary">
									<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">
								@if(count($data) && isset($current_page))
									<a href="{{route('print_page_irtama',['irtama_audit',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
			        			{{ $session['messages'] }}
			    			</div>
						@endif
						 @include('_templateFilter.irtama_audit_filter')

						<table id="datatable-responsive" class="table col-left table-striped dt-responsive nowrap datatables" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th width="15%">No LHA</th>
									<th width="15%">Tanggal Laporan</th>
									<th width="">Nama Satker</th>
									<th width="">Nama Ketua Tim</th>
									<th width="	">Status</th>
									<th width="10%">Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data) > 0)
									@php
										$i = $start_number;
									@endphp
									@foreach($data as $d)
										@php
											$ketua_tim = $d->ketua_tim;
											$nama_ketua_tim= '';
											if($ketua_tim){
												$nama = json_decode($ketua_tim,true);
												if(isset($nama[0]['nama'])){
													$nama_ketua_tim= $nama[0]['nama'];
												}else{
													$nama_ketua_tim= '';
												}
											}else{
												$nama_ketua_tim= '';
											}
										@endphp
										@php
						                    $nama_satker = $d->nama_satker;
						                    if($nama_satker){
							                    $j = json_decode($nama_satker);
							                    $satker = $j->satker;
						               		}else{
						               			$satker = "";
						               		}
					                    @endphp
										<tr>
											<td> {{$i}}</td>
											<td> {{$d->nomor_lha}} </td>
											<td> {{ ($d->tanggal_lha ? date('d/m/Y',strtotime($d->tanggal_lha)) :'')}} </td>
											<td> {{$satker}}</td>
											<td> {{ $nama_ketua_tim}} </td>
											<td> {{($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) )}}</td>
											<td>
												<a @php if(!in_array(117, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('irtama/audit/edit_irtama_audit/'.$d->id_lha)}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(117, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d->id_lha}}" ><i class="fa fa-trash"></i></button>

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
							                          Data Laporan Hasil Audit Yang Anda Cari Belum Tersedia.
							                        @else
							                          Data Laporan Hasil Audit Belum Tersedia
							                        @endif
						                      	@else
						                          	Data Laporan Hasil Audit Belum Tersedia
						                      	@endif

											</div>
										</td>
									</tr>
								@endif
							</tbody>
						</table>
						@if(count($data) > 0)
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
