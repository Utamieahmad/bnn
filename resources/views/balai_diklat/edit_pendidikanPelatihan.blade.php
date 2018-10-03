@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat')

@section('content')
	<div class="right_col withAnggaran" role="main">
        <div class="m-t-40">
            <div class="page-title">
				<div class="">
					{!! $breadcrumps !!}
				</div>
            </div>
        	<div class="clearfix"></div>

        	<div class="title_right"> </div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Form Ubah Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat Balai Pendidikan dan Pelatihan</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							 @if (session('status'))
	                            @php
	                                $session= session('status');
	                            @endphp
	                            <div class="alert alert-{{$session['status']}}">
	                                {{ $session['message'] }}
	                            </div>
	                        @endif
							<form action="{{route('update_pendidikan_pelatihan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
		        				<input type="hidden" name="id" value="{{$data->id}}">
		        				<input type="hidden" name="id_pelaksana" class="id_pelaksana" value="{{isset($id_pelaksana) ? $id_pelaksana : ''}}"/>
		        				{{csrf_field()}}
		        				<div class="form-body">

						        <div class="form-group">
						            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Kegiatan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{isset($data->nama_kegiatan)? $data->nama_kegiatan : ""}}" id="nama_kegiatan" name="nama_kegiatan" type="text" class="form-control" required>
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Periode Pelaksanaan</label>

						            <div class='col-md-6 col-sm-6 col-xs-12'>
						            	<div class="row">
						            		<div class="col-md-6 col-sm-6 col-xs-12">
						            			<div class="row">
									            	<label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
										            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal'>
										                <input type='text' name="tgl_mulai" class="form-control" value="{{($data->tgl_pelaksanaan ? date('d/m/Y',strtotime($data->tgl_pelaksanaan)) : '')}}"  required/>
										                <span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										                </span>
										            </div>
									            </div>
								            </div>
								            <div class="col-md-6 col-sm-6 col-xs-12">
								            	<div class="row">
									            	<label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
										            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal'>
										                <input type='text' name="tgl_selesai" class="form-control" value="{{($data->tgl_selesai ? date('d/m/Y',strtotime($data->tgl_selesai)) : '')}}"  required/>
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
						            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <div class="radio" id='buttons'>
						                	@if(isset($jenis_diklat))
							                	@if(count($jenis_diklat))
							              			@foreach($jenis_diklat as $jkode=>$jvalue)
							              					<label class="col-md-9"> 
								              					<input type="radio" value="{{$jkode}}" name="jenis_diklat" {{( ($data->jenis_diklat == $jkode) ? 'checked=checked' : '' )}}>
											                    <span>{{$jvalue}}</span>
										                    </label>
							              			@endforeach
							              		@endif
						              		@endif
						                </div>
						            </div>
						        </div>
						        <div class="form-group">
						            <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tujuan Kegiatan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input id="nama_kegiatan" name="tujuan_kegiatan" type="text" class="form-control" value="{{$data->tujuan_kegiatan}}">
						            </div>
						        </div>
						        
						        <div class="form-group">
						            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						            	<select class="form-control select2" name="lokasi">
						            		<option value="">-- Pilih Kabupaten --</option>
	                                    	{!! dropdownLokasiKabupaten($data->tempat_idkabkota) !!}
	                                    </select>
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="tempat" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Tempat Pelaksanaan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{isset($data->tempat)? $data->tempat : ''}}" id="tempat" name="tempat" type="text" class="form-control">
						            </div>
						        </div>

								<div class="form-group">
						            <label for="total_hari_diklat" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Hari Diklat/Pelatihan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{isset($data->total_hari_diklat) ? $data->total_hari_diklat : ''}}" id="total_hari_diklat" name="total_hari_diklat" type="text" class="form-control numeric" onKeydown="numeric_only(event,this)">
						            </div>
						        </div>

								<div class="form-group">
						            <label for="total_jam_pelajaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Jam Pelajaran</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{isset($data->total_jam_pelajaran) ? $data->total_jam_pelajaran : ''}}" id="total_jam_pelajaran" name="total_jam_pelajaran" type="text" class="form-control numeric" onKeydown="numeric_only(event,this)">
						            </div>
						        </div>

									<div class="form-group">
							            <label for="total_narasumber_pengajar" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Narasumber/Tenaga Pengajar</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="{{isset($data->total_narasumber_pengajar) ? $data->total_narasumber_pengajar : ''}}" id="total_narasumber_pengajar" name="total_narasumber_pengajar" type="text" class="form-control numeric" onKeydown="numeric_only(event,this)">
							            </div>
							        </div>

									<div class="form-group">
							            <label for="total_peserta" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="{{isset($data->total_peserta) ? $data->total_peserta : ''}}" id="total_peserta" name="total_peserta" type="text" class="form-control numeric" onKeydown="numeric_only(event,this)">
							            </div>
							        </div>

									<div class="form-group">
							            <label for="syarat_mengikuti_diklat" class="col-md-3 col-sm-3 col-xs-12 control-label">Syarat Mengikuti Diklat</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							            	<textarea name="syarat_mengikuti_diklat" class="form-control">{{(isset($data->syarat_mengikuti_diklat) ? $data->syarat_mengikuti_diklat : '')}} </textarea>
							            </div>
							        </div>

									<div class="form-group">
							            <label for="kodesumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							            	<div class='col-md-7 col-sm-7 col-xs-12 input-group'>
				                                <div class="radio">
				                                    @if($sumber_anggaran)
				                                    	@php $i = 1; @endphp
				                                        @foreach($sumber_anggaran as $ikey=>$ivalue)
				                                            <label class="mt-radio col-md-9 kodeanggaran"> <input type="radio" value="{{$ikey}}" name="kodeanggaran" class="kodeanggaran" {{(($ikey == $data->kodeanggaran)? 'checked=checked' : '')}} id="anggaran{{$i}}">
				                                            <span>{{$ivalue}} </span>
				                                            </label>
				                                            @php $i = $i+1; @endphp
				                                        @endforeach
				                                    @endif
				                                </div>
				                            </div>
							            </div>
							        </div>
							        <div class="form-group" id="PilihAnggaran" {{((strtolower($data->kodeanggaran )== 'dipa') ? '' : 'style=display:none;') }}>
		                                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
		                                <div class="col-md-6 col-sm-6 col-xs-12">
		                                    <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran">
		                                        <option value="">-- Pilih Anggaran --</option>
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group" id="DetailAnggaran" {{((strtolower($data->kodeanggaran ) == 'dipa') ? '' : 'style=display:none;') }} >
		                                <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
		                                <input type="hidden" name="asatker_code" id="kodeSatker" value="">
		                                <input type="hidden" name="kode_anggaran" id="kode_anggaran" value="{{( isset($data_anggaran['kode_anggaran']) ? $data_anggaran['kode_anggaran'] : '')}}">
		                                <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
		                                  <table class="table table-striped nowrap">
			                                    @if($data->anggaran_id)
			                                        @if(count($data_anggaran) > 0)
			                                            @php $d = $data_anggaran; @endphp
			                                            <tr><td>Kode Anggaran</td><td>{{$d['kode_anggaran']}}</td></tr>
			                                            <tr><td>Sasaran</td><td>{{$d['sasaran']}}</td></tr>
			                                            <tr><td>Target Output</td><td>{{$d['target_output']}}</td></tr>
			                                            <tr><td>Satuan Output</td><td>{{$d['satuan_output']}}</td></tr>
			                                            <tr><td>Tahun</td><td>{{$d['tahun']}}</td></tr>
			                                        @endif
			                                    @endif
		                                  </table>
		                                </div>
		                            </div>
							        <div class="form-group">
	                                    <label for="file_nspk" class="col-md-3 control-label">Laporan Kegiatan</label>
	                                    <div class="col-md-6 col-sm-6 col-xs-12">
	                                        <div class="fileinput fileinput-new" data-provides="fileinput">
	                                            <div class="input-group input-large">
	                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
	                                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
	                                                    <span class="fileinput-filename"> </span>
	                                                </div>
	                                                <span class="input-group-addon btn default btn-file">
	                                                    <span class="fileinput-new"> Pilih Berkas </span>
	                                                    <span class="fileinput-exists"> Ganti </span>
	                                                    <input type="file" name="file_laporan"> </span>
	                                                <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
	                                            </div>
	                                        </div>
	                                        <span class="help-block white">
	                                        	@if ($data->file_laporan)
						                            @if(\Storage::exists('BalaiDiklat/'.$data->file_laporan))
						                                Lihat File : <a target="__blank" class="link_file" href="{{\Storage::url('BalaiDiklat/'.$data->file_laporan)}}"> {{$data->file_laporan}} </a>
						                            @endif
						                        @endif
	                                        </span>
	                                    </div>
	                                </div>
		    					</div>
							    <div class="form-actions fluid">
							        <div class="">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">KIRIM</button>
							                <a href="{{route('pendidikan_pelatihan')}}" class="btn btn-primary" type="button">BATAL</a>
							            </div>
							        </div>
							    </div>
							</form>
							
	                		@include('balai_diklat.index_pesertaPelatihan')
						</div>
					</div>
				</div>
	        </div>
     	</div>
	</div>
@endsection
