@extends('layouts.base_layout')
@section('title', 'Master Data Bagian')
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
							<h2>Master Data Bagian<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(150, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahbagian" onclick="tambah_datadetail()" data-url="{{URL('/master/save_bagian')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_bagian">
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
				            @include('_templateFilter.master_bagian_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Bagian</th>
											<th>Biro</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['lookup_name']}}</td>
											<td> {{$d['nama_parent']}}</td>
											<td>
												<a @php if(!in_array(150, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp id="edit{{$d['id_settama_lookup']}}" onclick="edit_datadetail({{$d['id_settama_lookup']}});" data-url="{{URL('/master/update_bagian')}}" data-id="{{$d['id_settama_lookup']}}" data-biro="{{$d['id_lookup_parent']}}" data-nama="{{$d['lookup_name']}}" >
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(150, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id_settama_lookup']}}" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="4">
												<div class="alert-messages alert-warning">
													@if(isset($filter))
			                      @if(count($filter) >0)
			                        Data Bagian Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Bagian belum tersedia.
			                      @endif
			                    @else
			                        Data Bagian belum tersedia.
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
      document.getElementById("form_modalBagian").reset();
      var id = $('#edit'+editID).attr('data-id');
      var biro = $('#edit'+editID).attr('data-biro');
      var nama = $('#edit'+editID).attr('data-nama');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_bagian').find('form').attr('action', url);
      $('#modal_master_bagian').find('#id').val(id);
      $('#modal_master_bagian').find('#id_lookup_parent').val(biro).trigger('change');
      $('#modal_master_bagian').find('#lookup_name').val(nama);
      $('#modal_master_bagian').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalBagian").reset();
      var url = $('#tambahbagian').attr('data-url');
      $('#modal_master_bagian').find('form').attr('action', url);
      $('#modal_master_bagian').find('#id_lookup_parent').val('').trigger('change');
    }

  </script>
  @include('modal.modal_master_bagian')
  @include('modal.modal_delete_form')
@endsection
