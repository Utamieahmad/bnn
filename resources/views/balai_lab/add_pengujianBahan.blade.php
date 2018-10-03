@extends('layouts.base_layout')
@section('title', 'Tambah Data Berkas Sampel')

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
	                    <h2>Form Tambah Berkas Sampel</h2>
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
		           			<form action="{{route('save_berkas_sample')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
		           				{{ csrf_field()}}
			    				<div class="form-body">
			    					<div class="form-group">
							            <label for="nomor_surat_permohonan_pengajuan" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Permohonan Pengujian</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
					             			<input type='text' name="nomor_surat_permohonan_pengajuan" value="" class="form-control"/>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="tgl_surat" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Surat Permohonan</label>
							            <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
											<input type='text' name="tgl_surat" value="" class="form-control" />
							                <span class="input-group-addon">
							                <span class="glyphicon glyphicon-calendar"></span>
							                </span>
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="perihal_surat" class="col-md-3 col-sm-3 col-xs-12 control-label">Perihal Surat Permohonan</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input id="perihal_surat" name="perihal_surat" type="text" class="form-control" value="">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="no_lplkn" class="col-md-3 col-sm-3 col-xs-12 control-label">No. LP/LKN</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input  id="no_lplkn" name="no_lplkn" type="text" class="form-control" value="">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="tgl_lplkn" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal LP/LKN</label>
							            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
											<input type='text' name="tgl_lplkn" value="" class="form-control" />
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
							              					<input type="radio" value="{{$jkode}}" name="jenis_kasus" id="">
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
							                <input value="" id="nama_instansi" name="nama_instansi" type="text" class="form-control">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="nama_pengirim" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Pengirim</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="" id="nama_pengirim" name="nama_pengirim" type="text" class="form-control">
							            </div>
							        </div>

							        <div class="form-group">
								        <label for="pangkat_gol" class="control-label col-md-3">Pangkat/Golongan</label>
								        <div class="col-md-6 col-sm-6 col-xs-12">
							                <div class="mt-radio-list radio" id='buttons'>
							                	@if($golongan)
							              			@foreach($golongan as $gkode=>$gvalue)
						              					<label class="mt-radio col-md-9"> 
							              					<input type="radio" value="{{$gkode}}" name="pangkat_gol">
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
							                <input value="" id="no_telp_pengirim" name="no_telp_pengirim" type="text" class="form-control" onKeydown="phone(event,this)">
							            </div>
							        </div>

							        <div class="form-group">
								        <label for="kode_jnsbrgbukti" class="control-label col-md-3">Jenis Barang Bukti</label>
							        	<div class="col-md-6 col-sm-6 col-xs-12">
									        @if($jenis_barang_bukti)
						              			<div class="radio">
							              			@foreach($jenis_barang_bukti as $bkode=>$bvalue)
						              					<label class="mt-radio col-md-9"> 
							              					<input type="radio" value="{{$bkode}}" name="kode_jnsbrgbukti">
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
							                <input value="" id="kuantitas" name="kuantitas" type="text" class="form-control" onKeydown="numeric_only(event,this)" onChange="reformatNumber(event,this)" onClick="reformatNumber(event,this)">
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
								              					<input type="radio" value="{{$skode}}" name="satuan">
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
							                <input value="" id="logo" name="logo" type="text" class="form-control">
							            </div>
							        </div>

							        <div class="form-group">
							            <label for="laporan_hasil" class="col-md-3 col-sm-3 col-xs-12 control-label">Laporan Hasil</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
									        @if($laporan_hasil)
						              			<div class="radio">
							              			@foreach($laporan_hasil as $lkode=>$lvalue)
						              					<label class="mt-radio col-md-9"> 
							              					<input type="radio" value="{{$lkode}}" name="laporan_hasil">
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
							                <input value="" id="hasil_uji" name="hasil_uji" type="text" class="form-control">
							            </div>
							        </div>
					          	
					          	<div class="form-actions fluid">
							        <div class="row">
							            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							                <button type="submit" class="btn btn-success">SIMPAN</button>
							                <a href="{{route('berkas_sampel')}}" class="btn btn-primary" type="button">BATAL</a>
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
