@extends('layouts.base_layout')
@section('title', 'Master Data Barang Bukti')
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
							<h2>Master Data Barang Bukti<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(144, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahbarangbukti" onclick="tambah_datadetail()" data-url="{{URL('/master/save_barangbukti')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_barangbukti">
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
				            @include('_templateFilter.master_barbuk_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Barang Bukti</th>
											<th>Satuan</th>
											<th>Jenis Barang Bukti</th>
											<th>Jenis Kasus</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nm_brgbukti']}}</td>
											<td> {{$d['nm_satuan']}}</td>
											<td> {{$d['nm_jnsbrgbukti']}}</td>
											<td> {{$d['nm_jnskasus']}}</td>
											<td>
												<a @php if(!in_array(144, Session::get("canedit")))  echo 'style="display:none;"'; @endphp id="edit{{$d['id_brgbukti']}}" onclick="edit_datadetail({{$d['id_brgbukti']}});" data-url="{{URL('/master/update_barangbukti')}}" data-id="{{$d['id_brgbukti']}}" data-kasus="{{$d['kd_jnskasus']}}" data-jenis="{{$d['kd_jnsbrgbukti']}}" data-satuan="{{$d['kd_satuan']}}" data-nama="{{$d['nm_brgbukti']}}" >
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(144, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id_brgbukti']}}" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="6">
												<div class="alert-messages alert-warning">
													@if(isset($filter))
			                      @if(count($filter) >0)
			                        Data Barang Bukti Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Barang Bukti belum tersedia.
			                      @endif
			                    @else
			                        Data Barang Bukti belum tersedia.
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
      document.getElementById("form_modalBarangbukti").reset();
      var id = $('#edit'+editID).attr('data-id');
      var nama = $('#edit'+editID).attr('data-nama');
      var kasus = $('#edit'+editID).attr('data-kasus');
      var jenis = $('#edit'+editID).attr('data-jenis');
      var satuan = $('#edit'+editID).attr('data-satuan');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_barangbukti').find('form').attr('action', url);
      $('#modal_master_barangbukti').find('#id').val(id);
      $('#modal_master_barangbukti').find('#nm_brgbukti').val(nama);
      $('#modal_master_barangbukti').find('#kd_jnskasus').val(kasus).trigger('change');
      $('#modal_master_barangbukti').find('#kd_jnsbrgbukti').val(jenis).trigger('change');
      $('#modal_master_barangbukti').find('#kd_satuan').val(satuan).trigger('change');
      $('#modal_master_barangbukti').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalBarangbukti").reset();
      var url = $('#tambahbarangbukti').attr('data-url');
      $('#modal_master_barangbukti').find('form').attr('action', url);
      $('#modal_master_barangbukti').find('#kd_jnskasus').val('').trigger('change');
      $('#modal_master_barangbukti').find('#kd_jnsbrgbukti').val('').trigger('change');
      $('#modal_master_barangbukti').find('#kd_satuan').val('').trigger('change');
    }

  </script>
  @include('modal.modal_master_barangbukti')
  @include('modal.modal_delete_form')
@endsection
