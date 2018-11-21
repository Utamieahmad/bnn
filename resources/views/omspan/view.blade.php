@extends('layouts.omspan_layout')
@section('title', ' Omspan')

@section('content')
	<div class="right_col" role="main">
		<div class="m-t-40">
			<!--div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
				</div>
			</div-->

			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<input type="hidden" id="periode" value="{{$periode}}">
					<input type="hidden" id="_token" value="{{csrf_token()}}">
					<div class="x_panel">
						<div class="div_rekap">
						<div class="x_title">
							<h2>Indikator Pelaksanaan Anggaran</h2>
						</div>
						<div class="x_content">
							<table id="datatable-responsive" class="table table-bordered dt-omspan nowrap table-hover-cells" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Kode Dept</th>
										<th>Kode Satker</th>
										<th>Uraian Satker</th>
										<th>Keterangan</th>
										<th>Pengelolaan UP</th>
										<th>Data Kontrak</th>
										<th>Kesalahan SPM</th>
										<th>Retur SP2D</th>
										<th>Hal III DIPA</th>
										<th>Revisi DIPA</th>
										<th>Penyelesaian Tagihan</th>
										<th>Rekon LPJ</th>
										<th>Renkas</th>
										<th>Realisasi</th>
										<th>Pagu Minus</th>
										<th>Dispensasi SPM</th>
										<th>Nilai Total</th>
										<th>Konversi Bobot</th>
										<th>Nilai Akhir (Nilai Total/Konversi Bobot)</th>
									</tr>
								</thead>
								<tbody>
									@php
									$i = 1;
									@endphp
									@foreach ($rekap as $row)
										<tr>
											<td rowspan="3">{{$i++}}</td>
											<td rowspan="3"></td>
											<td rowspan="3">{{trim($row['kdInstansi'])}}</td>
											<td rowspan="3">{{$row['instansiName']}}</td>
											<td class="no_border">Nilai</td>
											<td class="no_border detail_up" kd_instansi = "{{trim($row['kdInstansi'])}}">{{round($row['up'],2)}}</td>
											<td class="no_border detail_dkon" kd_instansi = "{{trim($row['kdInstansi'])}}">{{round($row['dkon'],2)}}</td>
											<td class="no_border"></td>
											<td class="no_border">{{round($row['retur'],2)}}</td>
											<td class="no_border"></td>
											<td class="no_border">{{round($row['revisi'],2)}}</td>
											<td class="no_border">{{round($row['ptagih'],2)}}</td>
											<td class="no_border">{{round($row['rekon'],2)}}</td>
											<td class="no_border">{{round($row['renkas'],2)}}</td>
											<td class="no_border"></td>
											<td class="no_border">0</td>
											<td class="no_border">0</td>
											<td rowspan="3">{{$row['na_up'] + $row['na_dkon'] + $row['na_retur'] + $row['na_revisi'] + $row['na_ptagih'] + $row['na_rekon'] + $row['na_renkas']}}</td>
											<td rowspan="3">{{$row['bobot_up'] + $row['bobot_dkon'] + $row['bobot_retur'] + $row['bobot_revisi'] + $row['bobot_ptagih'] + $row['bobot_rekon'] + $row['bobot_renkas']}}</td>
											<td rowspan="3"></td>
										</tr>
										<tr>
											<td>Bobot(%)</td>
											<td>{{$row['bobot_up']}}</td>
											<td>{{$row['bobot_dkon']}}</td>
											<td></td>
											<td>{{$row['bobot_retur']}}</td>
											<td></td>
											<td>{{$row['bobot_revisi']}}</td>
											<td>{{$row['bobot_ptagih']}}</td>
											<td>{{$row['bobot_rekon']}}</td>
											<td>{{$row['bobot_renkas']}}</td>
											<td></td>
											<td>0</td>
											<td>0</td>
										</tr>
										<tr>
											<td>Nilai Akhir</td>
											<td>{{$row['na_up']}}</td>
											<td>{{$row['na_dkon']}}</td>
											<td></td>
											<td>{{$row['na_retur']}}</td>
											<td></td>
											<td>{{$row['na_revisi']}}</td>
											<td>{{$row['na_ptagih']}}</td>
											<td>{{$row['na_rekon']}}</td>
											<td>{{$row['na_renkas']}}</td>
											<td></td>
											<td>0</td>
											<td>0</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						</div>
						
						<div class="div_up">
							<div class="x_title">
								<h2>Detail Indikator Kinerja UP/TUP Satker</h2>
							</div>
							<div class="x_content">
								<button type="button" class="btn btn-success button-sm btn_kembali">Kembali</button>
								<table class="table table-bordered dt-up nowrap" cellspacing="0" width="100%" style="color: black !important;">
									<thead>
										<tr>
											<th>No</th>
											<th>Satker</th>
											<th>Nama Satker</th>
											<th>Sumber Dana</th>
											<th>Jenis</th>
											<th>Tanggal</th>
											<th>Selisih Hari</th>
											<th>Jumlah</th>
											<th>Outstanding UP</th>
											<th>Total GU</th>
											<th>Persen(%)</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="11">Jumlah Tepat</td>
											<td class="jml_tepat"></td>
										</tr>
										<tr>
											<td colspan="11">Jumlah Terlambat</td>
											<td class="jml_terlambat"></td>
										</tr>
										<tr>
											<td colspan="11">Total</td>
											<td class="total"></td>
										</tr>
										<tr>
											<td colspan="11">Persen</td>
											<td class="total_persen"></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
