@extends('layouts.omspan_layout')
@section('title', 'Indikator Pelaksanaan Anggaran')

@section('content')
	<div class="right_col" role="main">
		<div id="loader-wrapper">
			<div id="loader"></div>
		</div>
		<div class="m-t-40">
			<div class="page-title">
				<div class="">
					{!! (isset($breadcrumps) ? $breadcrumps : '') !!}
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<input type="hidden" id="periode" value="{{$periode}}">
					<input type="hidden" id="_token" value="{{csrf_token()}}">
					<div class="x_panel">
						<div class="div_rekap">
						<div class="x_title">
							<h2>Data Indikator Pelaksanaan Anggaran</h2>
						</div>
						<div class="x_content">
						<h6>Sampai Dengan {{$periode_name}}</h6>
							<table id="datatable-responsive" class="table table-bordered dt-omspan nowrap table-hover-cells" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Kode KPPN</th>
										<th>Kode Satker</th>
										<th>Uraian Satker</th>
										<th>Keterangan</th>										
										<th>Retur SP2D</th>
									</tr>
								</thead>
								<tbody>
									@php
									$i = 1;
									@endphp
									@foreach ($rekap as $row)
										<tr>
											<td rowspan="3" class="border_btm">{{$i++}}</td>
											<td rowspan="3" class="border_btm">{{trim($row['kd_kppn'])}}</td>
											<td rowspan="3" class="border_btm">{{trim($row['kd_satker'])}}</td>
											<td rowspan="3" class="border_btm">{{$row['instansi_name']}}</td>
											<td>Nilai</td>
											<td class="on_hover detail_retur" data-toggle="tooltip" title="Klik untuk lihat detail Retur SP2D" kd_instansi = "{{trim($row['kd_satker'])}}">{{round($row['retur'],2)}}</td>
										</tr>
										<tr>
											<td>Bobot(%)</td>
											<td>{{$row['bobot_retur']}}</td>
										</tr>
										<tr>
											<td class="border_btm td_bold">Nilai Akhir</td>
											<td class="border_btm td_bold">{{$row['na_retur']}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						</div>
						
						<div class="div_retur">
							<div class="x_title">
								<h2>Detail Indikator Retur Retur</h2>
							</div>
							<div class="x_content">
								<button type="button" class="btn btn-success button-sm btn_kembali">Kembali</button>
								<table class="table table-bordered dt-retur nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Kode Satker</th>
											<th>Nama Satker</th>
											<th>KPPN</th>
											<th>Periode</th>
											<th>Akumuliasi Jumlah Retur</th>
											<th>Akumulasi Jumlah SP2D</th>
											<th>Persen</th>
											<th>Nilai</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection