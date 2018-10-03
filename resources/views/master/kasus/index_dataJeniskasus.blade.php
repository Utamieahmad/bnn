@extends('layouts.base_layout')
@section('title', 'Master Data Jenis Kasus')
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
							<h2>Master Data Jenis Kasus<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(142, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahjeniskasus" onclick="tambah_datadetail()" data-url="{{URL('/master/save_jeniskasus')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_jeniskasus">
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
				            @include('_templateFilter.master_jnskasus_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Jenis Kasus</th>
											<th>Actions</th>
										</tr>
									</thead>
								<tbody>
									@if(count($datamaster))
									@php $i = $start_number; @endphp
									@foreach($datamaster as $d)
										<tr>
											<td> {{$i}}</td>
											<td> {{$d['nm_jnskasus']}}</td>
											<td>
												<a @php if(!in_array(142, Session::get("canedit")))  echo 'style="display:none;"'; @endphp id="edit{{$d['id']}}" onclick="edit_datadetail({{$d['id']}});" data-url="{{URL('/master/update_jeniskasus')}}" data-id="{{$d['id']}}" data-nama="{{$d['nm_jnskasus']}}" >
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(142, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
				                            </td>
										</tr>
									@php $i = $i+1; @endphp
									@endforeach
									@else
										<tr>
											<td colspan="3">
												<div class="alert-messages alert-warning">
													@if(isset($filter))
			                      @if(count($filter) >0)
			                        Data Jenis Kasus Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Jenis Kasus belum tersedia.
			                      @endif
			                    @else
			                        Data Jenis Kasus belum tersedia.
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
      document.getElementById("form_modalJeniskasus").reset();
      var id = $('#edit'+editID).attr('data-id');
      var nama = $('#edit'+editID).attr('data-nama');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_jeniskasus').find('form').attr('action', url);
      $('#modal_master_jeniskasus').find('#id').val(id);
      $('#modal_master_jeniskasus').find('#nm_jnskasus').val(nama);
      $('#modal_master_jeniskasus').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalJeniskasus").reset();
      var url = $('#tambahjeniskasus').attr('data-url');
      $('#modal_master_jeniskasus').find('form').attr('action', url);
    }

  </script>
  @include('modal.modal_master_jeniskasus')
  @include('modal.modal_delete_form')
@endsection
