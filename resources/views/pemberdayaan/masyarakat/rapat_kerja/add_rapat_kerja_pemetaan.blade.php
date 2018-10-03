@extends('layouts.base_layout')
@section('title', 'Tambah Data Rapat Kerja Pemetaan')

@section('content')
    <div class="right_col withAnggaran" role="main">
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
	                    <h2>Form Tambah Rapat Kerja Pemetaan</h2>
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
		           			<form action="{{route('save_rapat_kerja_pemetaan')}}" class="form-horizontal" id="frm_add" method="post" autocomplete="on">
		           				{{ csrf_field()}}
			    				<div class="form-body">
			    					<div class="form-group">
							            <label for="nomor_surat_permohonan_pengajuan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pemetaan</label>
							            <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only	'>
											<input type="text" name="tanggal_pemetaan" value="" class="form-control" required/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
							        </div>

							        <div class="form-group">
		                                <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
		                                <div class="col-md-6 col-xs-12 col-sm-6">
		                                    <select name="id_pelaksana" id="id_instansi" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required>
		                                        <option value="" > Pilih Pelaksana </option>
		                                        {!! dropdownPelaksana("",true) !!}
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group">
		                                <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
		                                <div class="col-md-6 col-sm-6 col-xs-12">
		                                	<select name="id_lokasi_kegiatan" id="id_instansi" class="form-control select2" tabindex="-1" aria-hidden="true">
		                                        <option value=""> Pilih Lokasi </option>
		                                        {!! dropdownLokasiKabupaten() !!}
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group">
		                                <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
		                                <div class="col-md-6 col-sm-6 col-xs-12">
		                                    <input value="" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control" onKeydown="numeric_only(event,this)">
		                                </div>
		                            </div>

							        <div class="form-group">
		                                <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
		                                <div class="col-md-6 col-xs-12 col-sm-6">
		                                    <div class="radio">
		                                        @if(count($sumber_anggaran) > 0)
		                                        	@php $i = 1; @endphp
		                                            @foreach($sumber_anggaran as $skey=>$svalue)
		                                                <label class="mt-radio col-md-9">
		                                                	<input type="radio" value="{{$skey}}" name="kode_sumber_anggaran" id="anggaran{{$i}}" />
		                                                	<span>{{$svalue}} </span>
		                                                </label>
	                                                @php $i = $i+1; @endphp
		                                            @endforeach
		                                        @endif
		                                    </div>
		                                </div>
		                            </div>


		                            <div class="form-group" id="PilihAnggaran" style="display:none">
		                                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
		                                <div class="col-md-6 col-sm-6 col-xs-12">
		                                    <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
		    									<option value="">-- Pilih Anggaran --</option>
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group" id="DetailAnggaran" style="display:none">
		                                <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
		                                <input type="hidden" name="asatker_code" id="kodeSatker" value="">
		                                <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">

		                                </div>
		                            </div>


							        <div class="form-group">
		                                <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran</label>
		                                <div class="col-md-6 col-xs-12 col-sm-6">
		                                    <div class="radio">

		                                        @if(count($sasaran) > 0)
		                                            @foreach($sasaran as $pkey=>$pvalue)
		                                                <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="kode_sasaran" />
		                                                <span>{{$pvalue}} </span>
		                                                </label>
		                                            @endforeach
		                                        @endif
		                                    </div>
		                                </div>
		                            </div>
						        </div>
					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{route('rapat_kerja_pemetaan')}}" class="btn btn-primary" type="button">BATAL</a>
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
