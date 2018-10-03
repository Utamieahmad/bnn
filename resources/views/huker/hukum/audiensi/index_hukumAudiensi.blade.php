@extends('layouts.base_layout')
@section('title', 'Data Kegiatan Konsultasi Hukum (Audiensi)')

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
							<h2>Data Kegiatan Konsultasi Hukum (Audiensi)<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(81, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
							<i class="fa fa-plus-circle"></i> Input Nihil
							</a>
							</li>
							<li class="" @php if(!in_array(81, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="{{url('huker/dir_hukum/add_hukum_audiensi')}}" class="btn btn-lg btn-round btn-primary">
							<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
							</a>
							</li>
							<li class="">
							@if(isset($data) && isset($current_page))
								<a href="{{route('print_hukum_audiensi',['print_hukum_audiensi',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
								<i class="fa fa-print"></i> Cetak
								</a>
							@endif
							</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content ">

								@include('_templateFilter.hukum_audiensi_filter')
								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Pelaksana</th>
											<th>No Surat Perintah BNN</th>
											<th>No Surat Perintah Deputi</th>
											<th>Tempat Kegiatan</th>
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
												<td> {{$d->nm_instansi}}</td>
												<td> {{$d->no_sprint_kepala}}</td>
												<td> {{$d->no_sprint_deputi}}</td>
												<td> {{$d->tempat_kegiatan}}</td>
						                        <td>  @if($d->status == 'Y')
						                                Lengkap
						                              @elseif($d->status == 'N')
						                                Tidak Lengkap
						                              @endif </td>
												<td>
													<a @php if(!in_array(81, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('huker/dir_hukum/edit_hukum_audiensi/'.$d->id)}}"><i class="fa fa-pencil"></i></a>
													<button @php if(!in_array(81, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-target="{{$d->id}}"  onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
										@php $i = $i+1; @endphp
										@endforeach
					                @else
					                <tr>
					                  <td colspan="9">
					                    <div class="alert-messages alert-warning">
					                      @if(isset($filter))
					                        @if(isset($filter['selected']))
					                          Data Kegiatan Konsultasi Hukum (Audiensi) Yang Anda Cari Belum Tersedia.
					                        @else
					                          Data Kegiatan Konsultasi Hukum (Audiensi) Belum Tersedia.
					                        @endif
					                      @else
					                          Data Kegiatan Konsultasi Hukum (Audiensi) Belum Tersedia.
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
