@extends('layouts.base_layout')
@section('title', 'Tambah Data Pekerjaan Jaringan')

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
	                    <h2>Form Tambah Data Pekerjaan Jaringan</h2>
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

	           			<form action="{{URL('/puslitdatin/bidang_tik/save_pekerjaan_jaringan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
	           				{{ csrf_field()}}
		    				<div class="form-body">

					          	<div class="form-group">
									<label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
									<div class="col-md-9 col-sm-9 col-xs-12 radio">
										@if(count($jenis_kegiatan))
											@foreach($jenis_kegiatan as $jkey =>$j)
												<label class="mt-radio col-md-9 col-sm-9 col-xs-12">
													<input type="radio" value="{{$jkey}}" name="jenis_kegiatan" onclick="kegiatanjenis(this)" required>
													<span>{{$j}}</span>
												</label>
											@endforeach
										@endif
									</div>
								</div>

								<div class="form-group hide jenis_jaringan">
					                <label for="kode_jenis_jaringan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Jaringan</label>
					                <div class="col-md-8 col-sm-8 col-xs-12">
					                  <div class="checkbox">
					                  	@if(count($jenis_jaringan))
											@foreach($jenis_jaringan as $jar =>$jj)
												<label class="mt-checkbox col-md-3 col-sm-3 col-xs-12">
							                    	<input type="checkbox" value="{{$jar}}" name="kode_jenis_jaringan[]" >
							                    	<span>{{$jj}}</span>
							                    </label>
						                    @endforeach
										@endif
					                  </div>
					                </div>
					            </div>

					            <div class="form-group hide nota_dinas">
	              					<label for="nomor_nota_dinas" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Nota Dinas</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 '>
										<input type='text' name="nomor_nota_dinas" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaporan</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal '>
										<input type='text' name="tgl_pelaporan" value="" class="form-control" required/>
										<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
					          	</div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelapor</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12'>
										<input type='text' name="nama_pelapor" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
					                <label for="tempatkejadian_idprovinsi" class="col-md-3 col-sm-3 col-xs-12 control-label">Satuan Kerja</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                  <select class="form-control select2" id="tempatkejadian_idprovinsi" name="tempatkejadian_idprovinsi">
					                    <option value="">-- Pilih Satker --</option>
					                    @if(count($instansi))
					                      @foreach($instansi as $wil)
					                        <option value="{{$wil['id_instansi']}}" > {{$wil['nm_instansi']}}</option>
					                      @endforeach
					                    @endif
					                </select>
					                </div>
				                </div>

					          	<div class="form-group">
	              					<label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Penerima Laporan</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12'>
										<input type='text' name="penerima_laporan" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
			                    <label for="waktu_pengerjaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Waktu Pengerjaan</label>

			                    <div class='col-md-6 col-sm-6 col-xs-12'>
			                      <div class="row">
			                        <div class="col-md-6 col-sm-6 col-xs-12">
			                          <div class="row">
			                            <label for="waktu_pengerjaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
			                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal-publish'>
			                                <input type='text' name="tgl_mulai" class="form-control" value=""/>
			                                <span class="input-group-addon">
			                                <span class="glyphicon glyphicon-calendar"></span>
			                                </span>
			                            </div>
			                          </div>
			                        </div>
			                        <div class="col-md-6 col-sm-6 col-xs-12">
			                          <div class="row">
			                            <label for="waktu_pengerjaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
			                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal-publish'>
			                                <input type='text' name="tgl_selesai" class="form-control" value=""/>
			                                <span class="input-group-addon">
			                                <span class="glyphicon glyphicon-calendar"></span>
			                                </span>
			                            </div>
			                          </div>
			                        </div>
			                      </div>
			                    </div>
			                	</div>

			                	<div class="form-group">
	              					<label for="penanggung_jawab" class="col-md-3 col-sm-3 col-xs-12 control-label">Penanggung Jawab</label>
	              					<div class='col-md-6 col-sm-6 col-xs-12'>
										<input type='text' name="penanggung_jawab" value="" class="form-control" />
									</div>
					          	</div>

					          	<div class="form-group">
				                  <label for="meta_indikator" class="col-md-3 control-label">&nbsp;</label>
				                  <div class="col-md-6">
				                      <div class="mt-repeater">
				                          <div data-repeater-list="meta_teknisi">
				                              <div data-repeater-item class="mt-repeater-item">
				                                  <div class="row mt-repeater-row">
				                                      <div class="col-md-11">
				                                          <label class="control-label"> Teknisi</label>
				                                          <input name="meta_teknisi[][nama_teknisi]" type="text" class="form-control">
				                                      </div>
				                                      <div class="col-md-1">
				                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
				                                              <i class="fa fa-close"></i>
				                                          </a>
				                                      </div>
				                                  </div>
				                              </div>
				                          </div>
				                          <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
				                              <i class="fa fa-plus"></i> Tambah </a>
				                      </div>
				                  </div>
				                </div>

					          	<div class="form-group">
				                  <label for="meta_indikator" class="col-md-3 control-label">Permasalahan</label>
				                  <div class="col-md-6">
				                      <div class="mt-repeater">
				                          <div data-repeater-list="meta_permasalahan">
				                              <div data-repeater-item class="mt-repeater-item">
				                                  <div class="row mt-repeater-row">
				                                      <div class="col-md-11">
				                                          <label class="control-label"> Masalah</label>
				                                          <input name="meta_permasalahan[][masalah]" type="text" class="form-control"> </div>
				                                      <div class="col-md-11">
				                                          <label class="control-label">Tindak Lanjut</label>
				                                          <input name="meta_permasalahan[][tindak_lanjut]" type="text" class="form-control "> </div>
				                                      <div class="col-md-11">
				                                          <label class="control-label">Hasil</label>
				                                          <input name="meta_permasalahan[][hasil]" type="text" class="form-control "> </div>
				                                      <div class="col-md-1">
				                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
				                                              <i class="fa fa-close"></i>
				                                          </a>
				                                      </div>
				                                  </div>
				                              </div>
				                          </div>
				                          <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
				                              <i class="fa fa-plus"></i> Tambah </a>
				                      </div>
				                  </div>
				                </div>

				                <div class="clearfix">&nbsp;</div>

					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{url('puslitdatin/bidang_tik/pekerjaan_jaringan')}}" class="btn btn-primary" type="button">BATAL</a>
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
