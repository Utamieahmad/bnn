<table>
	<tr>
		<td>Nomor LHA</td>
		<td>{{$data->nomor_lha}}</td>
	</tr>
	<tr>
		<td>Tanggal LHA</td>
		<td>{{$data->tanggal_lha}}</td>
	</tr>
	<tr>
		<td>Tanggal Mulai</td>
		<td>{{$data->tgl_mulai}}</td>
	</tr>
	<tr>
		<td>Tanggal Selesai</td>
		<td>{{$data->tgl_selesai}}</td>
	</tr>
</table>

@php
$bidang = $data->bidang;
@endphp
@foreach($bidang as $key=>$value)
	<table>
		<tr>
			<td colspan="4" style="background: #5586e0"><strong></strong>{{ucwords($key)}}</strong></td>
		</tr>
	</table>
	@if(count($value)>0)
		@foreach($value as $k => $d)
			
			<table>
				<tr>
					<td colspan="4" style="background: #e0e0e0"><strong></strong>Temuan</strong></td>
				</tr>
			</table>
			<table>
				<tr>
					<td>Judul Temuan</td>
					<td colspan="3">{{$d->judul_temuan}}</td>
				</tr>
				<tr>
					<td>Kriteria</td>
					<td colspan="3">{{$d->kriteria}}</td>
				</tr>
				<tr>
					<td>Sebab</td>
					<td colspan="3">{{$d->sebab}}</td>
				</tr>
				<tr>
					<td>Akibat</td>
					<td colspan="3">{{$d->akibat}}</td>
				</tr>
				<tr>
					<td>Tanggapan</td>
					<td colspan="3">{{$d->tanggapan}}</td>
				</tr>
			</table>
			<table>
				<tr>
					<td colspan="4" style="background: #e0e0e0"><strong></strong>Rekomendasi</strong></td>
				</tr>
			</table>
			
				<table>
					<thead>
						<tr>
							<td>Judul Rekomendasi</td>
							<td>Kode Rekomendasi</td>
							<td>Nilai Rekomendasi</td>
							<td>Penanggung Jawab</td>
							<td>Status </td>
							<td>Nilai Yang Ditindak Lanjut</td>
							<td>Bukti</td>
						</tr>
					</thead>
				@php
				$rekomendasi = $d->rekomendasi_bidang;
				@endphp
				<tbody>
					@if(count($rekomendasi) > 0 )
						@foreach($rekomendasi as $key => $r)
								<tr>
									<td>{{$r->judul_rekomendasi}}</td>
									<td>{{$r->kode_rekomendasi}}</td>
									<td>{{$r->nilai_rekomendasi}}</td>
									<td>{{$r->penanggung_jawab}}</td>
									<td>{{$r->status}}</td>
									<td>{{$r->nilai_tindak_lanjut}}</td>
								</tr>
						@endforeach
					@else
					<tr>
						<td colspan="6" align="center">
							Data Rekomendasi Tidak Tersedia
						</td>
					</tr>
					@endif
				</tbody>
			</table>
				
			
		@endforeach
	@endif
@endforeach