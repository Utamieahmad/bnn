@extends('layouts.base_layout')
@section('title', 'Pendataan LKN')
@section('content')
<div class="right_col" role="main">
	<div class="m-t-40">
		<div class="page-title">
			<div class="">
				{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="row">

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Data Kasus<small></small></h2>
						<ul class="nav navbar-right panel_toolbox">
							{{-- <li class=""> --}}
              				<li class="" @php if(!in_array(19, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
								<i class="fa fa-plus-circle"></i> Input Nihil
								</a>
							</li>
							{{-- <li class=""> --}}
              				<li class="" @php if(!in_array(19, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
								<a href="{{url('pemberantasan/dir_narkotika/add_pendataan_lkn')}}" class="btn btn-lg btn-round btn-primary">
								<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
								</a>
							</li>
							<li class="">
							<a href="{{url('pemberantasan/dir_narkotika/print_pendataan_lkn'.$kondisi)}}" class="btn btn-lg btn-round btn-dark">
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
			            @include('_templateFilter.narkotika_lkn_filter')


						<table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Instansi</th>
									<th>Tanggal LKN</th>
									<th>Kelompok Kasus  </th>
									<th>Nomor Kasus</th>
									<th>Tersangka</th>
									<th>Barang Bukti</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data_kasus))
								@php $i = $start_number; @endphp
								@foreach($data_kasus as $d)
								<tr>
									<td> {{$i}}</td>
									<td> {{$d['instansi']}}</td>
									<td> {{ ( $d['kasus_tanggal'] ? date('d/m/Y', strtotime($d['kasus_tanggal'])) : '') }}</td>
									<td> {{$d['kasus_jenis']}} </td>
									<td> {{$d['no_lap']}} </td>
									<td>
										@php
										$meta = $d['tersangka'];
										if(count($meta)){
											echo '<ol class="">';
												for($j = 0 ; $j < count($meta); $j++){
													echo '<li>'.$meta[$j]['tersangka_nama'].'('.$meta[$j]['kode_jenis_kelamin'].')</li>';
												}
												echo '</ol>';
											}else{
												echo '<span class="center">-</span>';
											}
										@endphp
									</td>
									<td>
										@php
										$meta = $d['BrgBukti'];
										if(count($meta)){
											echo '<ol class="">';
												for($j = 0 ; $j < count($meta); $j++){
													if($meta[$j]['keterangan']!=''){
														echo '<li>'.$meta[$j]['keterangan'].'('.$meta[$j]['jumlah_barang_bukti'].' '.$meta[$j]['nm_satuan'].')</li>';
													}else{
														echo '<li>'.$meta[$j]['nm_brgbukti'].'('.$meta[$j]['jumlah_barang_bukti'].' '.$meta[$j]['nm_satuan'].')</li>';
													}

												}
												echo '</ol>';
											}else{
												echo '<span class="center">-</span>';
											}
										@endphp
									</td>
									<td> {{( ($d['status_kelengkapan'] == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}} </td>
									{{--<td>
										<a href="{{url('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$d['eventID'])}}">
										<i class="fa fa-pencil"></i></a>
										<button data-url='kasus' type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['eventID']}}" ><i class="fa fa-trash"></i></button>
									</td>--}}
									<td>
										<a @php if(!in_array(19, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$d['eventID'])}}">
										<i class="fa fa-pencil"></i></a>
										<button @php if(!in_array(19, Session::get("candelete")))  echo 'style="display:none;"'; @endphp data-url='kasus' type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['eventID']}}" ><i class="fa fa-trash"></i></button>
									</td>
								</tr>
											@php $i = $i+1; @endphp
											@endforeach
										@else
											<tr>
												<td colspan="9">
													<div class="alert-messages alert-warning">
														@if(isset($filter))
									                      @if(count($filter) >0)
									                        Data Kasus Yang Anda Cari Tidak Tersedia.
									                      @else
									                        Data Kasus Tidak tersedia.
									                      @endif
									                    @else
									                        Data Kasus Tidak tersedia.
									                    @endif

													</div>
												</td>
											</tr>
										@endif
									</tbody>
								</table>
								@if(count($data_kasus) > 0)
					              {!! $pagination !!}
					            @endif


								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{-- <div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
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
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
							<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
						</div>
					</div>
				</div>
			</div> --}}
			@include('modal.modal_inputNihil')
			@include('modal.modal_delete_form')
			@endsection
