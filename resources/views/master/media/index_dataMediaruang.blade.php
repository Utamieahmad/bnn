@extends('layouts.base_layout')
@section('title', 'Master Data Media Luar Ruang')
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
							<h2>Master Data Media Luar Ruang<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(149, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahmediaruang" onclick="tambah_datadetail()" data-url="{{URL('/master/save_mediaruang')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_mediaruang">
							<i class="fa fa-plus-circle"></i> Tambah Data
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
				            @include('_templateFilter.master_ruang_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Kode Media Luar Ruang</th>
											<th>Nama Media Luar Ruang</th>
											<th>Jenis Media</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['value_media']}}</td>
											<td> {{$d['nama_media']}}</td>
											<td> {{$d['nama_parent']}}</td>
											<td>
												<a @php if(!in_array(149, Session::get("canedit")))  echo 'style="display:none;"'; @endphp id="edit{{$d['id']}}" onclick="edit_datadetail({{$d['id']}});" data-url="{{URL('/master/update_mediaruang')}}" data-id="{{$d['id']}}" data-media="{{$d['parent_id']}}" data-kode="{{$d['value_media']}}" data-nama="{{$d['nama_media']}}" >
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(149, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="5">
												<div class="alert-messages alert-warning">
													@if(isset($filter))
			                      @if(count($filter) >0)
			                        Data Media Luar Ruang Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Media Luar Ruang belum tersedia.
			                      @endif
			                    @else
			                        Data Media Luar Ruang belum tersedia.
			                    @endif

												</div>
											</td>
										</tr>
										@endif
								</tbody>
								</table>

								@if(count($datamaster) > 0)
		                {!! $pagination !!}
		            @endif


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

  <script>
    function edit_datadetail(editID){
      document.getElementById("form_modalMediaruang").reset();
      var id = $('#edit'+editID).attr('data-id');
      var media = $('#edit'+editID).attr('data-media');
      var kode = $('#edit'+editID).attr('data-kode');
      var nama = $('#edit'+editID).attr('data-nama');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_mediaruang').find('form').attr('action', url);
      $('#modal_master_mediaruang').find('#id').val(id);
      $('#modal_master_mediaruang').find('#parent_id').val(media).trigger('change');
      $('#modal_master_mediaruang').find('#value_media').val(kode);
      $('#modal_master_mediaruang').find('#nama_media').val(nama);
      $('#modal_master_mediaruang').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalMediaruang").reset();
      var url = $('#tambahmediaruang').attr('data-url');
      $('#modal_master_mediaruang').find('form').attr('action', url);
      $('#modal_master_mediaruang').find('#parent_id').val('').trigger('change');
    }

  </script>
  @include('modal.modal_master_mediaruang')
  @include('modal.modal_delete_form')
@endsection
