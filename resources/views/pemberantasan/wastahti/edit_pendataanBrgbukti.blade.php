@extends('layouts.base_layout')
@section('title', 'Ubah Pendataan Barang Bukti')

@section('content')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah2').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah3').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>


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
					<h2>Form Ubah Pendataan Pemusnahan barang bukti di BNN dan BNNP Direktorat Wastahti</h2>
					<!-- <?php
					// echo '<pre>';
					// print_r($data_kasus);
					// echo '</pre>';
					// echo '<pre>';
					// // print_r($wilayah);
					// echo '</pre>';
					?> -->
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
					<form action="{{url('/pemberantasan/dir_wastahti/update_pendataan_brgbukti')}}" enctype="multipart/form-data" method="post" class="form-horizontal form-label-left">
						{{ csrf_field() }}
						<input type="hidden" name="url" value="{{\Request::path()}}">
						<input type="hidden" name="id" value="{{$pemusnahan['id']}}">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{$no_lkn}}" name="no_lkn" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nama Penyidik</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{(isset($pemusnahan) ? $pemusnahan['nama_penyidik'] : "")}}" name="nama_penyidik" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label for="tgl_tap" class="col-md-3 control-label">Tanggal Penitipan</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
								<input type='text' value="{{(isset($pemusnahan) ? \Carbon\Carbon::parse($pemusnahan['tgl_penitipan'])->format('d/m/Y') : "")}}" name="tgl_penitipan" class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal Penitipan Pengambilan Untuk Press Releasse</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
								<input type='text' value="{{(isset($pemusnahan) ? \Carbon\Carbon::parse($pemusnahan['tgl_penitipan_ambil'])->format('d/m/Y') : "")}}" name="tgl_penitipan_ambil" class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>

						{{-- <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal Pemusnahan</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
								<input type='text' value="{{(isset($pemusnahan) ? \Carbon\Carbon::parse($pemusnahan['tgl_pemusnahan'])->format('d/m/Y') : "")}}" name="tgl_pemusnahan" class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div> --}}

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
										<span class="help-block" style="color:white">
												@if ($pemusnahan['file_upload'])
														@if (\Storage::exists('PemusnahanBB/'.$pemusnahan['file_upload']))
																Lihat File : <a  target="_blank" class="link_file" href="{{\Storage::url('PemusnahanBB/'.$pemusnahan['file_upload'])}}">{{$pemusnahan['file_upload']}}</a>
														@endif
												@endif

										</span>
								</div>
						</div>

						<div class="x_title">
							<h2>Ketetapan Penyitaan BB / Pemusnahan Kejari</h2>
							<div class="clearfix"></div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor Ketetapan</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{(isset($pemusnahan) ? $pemusnahan['nomor_tap'] : "")}}" name="nomor_tap" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label for="tgl_tap" class="col-md-3 control-label">Tanggal Ketetapan</label>
							<div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
								<input type='text' value="{{(isset($pemusnahan) ? \Carbon\Carbon::parse($pemusnahan['tgl_tap'])->format('d/m/Y') : "")}}" name="tgl_tap" class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" >Nama Kejari</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text"  value="{{(isset($pemusnahan) ? $pemusnahan['nama_kejari'] : "")}}" name="nama_kejari" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_brgbukti')}}" class="btn btn-primary" type="button">BATAL</a>
							</div>
						</div>
					</form>

						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">

								<div class="x_content">


									<div class="" role="tabpanel" data-example-id="togglable-tabs">
										<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
											<li role="presentation" class=""><a href="#barang_bukti_narkotika" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Barang Bukti Dimusnahkan</a>
											</li>
										</ul>
										<div id="myTabContent" class="tab-content">
											<div role="tabpanel" class="tab-pane fade active in" id="tersangka" aria-labelledby="home-tab">
												<div class="tools pull-right m-b-20" >
													<!-- <button class="btn btn-success tambahTersangka" data-toggle="modal" data-target="#modal_edit_brgbukti" data-url="{{URL('/pemberantasan/dir_wastahti/input_detail_pendataan_brgbukti')}}">Edit Barang Bukti</button> -->
													</div>
													<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
														<thead>
															<tr role="row" class="heading">
																<th width="5%"> No </th>
																<th width="40%"> Jenis Barang Bukti </th>
																<th width="15%"> Jumlah Awal </th>
																<th width="15%"> Keperluan Lab/Bukti </th>
																<th width="15%"> Keperluan Diklat </th>
																<th width="15%"> Keperluan Iptek </th>
																<th width="15%"> Jumlah Dimusnahkan </th>
																<th width="10%"> Actions </th>
															</tr>
														</thead>
														<tbody>
														@if(count($pemusnahanBrgBuktiDetail['data']))
															@php $i = 1; @endphp
															@foreach($pemusnahanBrgBuktiDetail['data'] as $brgBukti)
															<tr>
																<td>{{$i}}</td>
																<td>{{$brgBukti['nm_brgbukti']}}</td>
																<td>{{$brgBukti['jumlah_barang_bukti']}} <br/>( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
																<td>{{$brgBukti['keperluan_lab']}} <br/>( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
																<td>{{$brgBukti['keperluan_diklat']}} <br/>( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
																<td>{{$brgBukti['keperluan_iptek']}} <br/>( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
																<td>{{($brgBukti['jumlah_dimusnahkan'] == "") ? "0" : $brgBukti['jumlah_dimusnahkan']}} <br/> ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
																<td class="actionTable">
																	<a data-id="{{$brgBukti['id']}}" data-idBrgBukti="{{$brgBukti['id_brgbukti']}}" data-jumlahDimusnahkan="{{($brgBukti['jumlah_dimusnahkan'] == "") ? "0" : $brgBukti['jumlah_dimusnahkan']}}" data-keperluanIptek="{{$brgBukti['keperluan_iptek']}}" data-keperluanDiklat="{{$brgBukti['keperluan_diklat']}}" data-keperluanLab="{{$brgBukti['keperluan_lab']}}" data-nmBrgBukti="{{$brgBukti['nm_brgbukti']}}" data-jmlBrgBukti="{{$brgBukti['jumlah_barang_bukti']}}" data-kodeSatuan="{{$brgBukti['kode_satuan_barang_bukti']}}" data-tglPemusnahan="{{(isset($brgBukti) ? \Carbon\Carbon::parse($brgBukti['tgl_pemusnahan'])->format('d/m/Y') : "")}}" data-lokasi="{{$brgBukti['lokasi']}}" class="wastahtiModalBrgBukti" data-foto1="{{($brgBukti['foto1']) ? 'data:image/png;base64,'.$brgBukti['foto1'] : asset('assets/images/NoImage.gif')}}" data-foto2="{{($brgBukti['foto2']) ? 'data:image/png;base64,'.$brgBukti['foto2'] : asset('assets/images/NoImage.gif')}}" data-foto3="{{($brgBukti['foto3']) ? 'data:image/png;base64,'.$brgBukti['foto3'] : asset('assets/images/NoImage.gif')}}"><i class="fa fa-pencil f-18"></i></a>
																</td>
															</tr>
															@php $i = $i+1; @endphp
															@endforeach
															@else
																<tr>
																	<td colspan="8">
																		<div class="">
																			Data barang bukti belum tersedia.
																		</div>
																	</td>
																</tr>
															@endif
														</tbody>

													</table>
												</div>
											</div>
										</div>

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
</div>
@include('modal.modal_edit_brgbukti')
@endsection
