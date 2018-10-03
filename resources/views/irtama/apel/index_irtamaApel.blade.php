@extends('layouts.base_layout')
@section('title', 'Data Apel Senin &amp; Upacara')

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
							<h2>Data Apel Senin &amp; Upacara<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li class="" @php if(!in_array(125, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
										<i class="fa fa-plus-circle"></i> Input Nihil
									</a>
								</li>
								<li class="" @php if(!in_array(125, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
									<a href="{{url('irtama/apel/add_irtama_apel')}}" class="btn btn-lg btn-round btn-primary">
										<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
									</a>
								</li>
								<li class="">
									<a href="{{route('print_page_irtama',['irtama_apel',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
							@include('_templateFilter.irtama_apel')
							<table id="datatable-responsive" class="col-left2 table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
								<thead>
			                      <tr>
			                        <th>No</th>
			                        <th>Tanggal</th>
			                        <th>Jenis kegiatan</th>
			                        <th>Satker</th>
			                        <th>Jumlah Hadir</th>
			                        <th>Jumlah Tidak Hadir</th>
			                        <th>Status</th>
			                        <th>Action</th>
			                      </tr>
			                    </thead>
								<tbody>
								@if(count($apel))
									@php $i = $start_number; @endphp
									@foreach($apel as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{( $d['tanggal'] ? date('d/m/Y', strtotime($d['tanggal'])) : '')}}</td>
											<td> {{$d['jenis_kegiatan']}}</td>
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
											<td> {{$d['jumlah_hadir']}}</td>
											<td> {{$d['jumlah_tidak_hadir']}}</td>
											<td> {{ ($d['status'] ?  ( ($d['status']== 'Y') ? 'Lengkap' : 'Tidak Lengkap'): 'Belum Lengkap')}}</td>
											<td>
												<a @php if(!in_array(125, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('irtama/apel/edit_irtama_apel/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
                      								<button @php if(!in_array(125, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-url="apelupacara" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>

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
							                          Data Apel Senin &amp; Upacara  Yang Anda Cari Belum Tersedia.
							                        @else
							                          Data Apel Senin &amp; Upacara belum tersedia.
							                        @endif
						                      	@else
						                          	Data Apel Senin &amp; Upacara belum tersedia.
						                      	@endif

						                	</div>
						              	</td>
						            </tr>
								@endif
							</tbody>
						</table>
						{!! ( isset($pagination) ? $pagination :'') !!}
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
				<input type="hidden" class="audit_menu" value="Inspektorat Utama - Apel Senin & Upacara Hari Besar Lainnya"/>
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

	@include('modal.modal_input_nihil')
@endsection
