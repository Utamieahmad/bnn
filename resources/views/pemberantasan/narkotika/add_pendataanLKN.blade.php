@extends('layouts.base_layout')
@section('title', 'Tambah Pendataan LKN')

@section('content')
	<script>
		var IDSATKER = '{{Session::get("satker_simpeg")}}';
	</script>
	<div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
				{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
				</div>
            </div>
        	<div class="clearfix"></div>

        	<div class="title_right">

        </div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Form Tambah Pendataan LKN Direktorat Narkotika</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<br />
					<form  data-parsley-validate class="form-horizontal form-label-left" action="{{URL('/pemberantasan/dir_narkotika/input_pendataan_lkn')}}" enctype="multipart/form-data" method="post">
					{{ csrf_field() }}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal LKN</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal triger-tanggal'>
								<input type='text' class="form-control " name="kasus_tanggal" required/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2" id="pelaksana" name="pelaksana" required>
									<option value="">-- Pilih Instansi --</option>
								@foreach($instansi as $w)
									<option value="{{$w['id_instansi']}}">{{$w['nm_instansi']}}</option>
								@endforeach
								</select>
							</div>
						</div>

						<div class="form-group group-lkn">

							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN</label>
									<div class="col-md-2 col-sm-1 col-xs-1">
										<input type="text" id="noLKN0"  value="LKN"  class="form-control" name="noLKN[0]" readonly>
									</div>
									<div class="col-md-2 col-sm-1 col-xs-1">
										<input type="text" id="noLKN1" value="" class="form-control" name="noLKN[1]" required>
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
									<select class="form-control" id="noLKN2" name="noLKN[2]" required>
										<!--option value="P2">P2</option>
										<option value="INTD">INTD</option>
										<option value="TPPU">TPPU</option-->
										<option value="BRNTS">BRNTS</option>
										<option value="NAR">NAR</option>
									</select>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1">
									<input type="text" id="noLKN3" value="" class="form-control" name="noLKN[3]" >
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1">
									<input type="text" id="noLKN4" value="" class="form-control" name="noLKN[4]" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 last-child">
									<input type="text" id="noLKN5" value="" class="form-control" name="noLKN[5]" readonly>
								</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  ></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="pelaksanaGenerate" name="kasus_no" value="" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						@if (Session::get("satker_simpeg")=='')
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Satker Penyidik</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select class="form-control select2 selectSatker" id="satker" name="satker">
										<option value="">-- Pilih Satker Penyidik --</option>
									@foreach($satker as $s)
										<option value="{{$s['id']}}">{{$s['nama']}}</option>
									@endforeach
									</select>
								</div>
							</div>
						@else
							<input type="hidden" id="satker" name="satker" value="{{Session::get("satker_simpeg")}}">
						@endif

						<div class="form-group">
							<label class="col-md-3 control-label">Penyidik</label>
							<div class="col-md-6">
									<div class="mt-repeater">
											<div data-repeater-list="penyidik">
													<div data-repeater-item="" class="mt-repeater-item">
															<div class="row mt-repeater-row">
																<div class="col-md-10">
																	<label class="control-label">Nama Penyidik</label>
																		<select class="form-control selectPenyidik" name="penyidik[0][nama_penyidik]">
																			<option value="">-- Pilih Penyidik --</option>
																			@if ($penyidik['code'] == 200)
																				@foreach($penyidik['data']['pegawai'] as $w)
																					<option value="{{$w['nama']}}">{{$w['nama']}}</option>
																				@endforeach
																			@endif
																		</select>
																</div>
																<div class="col-md-1">
																		<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
																				<i class="fa fa-close"></i>
																		</a>
																</div>
															</div>
													</div>
											</div>
											<a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add" onclick="execSatker();">
											<i class="fa fa-plus"></i> Tambah Penyidik</a>
									</div>
							</div>
						</div>
						<div class="form-group tanggal">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Tanggal Kejadian</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
								<input type='text' name="tanggalKejadian" class="form-control" required/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >TKP Kasus</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="tkp" value="" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2 selectPropinsi" name="propinsi">
									<option value="">-- Pilih Provinsi -- </option>
									@foreach($propinsi as $p)
									<option value="{{$p->id_wilayah}}" > {{$p->nm_wilayah}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2 selectKabupaten" name="kabupaten">
									<option value="">-- Pilih Kabupaten --</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Modus Operandi</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="modus" value="" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Negara Sumber Narkotika</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2" name="negaraSumber">
									<option value="">-- Pilih Negara --</option>
									@foreach($negara as $n)
										<option value="{{$n->kode}}" > {{$n->nama_negara}}</p>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jalur Masuk</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2 noSearch" name="jalurMasuk">
									<option>Perlintasan Batas Darat</option>
									<option>Perlintasan Batas Laut</option>
									<option>Perlintasan Batas Udara</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Asal</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="ruteAsal" value="" class="form-control col-md-7 col-xs-12" >
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Transit</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="ruteTransit" value="" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Tujuan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="ruteTujuan" value="" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kasus</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2" name="jenisKasus">
									<option value="">-- Jenis Kasus -- </option>
									@foreach($jenisKasus['data'] as $keyGroup => $jenis )
										<option value="{{$jenis['id']}}">{{$jenis['nm_jnskasus']}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<!--div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Kasus</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control select2 noSearch" name="kelompokKasus" required>
									<option value="">-- Kelompok Kasus -- </option>
									<option value="TPPU">TPPU</option>
									<option value="NARKOTIKA">NARKOTIKA</option>
								</select>
							</div>
						</div-->
            <div class="form-group">
                <label for="file_upload" class="col-md-3 control-label">File Upload</label>
                <div class="col-md-6">
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
                            <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                        </div>
                    </div>
                    <span class="help-block">
                    </span>
                </div>
            </div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_lkn')}}" class="btn btn-primary" type="button">BATAL</a>
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
