@extends('layouts.base_layout')
@section('title', 'Ubah Data Apel Senin &amp; Upacara')

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
					<h2>Form Ubah Data Apel Senin &amp; Upacara Inspektorat Utama</h2>
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
						<form action="{{url('/irtama/apel/update_irtama_apel')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="{{$data_detail['id']}}">
								<input type="hidden" name="form_method" value="create">
								<div class="form-body">

					        <div class="form-group">
					            <label for="tanggal" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
					            <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
					                <input type='text' name="tanggal" class="form-control" value="{{( $data_detail['tanggal']) ? \Carbon\Carbon::parse($data_detail['tanggal'] )->format('d/m/Y') : '' }}" required/>
					                <span class="input-group-addon">
					                <span class="glyphicon glyphicon-calendar"></span>
					                </span>
					            </div>
					        </div>

					        <div class="form-group">
					            <label for="jenis_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
					            <div class="col-md-6 col-sm-6 col-xs-12">
					                <input value="{{$data_detail['jenis_kegiatan']}}" id="jenis_kegiatan" name="jenis_kegiatan" type="text" class="form-control">
					            </div>
					        </div>

					        <div class="form-group">
					            <label for="kode_satker" class="col-md-3 col-sm-3 col-xs-12 control-label">Satker</label>
					            <div class="col-md-6 col-sm-6 col-xs-12">
					               	<?php
					                    $data_satker = $data_detail['kode_satker'];
					                    $id_satker = "";
					                    if($data_satker){
					                      $j = json_decode($data_satker,true);
					                      $id_satker = $j['id'];
					                    }else{
					                      $id_satker = "";
					                    }

				                  	?>
					               <select name="kode_satker" id="kode_satker" class="select2 form-control" tabindex="-1" aria-hidden="true" required  onChange="satker_code(this)"> 
				                   <option value="">-- Pilih Satker--</option>
			                        @if(count($satker) > 0 )
				                     	@foreach($satker as $s => $sval)
				                       		<option value="{{$sval->nama}}" data-id="{{$sval->id}}" {{( ($sval->id==$id_satker) ? 'selected=selected' : '' )}}>{{$sval->nama}}</option>
				                     	@endforeach
				                    @endif
				                  </select>
					            </div>
	            					<input type="hidden" name="list_satker" class="list_satker"/>
					        </div>

						      <div class="form-group">
						            <label for="jumlah_hadir" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Hadir</label>
							            <div class="col-md-6 col-sm-6 col-xs-12">
							                <input value="{{$data_detail['jumlah_hadir']}}" id="jumlah_hadir" name="jumlah_hadir" type="text" class="form-control numeric" onKeydown="numeric(event)">
							            </div>
							    </div>

						      <div class="form-group">
						            <label for="jumlah_tidak_hadir" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Tidak Hadir</label>
							        <div class="col-md-6 col-sm-6 col-xs-12">
							            <input value="{{$data_detail['jumlah_tidak_hadir']}}" id="jumlah_tidak_hadir" name="jumlah_tidak_hadir" type="text" class="form-control numeric" onKeydown="numeric(event)">
							        </div>
							    </div>

					        <div style="border:1px solid" class="col-md-12 col-sm-12 col-xs-12 border-aero m-b-32 m-t-32 p-b-20">
				                <h4 class="text-center m-32">KETERANGAN</h4>

				            <div class="form-group">
					            <label for="keterangan_dinas" class="col-md-3 col-sm-3 col-xs-12 control-label">Dinas</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_dinas']}}" id="keterangan_dinas" name="keterangan_dinas" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

				            <div class="form-group">
						            <label for="keterangan_izin" class="col-md-3 col-sm-3 col-xs-12 control-label">Izin</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_izin']}}" id="keterangan_izin" name="keterangan_izin" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

				            <div class="form-group">
						            <label for="keterangan_sakit" class="col-md-3 col-sm-3 col-xs-12 control-label">Sakit</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_sakit']}}" id="keterangan_sakit" name="keterangan_sakit" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

				            <div class="form-group">
						            <label for="keterangan_cuti" class="col-md-3 col-sm-3 col-xs-12 control-label">Cuti</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_cuti']}}" id="keterangan_cuti" name="keterangan_cuti" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="keterangan_pendidikan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pendidikan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_pendidikan']}}" id="keterangan_pendidikan" name="keterangan_pendidikan" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="keterangan_hamil" class="col-md-3 col-sm-3 col-xs-12 control-label">Hamil</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_hamil']}}" id="keterangan_hamil" name="keterangan_hamil" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="keterangan_terlambat" class="col-md-3 col-sm-3 col-xs-12 control-label">Terlambat</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_terlambat']}}" id="keterangan_terlambat" name="keterangan_terlambat" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="keterangan_tugas_kantor" class="col-md-3 col-sm-3 col-xs-12 control-label">Tugas Kantor</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_tugas_kantor']}}" id="keterangan_tugas_kantor" name="keterangan_tugas_kantor" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

						        <div class="form-group">
						            <label for="keterangan_tanpa_keterangan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanpa Keterangan</label>
						            <div class="col-md-6 col-sm-6 col-xs-12">
						                <input value="{{$data_detail['keterangan_tanpa_keterangan']}}" id="keterangan_tanpa_keterangan" name="keterangan_tanpa_keterangan" type="text" class="form-control numeric" onKeydown="numeric(event)">
						            </div>
						        </div>

				          </div>

				    	</div>
				    <div class="form-actions fluid">
				        <div class="row">
				            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				                <button type="submit" class="btn btn-success">KIRIM</button>
												<a href="{{route('irtama_apel')}}" class="btn btn-primary" type="button">BATAL</a>
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
