@extends('layouts.base_layout')
@section('title', 'Master Data Jenis Barang Bukti')
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
							<h2>Master Data Jenis Barang Bukti<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(143, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahjenisbarbuk" onclick="tambah_datadetail()" data-url="{{URL('/master/save_jenisbarbuk')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_jenisbarbuk">
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
				            @include('_templateFilter.master_jnsbarbuk_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Kode Jenis Barang Bukti</th>
											<th>Nama Jenis Barang Bukti</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['kd_jnsbrgbukti']}}</td>
											<td> {{$d['nm_jnsbrgbukti']}}</td>
											<td>
												<a @php if(!in_array(143, Session::get("canedit")))  echo 'style="display:none;"'; @endphp id="edit{{$d['kd_jnsbrgbukti']}}" onclick="edit_datadetail({{$d['kd_jnsbrgbukti']}});" data-url="{{URL('/master/update_jenisbarbuk')}}" data-id="{{$d['kd_jnsbrgbukti']}}" data-nama="{{$d['nm_jnsbrgbukti']}}" >
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(143, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['kd_jnsbrgbukti']}}" ><i class="fa fa-trash"></i></button>
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
			                        Data Jenis Barang Bukti Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Jenis Barang Bukti belum tersedia.
			                      @endif
			                    @else
			                        Data Jenis Barang Bukti belum tersedia.
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
      document.getElementById("form_modalJenisbarbuk").reset();
      var id = $('#edit'+editID).attr('data-id');
      var nama = $('#edit'+editID).attr('data-nama');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_jenisbarbuk').find('form').attr('action', url);
      $('#modal_master_jenisbarbuk').find('#id').val(id);
      $('#modal_master_jenisbarbuk').find('#kd_jnsbrgbukti').val(id);
      $('#modal_master_jenisbarbuk').find('#nm_jnsbrgbukti').val(nama);
      $('#modal_master_jenisbarbuk').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalJenisbarbuk").reset();
      var url = $('#tambahjenisbarbuk').attr('data-url');
      $('#modal_master_jenisbarbuk').find('form').attr('action', url);
    }

  </script>
  @include('modal.modal_master_jenisbarbuk')
  @include('modal.modal_delete_form')
@endsection
