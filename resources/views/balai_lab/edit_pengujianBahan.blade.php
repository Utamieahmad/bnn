@extends('layouts.base_layout')
@section('title', 'Ubah Data Berkas Sampel')

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
	                    <h2>Form Ubah Berkas Sampel</h2>
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
		           			<form action="{{URL('/balai_lab/pengujian/update_berkas_sampel')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
		           				{{ csrf_field()}}
		           				<input type="hidden" name="id" value="{{$data->id}}"/>
			    				<div class="form-body">
			    					<div class="form-group">
							            <label for="nomor_surat_permohonan_pengajuan" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Permohonan Pengujian</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
					             			<input type='text' name="nomor_surat_permohonan_pengajuan" value="@if(isset($data->nomor_surat_permohonan_pengajuan)){{$data->nomor_surat_permohonan_pengajuan}}@else{{''}}@endif" class="form-control"/>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="tgl_surat" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Surat Permohonan</label>
							            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
											<input type='text' name="tgl_surat" value="{{ (isset($data->tgl_surat) ? \Carbon\Carbon::parse($data->tgl_surat)->format('d/m/Y') : '')}}" class="form-control" />
							                <span class="input-group-addon">
							                <span class="glyphicon glyphicon-calendar"></span>
							                </span>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="perihal_surat" class="col-md-3 col-sm-3 col-xs-12 control-label">Perihal Surat Permohonan</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input id="perihal_surat" name="perihal_surat" type="text" class="form-control" value="@if(isset($data->perihal_surat)){{$data->perihal_surat}}@else{{''}}@endif">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="no_lplkn" class="col-md-3 col-sm-3 col-xs-12 control-label">No. LP/LKN</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input  id="no_lplkn" name="no_lplkn" type="text" class="form-control" value="@if(isset($data->no_lplkn)){{$data->no_lplkn}}@else{{''}}@endif">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="tgl_lplkn" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal LP/LKN</label>
							            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
											<input type='text' name="tgl_lplkn" value="{{ (isset($data->tgl_lplkn) ? \Carbon\Carbon::parse($data->tgl_lplkn)->format('d/m/Y') : '')}}" class="form-control" />
							                <span class="input-group-addon">
							                <span class="glyphicon glyphicon-calendar"></span>
							                </span>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="jenis_kasus" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kasus</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <div class="mt-radio-list radio" id='buttons'>
							                	@if($jenis_kasus)
							              			@foreach($jenis_kasus as $jkode=>$jvalue)
							              					<label class="mt-radio col-md-9"> 
								              					<input type="radio" value="{{$jkode}}" name="jenis_kasus" id="" {{(isset($data->jenis_kasus) ? (strtolower($data->jenis_kasus) == strtolower($jkode) ? 'checked=checked' : '') : '' )}}>
											                    <span>{{$jvalue}}</span>
										                    </label>
							              			@endforeach
							              		@endif
							                </div>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="nama_instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Asal Instansi</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="@if(isset($data->nama_instansi)){{$data->nama_instansi}}@else{{''}}@endif" id="nama_instansi" name="nama_instansi" type="text" class="form-control">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="nama_pengirim" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Pengirim</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="@if(isset($data->nama_pengirim)){{$data->nama_pengirim}}@else{{''}}@endif" id="nama_pengirim" name="nama_pengirim" type="text" class="form-control">
							            </div>
							        </div>

							        <div class="form-group">
								        <label for="pangkat_gol" class="control-label col-md-3">Pangkat/Golongan</label>
								        <div class="col-md-6 col-sm-6 col-xs-12">
							                <div class="mt-radio-list radio" id='buttons'>
							                	@if($golongan)
							              			@foreach($golongan as $gkode=>$gvalue)
						              					<label class="mt-radio col-md-9"> 
							              					<input type="radio" value="{{$gkode}}" name="pangkat_gol" id="" {{(isset($data->pangkat_gol) ? (strtolower($data->pangkat_gol) == strtolower($gkode) ? 'checked=checked' : '') : '' )}}>
										                    <span>{{$gvalue}}</span>
									                    </label>
							              			@endforeach
							              		@endif
							                </div>
							            </div>
								    </div>

							        <div class="form-group">
							            <label for="no_telp_pengirim" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Telpon Pengirim</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="@if(isset($data->no_telp_pengirim)){{$data->no_telp_pengirim}}@else{{''}}@endif" id="no_telp_pengirim" name="no_telp_pengirim" type="text" class="form-control" onKeydown="phone(event,this)">
							            </div>
							        </div>

							        <div class="form-group">
								        <label for="kode_jnsbrgbukti" class="control-label col-md-3">Jenis Barang Bukti</label>
							        	<div class="col-md-6 col-sm-6 col-xs-12">
									        @if($jenis_barang_bukti)
						              			<div class="radio">
							              			@foreach($jenis_barang_bukti as $bkode=>$bvalue)
						              					<label class="mt-radio col-md-9"> 
							              					<input type="radio" value="{{$bkode}}" name="kode_jnsbrgbukti" {{(isset($data->kode_jnsbrgbukti) ? ($data->kode_jnsbrgbukti == $bkode ? 'checked=checked' : '') : '' )}}>
										                    <span>{{$bvalue}}</span>
									                    </label>
							              			@endforeach
							              		</div>
						              		@endif
							    		</div>
								    </div>

							        <div class="form-group">
							            <label for="kuantitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Kuantitas</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="@if(isset($data->kuantitas)){{number_format($data->kuantitas)}}@else{{''}}@endif" id="kuantitas" name="kuantitas" type="text" class="form-control" onKeydown="numeric_only(event,this)" onChange="reformatNumber(event,this)" onClick="reformatNumber(event,this)">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="satuan" class="col-md-3 col-sm-3 col-xs-12 control-label">Satuan</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							            	<div class="col-md-6 col-sm-6 col-xs-12">
										        @if($satuan)
							              			<div class="radio">
								              			@foreach($satuan as $skode=>$svalue)
							              					<label class="mt-radio col-md-9"> 
								              					<input type="radio" value="{{$skode}}" name="satuan" {{(isset($data->satuan) ? (strtolower($data->satuan) == strtolower($skode) ? 'checked=checked' : '') : '' )}}>
											                    <span>{{$svalue}}</span>
										                    </label>
								              			@endforeach
								              		</div>
							              		@endif
								    		</div>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="logo" class="col-md-3 col-sm-3 col-xs-12 control-label">Logo</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="@if(isset($data->logo)){{$data->logo}}@else{{''}}@endif" id="logo" name="logo" type="text" class="form-control">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="laporan_hasil" class="col-md-3 col-sm-3 col-xs-12 control-label">Laporan Hasil</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
									        @if($laporan_hasil)
						              			<div class="radio">
							              			@foreach($laporan_hasil as $lkode=>$lvalue)
						              					<label class="mt-radio col-md-9"> 
							              					<input type="radio" value="{{$lkode}}" name="laporan_hasil" {{(isset($data->laporan_hasil) ? (strtolower($data->laporan_hasil) == strtolower($lkode) ? 'checked=checked' : '') : '' )}}>
										                    <span>{{$lvalue}}</span>
									                    </label>
							              			@endforeach
							              		</div>
						              		@endif
							    		</div>
							        </div>

							        <div class="form-group">
							            <label for="hasil_uji" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Uji Kemurnian Barang Bukti Sitaan</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="@if(isset($data->hasil_uji)){{$data->hasil_uji}}@else{{''}}@endif" id="hasil_uji" name="hasil_uji" type="text" class="form-control">
							            </div>
							        </div>
					          	
					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{url('balai_lab/pengujian/berkas_sampel')}}" class="btn btn-primary" type="button">BATAL</a>
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
