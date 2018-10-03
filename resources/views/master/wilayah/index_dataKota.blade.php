@extends('layouts.base_layout')
@section('title', 'Master Data Kota')
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
							<h2>Master Data Kota<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(141, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahkota" onclick="tambah_datadetail()" data-url="{{URL('/master/save_kota')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_kota">
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
				            @include('_templateFilter.master_kota_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Kota</th>
											<th>Provinsi</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nama']}}</td>
											<td> {{$d['nm_propinsi']}}</td>
											<td>
												<a @php if(!in_array(141, Session::get("canedit")))  echo 'style="display:none;"'; @endphp id="edit{{$d['id_wilayah']}}" onclick="edit_datadetail({{$d['id_wilayah']}});" data-url="{{URL('/master/update_kota')}}" data-id="{{$d['id_wilayah']}}" data-nama="{{$d['nm_wilayah']}}" data-prop="{{$d['wil_id_wilayah']}}" data-jns="{{$d['kd_jnswilayah']}}">
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(141, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id_wilayah']}}" ><i class="fa fa-trash"></i></button>
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
			                        Data Kota Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Kota belum tersedia.
			                      @endif
			                    @else
			                        Data Kota belum tersedia.
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
      document.getElementById("form_modalKota").reset();
      var id = $('#edit'+editID).attr('data-id');
      var nama = $('#edit'+editID).attr('data-nama');
      var prop = $('#edit'+editID).attr('data-prop');
      var jenis = $('#edit'+editID).attr('data-jns');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_kota').find('form').attr('action', url);
      $('#modal_master_kota').find('#id').val(id);
      $('#modal_master_kota').find('#nm_wilayah').val(nama);
      $('#modal_master_kota').find('#wil_id_wilayah').val(prop).trigger('change');
      $('#modal_master_kota').find('#kd_jnswilayah').val(jenis).trigger('change');
      $('#modal_master_kota').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalKota").reset();
      var url = $('#tambahkota').attr('data-url');
      $('#modal_master_kota').find('form').attr('action', url);
      $('#modal_master_kota').find('#wil_id_wilayah').val('').trigger('change');
      $('#modal_master_kota').find('#kd_jnswilayah').val().trigger('change');
    }

  </script>
  @include('modal.modal_master_kota')
  @include('modal.modal_delete_form')
@endsection
