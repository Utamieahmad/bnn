@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Pembelaan Hukum (Pendampingan)')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Data Kegiatan Pembelaan Hukum (Pendampingan)<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(82, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(82, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('huker/dir_hukum/add_hukum_pendampingan')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							@if(isset($data) && isset($current_page))
								<a href="{{route('print_hukum_pendampingan',['print_hukum_pendampingan',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
								<i class="fa fa-print"></i> Cetak
								</a>
							@endif
							</a>
							</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">
								@include('_templateFilter.hukum_pendampingan_filter')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Permasalahan</th>
											<th>No. Laporan Polisi</th>
											<th>Tanggal Laporan</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
								@if(count($data))
									@php $i = $start_number; @endphp
									@foreach($data as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d->permasalahan}}</td>
											<td> {{$d->nomor_perkara}}</td>
											<td> {{date('d-m-Y', strtotime($d->tgl_perkara))}} </td>
						                    <td>  @if($d->status == 'Y')
						                            Lengkap
						                          @elseif($d->status == 'N')
						                            Tidak Lengkap
						                          @endif </td>
											<td>
												<a @php if(!in_array(82, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('huker/dir_hukum/edit_hukum_pendampingan/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(82, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-target="{{$d->id}}"  onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>				                              	

				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
					                @else
					                <tr>
					                  <td colspan="5">
					                    <div class="alert-messages alert-warning">
					                      @if(isset($filter))
					                        @if(isset($filter['selected']))
					                          Data Kegiatan Konsultasi Hukum Pendampingan (Litigasi) Yang Anda Cari Belum Tersedia.
					                        @else
					                          Data Kegiatan Konsultasi Hukum Pendampingan (Litigasi) Belum Tersedia.
					                        @endif
					                      @else
					                          Data Kegiatan Konsultasi Hukum Pendampingan (Litigasi) Belum Tersedia.
					                      @endif
					                    </div>
					                  </td>
					                </tr>
					                @endif

					              </tbody>
					              </table>
					              @if(count($data))
					                <div class="pagination_wrap">
					                  {!! $pagination !!}
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
