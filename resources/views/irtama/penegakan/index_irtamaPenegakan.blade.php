@extends('layouts.base_layout')
@section('title', 'Data Penegakan Disiplin')

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
							<h2>Data Penegakan Disiplin<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(124, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(124, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{url('irtama/penegakan/add_irtama_penegakan')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									<a href="{{route('print_page_irtama',['irtama_penegakan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
							@include('_templateFilter.irtama_penegakan')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
					                    <tr>
					                      <th>No</th>
					                      <th>No Laporan</th>
					                      <th>Tanggal Laporan</th>
					                      <th>Satker</th>
					                      <th>Status</th>
					                      <th>Action</th>
					                    </tr>
					                  </thead>
									<tbody>
										@if(count($penegakan))
											@php $i = $start_number; @endphp
											@foreach($penegakan as $d)
												<tr>
													<td> {{$i}}</td>
													<td> {{$d['no_laporan']}}</td>
													<td> {{( $d['tgl_laporan'] ? date('d/m/Y', strtotime($d['tgl_laporan'])) : '')}}</td>
													<td>
														@php
										                  $data_satker = $d['kode_satker'];
										                  $id_satker = "";
										                  if($data_satker){
										                    $j = json_decode($data_satker,true);
										                    $nama_satker = $j['nama'];
										                  }else{
										                    $nama_satker = "";
										                  }
										                @endphp

										                {{$nama_satker}}
													</td>
													<td> {{( ($d['status'] == 'Y') ? 'Lengkap' : 'Tidak Lengkap' )}}</td>
													<td>
														<a @php if(!in_array(124, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('irtama/penegakan/edit_irtama_penegakan/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
						                              	<button @php if(!in_array(124, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-url="penegakandisiplin" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>

						                            </td>
												</tr>
											@php $i = $i+1; @endphp
											@endforeach
										@else
											<tr>
								            	<td colspan="10">
								                <div class="alert-messages alert-warning">
							                	@if(isset($filter))
							                        @if(count($filter) >0)
							                          Data Penegakan Disiplin Yang Anda Cari Belum Tersedia.
							                        @else
							                          Data Penegakan Disiplin belum tersedia.
							                        @endif
						                      	@else
						                          	Data Penegakan Disiplin belum tersedia.
						                      	@endif

								                </div>
								              </td>
								            </tr>
										@endif
									</tbody>
								</table>
								@if(count($penegakan))
		          					{!! $pagination !!}
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
					<input type="hidden" class="audit_menu" value="Inspektorat Utama - Penegakan Disiplin"/>
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
