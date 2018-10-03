@extends('layouts.base_layout')
@section('title', 'Tambah Data Penegakan Disiplin')

@section('content')
	<div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
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
					<h2>Form Tambah Data Penegakan Disiplin Inspektorat Utama</h2>
					<div class="clearfix"></div>
				</div>
		<div class="x_content">
					<br />
			<form action="{{url('/irtama/penegakan/input_irtama_penegakan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
				{{ csrf_field() }}
				<input type="hidden" name="form_method" value="create">
        <div class="form-body">

	        <div class="form-group">
	            <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Bulan</label>
	            <div class='col-md-6 col-sm-6 col-xs-12 input-group date periodebulan'>
	                <input required type='text' name="periode" class="form-control" />
	                <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	                </span>
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="no_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Laporan</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	                <input value="" id="no_laporan" name="no_laporan" type="text" class="form-control">
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="tgl_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
	            <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
	                <input required type='text' name="tgl_laporan" class="form-control" />
	                <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	                </span>
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="kode_satker" class="col-md-3 col-sm-3 col-xs-12 control-label">Satker</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	               <select name="kode_satker" id="kode_satker" class="select2 form-control" tabindex="-1" aria-hidden="true" required  onChange="satker_code(this)">
                    <option value="">-- Pilih --</option>
                     @if(count($satker) > 0 )
                      @foreach($satker as $s => $sval)
                        <option value="{{$sval->nama}}" data-id="{{$sval->id}}">{{$sval->nama}}</option>
                      @endforeach
                    @endif
                  </select>
	            </div>
	            <input type="hidden" name="list_satker" class="list_satker"/>
	        </div>

	        <div style="border:1px solid" class="col-md-12 col-sm-12 col-xs-12 border-aero m-b-32 m-t-32 p-b-20">
                <h4 class="text-center m-32">DATA ABSENSI (KALI)</h4>

            	<div class="form-group">
	            <label for="absensi_tanpa_keterangan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanpa Keterangan</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	                <input value="" id="absensi_tanpa_keterangan" name="absensi_tanpa_keterangan" type="text" class="form-control numeric" onKeydown="numeric(event)">
	            </div>
	        </div>

            	<div class="form-group">
	            <label for="absensi_terlambat" class="col-md-3 col-sm-3 col-xs-12 control-label">Terlambat</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	                <input value="" id="absensi_terlambat" name="absensi_terlambat" type="text" class="form-control numeric" onKeydown="numeric(event)">
	            </div>
	        </div>

            	<div class="form-group">
	            <label for="absensi_pulang_cepat" class="col-md-3 col-sm-3 col-xs-12 control-label">Pulang Cepat</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	                <input value="" id="absensi_pulang_cepat" name="absensi_pulang_cepat" type="text" class="form-control numeric" onKeydown="numeric(event)">
	            </div>
	        </div>

            	<div class="form-group">
	            <label for="absensi_telambat_pulang_cepat" class="col-md-3 col-sm-3 col-xs-12 control-label">Terlambat / Pulang cepat</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	                <input value="" id="absensi_telambat_pulang_cepat" name="absensi_telambat_pulang_cepat" type="text" class="form-control numeric" onKeydown="numeric(event)">
	            </div>
	        </div>

            </div>

    </div>
    <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('irtama_penegakan')}}" class="btn btn-primary" type="button">BATAL</a>
            </div>
        </div>
    </div>
</form>
				</div>
			</div>
			</div>
	        </div>
     	</div>
	</div>
@endsection
