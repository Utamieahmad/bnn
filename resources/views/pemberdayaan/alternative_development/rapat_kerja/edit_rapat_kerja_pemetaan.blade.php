@extends('layouts.base_layout')
@section('title', 'Ubah Data Rapat Kerja Pemetaan')

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
	                    <h2>Form Ubah Rapat Kerja Pemetaan</h2>
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
		           			<form action="{{route('update_alv_rapat_kerja_pemetaan')}}" class="form-horizontal" id="frm_add" method="post" autocomplete="on">

		           				{{ csrf_field()}}
		           				<input type="hidden" name="id" value="{{$data->id}}"/>
			    				<div class="form-body">
			    					<div class="form-group">
							            <label for="nomor_surat_permohonan_pengajuan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pemetaan</label>
							            <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only	'>
											<input type="text" name="tanggal_pemetaan" value="{{ (isset($data->tanggal_pemetaan) ? \Carbon\Carbon::parse($data->tanggal_pemetaan)->format('d/m/Y') : '') }}" class="form-control" required/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
							        </div>

							        <div class="form-group">
		                                <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                        <input type="hidden" name="id_pelaksana" class="id_pelaksana" value="{{$data->id_pelaksana}}"/>
		                                <div class="col-md-6 col-xs-12 col-sm-6">
		                                    <select name="id_pelaksana" id="id_instansi" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required> 
		                                        <option value="" {{(isset($data->id_pelaksana) ? "" : 'selected=selected')}}> Pilih Pelaksana </option>
		                                        {!! dropdownPelaksana($data->id_pelaksana,true) !!}
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group">
		                                <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
		                                <div class="col-md-6 col-sm-6 col-xs-12">
		                                	<select name="id_lokasi_kegiatan" id="id_instansi" class="form-control select2 " tabindex="-1" aria-hidden="true">
		                                        <option value="" {{(isset($data->id_lokasi_kegiatan) ? "" : 'selected=selected')}}> Pilih Lokasi </option>
		                                        {!! dropdownLokasiKabupaten($data->id_lokasi_kegiatan,true) !!}
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group">
		                                <label for="nama_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Peserta</label>
		                                <div class="col-md-6 col-sm-6 col-xs-12">
		                                    <input value="{{$data->jumlah_peserta}}" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control" onKeydown="numeric_only(event,this)">
		                                </div>
		                            </div>

						        </div>

						        <div class="form-group">
	                                <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
	                                <div class="col-md-6 col-xs-12 col-sm-6">
	                                    <div class="radio">
	                                        @if(count($sumber_anggaran) > 0)
	                                        	@php $i = 1;@endphp
	                                            @foreach($sumber_anggaran as $skey=>$svalue)
	                                                <label class="mt-radio col-md-9"> 
	                                                	<input type="radio" value="{{$skey}}" name="kode_sumber_anggaran"  {{( ($skey == $data->kode_sumber_anggaran) ? 'checked=checked' : '')}} id="anggaran{{$i}}"/>
	                                                	<span>{{$svalue}} </span>
	                                                </label>
		                                        @php $i = $i + 1;@endphp        
	                                            @endforeach
	                                        @endif
	                                    </div>
	                                </div>
	                            </div>
	                             <div class="form-group" id="PilihAnggaran" {{(($data->kode_sumber_anggaran == 'dipa') ? '' : 'style=display:none;') }}>
	                                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
	                                <div class="col-md-6 col-sm-6 col-xs-12">
	                                    <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran">
	                                        <option value="">-- Pilih Anggaran --</option>
	                                    </select>
	                                </div>
	                            </div>

	                            <div class="form-group" id="DetailAnggaran" {{(($data->kode_sumber_anggaran == 'dipa') ? '' : 'style=display:none;') }} >
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
	                                <label for="kodepenyelenggara" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran</label>
	                                <div class="col-md-6 col-xs-12 col-sm-6">

	                                    <div class="radio">
	                                        @if(count($sasaran) > 0)
	                                            @foreach($sasaran as $pkey=>$pvalue)
	                                                @if($pkey!=3)
                                                    <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="kode_sasaran" {{( ($pkey == $data->kode_sasaran) ? 'checked=checked' : '')}}/>
  	                                                <span>{{$pvalue}} </span>
  	                                                </label>
                                                  @endif
	                                            @endforeach
	                                        @endif
	                                    </div>
	                                </div>
	                            </div>

					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{route('alv_rapat_kerja_pemetaan')}}" class="btn btn-primary" type="button">BATAL</a>
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
