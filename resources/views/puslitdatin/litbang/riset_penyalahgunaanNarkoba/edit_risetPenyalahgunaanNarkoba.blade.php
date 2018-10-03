@extends('layouts.base_layout')
@section('title', 'Ubah Data Riset Operasional Penyalahgunaan Narkoba di Indonesia')

@section('content')
    <div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
                <div class="">
                    {!! $breadcrumps !!}
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="title_right">
            </div>
        </div>
        <div class="clearfix"></div>
    	<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
            	<div class="x_panel">
	                <div class="x_title">
	                    <h2>Form Edit Riset Operasional Penyalahgunaan Narkoba</h2>
	                    <div class="clearfix"></div>
	                </div>
	    			<div class="x_content">
	                <br/>
	                @if (session('status'))
	                @php
	                	$session= session('status');
	                @endphp
    					<div class="alert alert-{{$session['status']}}">
		        			{{ $session['message'] }}
		    			</div>
					@endif

	           			<form action="{{URL('/puslitdatin/bidang_litbang/update_riset_penyalahgunaan_narkoba')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
	           				<input type="hidden" name="id" value="{{$data->id}}"/>
		    				<div class="form-body">
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 12 input-group date year-only'>
					             		<input type='text' name="tahun" value="{{ (isset($data->tahun) ? $data->tahun : '' ) }}" class="form-control" required />
					             		<span class="input-group-addon">
						                <span class="glyphicon glyphicon-calendar"></span>
						                </span>
					              	</div>
					          	</div>
		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="judul" value="@if(isset($data->judul)){{$data->judul}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

  		    					<div class="form-group">
						            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi</label>
						            <?php
						            	$meta_lokasi = [];
						            	if($data->meta_lokasi){
						            		$meta_lokasi = json_decode($data->meta_lokasi,true);
						            	}else{
						            		$meta_lokasi = [];
						            	}

						            ?>
						            <div class="col-md-9 col-sm-9 col-xs-12">
						                <div class="mt-repeater">
						                    <div data-repeater-list="meta_lokasi">
						                    	@if(count($meta_lokasi ) > 0 )
						                    		@foreach($meta_lokasi as $key => $m)
						                        <div data-repeater-item class="mt-repeater-item">
						                            <div class="row mt-repeater-row">
						                                <div class="col-md-6 col-sm-6 col-xs-12">
						                                    <label class="control-label">Provinsi</label>
						                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
                                  								<select class="form-control selectPropinsimeta" name="meta_lokasi[][propinsi]">
                                  									<option value="">-- Pilih Provinsi -- </option>
                                  									@foreach($propinsi as $p)
                                  									<option value="{{$p->id_wilayah}}" {{(isset($m['propinsi']) ? ( ($m['propinsi'] ==  $p->id_wilayah )? 'selected=selected' : '') : '')}}> {{$p->nm_wilayah}}</option>
                                  									@endforeach
                                  								</select>
              													        </div>
              													    </div>
  													    <div class="col-md-1 col-sm-1 col-xs-12 text-right p-r-0">
						                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
						                                        <i class="fa fa-close"></i>
						                                    </a>
						                                </div>
						                               {{-- <div class="clearfix"></div>
						                                <div class="col-md-6 col-sm-6 col-xs-12">
						                                    <label class="control-label">Kota / Kabupaten</label>
						                                    <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
                                  								<select class="form-control selectKabupatenmeta" name="meta_lokasi[][kabupaten]">
                                  									<option value="">-- Pilih Kabupaten -- </option>
                                                    {!! dropdownKabupaten($m['propinsi'],$m['kabupaten']) !!}
                                                    {{-- @foreach($kabupaten['data'] as $k)
                                										<option value="{{$k['id_wilayah']}}"  {{(isset($m['kabupaten']) ? ( ($m['kabupaten'] ==  $key )? 'selected=selected' : '') : '')}}> {{$k['nm_wilayah']}}</p>
                              											@endforeach
                                  								</select>
              													        </div>
              													    </div>
						                                <div class="clearfix"></div>
						                                <div class="col-md-6 col-sm-6 col-xs-12">
						                                    <label class="control-label">Lokasi</label>
						                                    <input value="{{( isset($m['lokasi']) ? $m['lokasi'] : '')}}" name="meta_lokasi[][lokasi]" type="text" class="form-control"> </div> --}}
						                                
						                            </div>
						                        </div>
                                @endforeach
                            @else
                                    <div data-repeater-item class="mt-repeater-item">
                                        <div class="row mt-repeater-row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label">Provinsi</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
                                                  <select class="form-control selectPropinsimeta" name="meta_lokasi[][propinsi]">
                                                    <option value="">-- Pilih Provinsi -- </option>
                                                    @foreach($propinsi as $p)
                                                    <option value="{{$p->id_wilayah}}" > {{$p->nm_wilayah}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-12 text-right p-r-0">
                                                <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            </div>
                                           {{-- <div class="clearfix"></div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label">Kota / Kabupaten</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12 p-l-0 p-r-0">
                                                  <select class="form-control selectKabupatenmeta" name="meta_lokasi[][kabupaten]">
                                                    <option value="">-- Pilih Kabupaten --</option>
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label">Lokasi</label>
                                                <input value="" name="meta_lokasi[][lokasi]" type="text" class="form-control"> </div> --}}
                                            
                                        </div>
                                    </div>
                              @endif
						                    </div>
						                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add " onClick="mSelect2(this)">
						                        <i class="fa fa-plus"></i> Tambah Lokasi</a>
						                </div>
						            </div>
						        </div>

		    					{{-- <div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="lokasi" value="@if(isset($data->lokasi)){{$data->lokasi}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>

		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kabupaten/Kota</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		{!! dropdownPropinsiKabupaten($data->lokasi_idkabkota,'lokasi_idkabkota') !!}
					              	</div>
					          	</div> --}}

		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Total Responden</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="jumlah_responden" value="@if(isset($data->jumlah_responden)){{number_format($data->jumlah_responden)}}@else{{''}}@endif" class="form-control" onKeydown="numeric_only(event,this)" onClick="reformatNumber(event,this)" onChange="reformatNumber(event,this)"/>
					              	</div>
					          	</div>

		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Riset</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
					             		<input type='text' name="hasil_riset" value="@if(isset($data->hasil_riset)){{$data->hasil_riset}}@else{{''}}@endif" class="form-control"/>
					              	</div>
					          	</div>


		    					<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">File Upload Hasil Reset</label>
					             	<div class='col-md-6 col-sm-6 col-xs-12 input-control'>
	                						<div class="fileinput fileinput-new" data-provides="fileinput">
							                    <div class="input-group input-large">
							                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
							                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
							                            <span class="fileinput-filename"> </span>
							                        </div>
							                        <span class="input-group-addon btn default btn-file">
							                            <span class="fileinput-new"> Pilih Berkas </span>
							                            <span class="fileinput-exists"> Ganti </span>
							                            <input type="file" name="file_upload"> </span>
							                        <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
							                    </div>
							                </div>
							                <span class="help-block" style="color:white">
							                    @if (isset($data->file_upload))
							                    	@if(file_exists($file_path.$data->file_upload))
							                        	Lihat File : <a  target="_blank"  style="color:yellow" href="{{url($file_path.$data->file_upload)}}">{{$data->file_upload}}</a>
							                    	@endif
							                    @endif
							                </span>
					              	</div>
					          	</div>



					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{url('puslitdatin/bidang_litbang/riset_penyalahgunaan_narkoba')}}" class="btn btn-primary" type="button">BATAL</a>
							            </div>
							        </div>
							    </div>

		                	</div>
	                	</form>
	                </div>
	            </div>
            </div>
        </div>
    </div>
@endsection
