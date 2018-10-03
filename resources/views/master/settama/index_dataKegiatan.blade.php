@extends('layouts.base_layout')
@section('title', 'Master Data Kegiatan')
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
							<h2>Master Data Kegiatan<small></small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<li class="" @php if(!in_array(151, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
							<a href="#" id="tambahkegiatan" onclick="tambah_datadetail()" data-url="{{URL('/master/save_kegiatan')}}" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_master_kegiatan">
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
				            @include('_templateFilter.master_kegiatan_filter')

								<table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Kegiatan</th>
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
											<td> @php $metabiro = json_decode(str_replace('{','[',str_replace('}',']',$d['nama_parent']))); @endphp
												@foreach($metabiro as $br)
													{{$br}} <br>
												@endforeach
											</td>
											<td>
												<a @php if(!in_array(151, Session::get("canedit")))  echo 'style="display:none;"'; @endphp id="edit{{$d['id_settama_lookup']}}" onclick="edit_datadetail({{$d['id_settama_lookup']}});" data-url="{{URL('/master/update_kegiatan')}}" data-id="{{$d['id_settama_lookup']}}" data-biro="{{str_replace('{','[',str_replace('}',']',$d['id_parent']))}}" data-nama="{{$d['lookup_name']}}" >
												<i class="fa fa-pencil"></i></a>
												<button @php if(!in_array(151, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id_settama_lookup']}}" ><i class="fa fa-trash"></i></button>
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
			                        Data Kegiatan Yang Anda Cari Belum Tersedia.
			                      @else
			                        Data Kegiatan belum tersedia.
			                      @endif
			                    @else
			                        Data Kegiatan belum tersedia.
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
      document.getElementById("form_modalKegiatan").reset();
      var id = $('#edit'+editID).attr('data-id');
      var biro = $('#edit'+editID).attr('data-biro');
      var metabiro = JSON.parse(biro);
      var nama = $('#edit'+editID).attr('data-nama');
      var url = $('#edit'+editID).attr('data-url');

      $('#modal_master_kegiatan').find('form').attr('action', url);
      $('#modal_master_kegiatan').find('#id').val(id);
			$.each(metabiro, function(key, value) {
      	$('#modal_master_kegiatan').find('#id_parent[value="'+value+'"]').prop('checked',true);
			});
      $('#modal_master_kegiatan').find('#lookup_name').val(nama);
      $('#modal_master_kegiatan').modal('show');

    }

    function tambah_datadetail(){
      document.getElementById("form_modalKegiatan").reset();
      var url = $('#tambahkegiatan').attr('data-url');
      $('#modal_master_kegiatan').find('form').attr('action', url);
      $('#modal_master_kegiatan').find('#id_parent').prop('checked',false);
    }

  </script>
  @include('modal.modal_master_kegiatan')
  @include('modal.modal_delete_form')
@endsection
