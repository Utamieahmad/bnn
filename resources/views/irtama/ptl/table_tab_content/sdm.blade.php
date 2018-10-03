<div class="tab-wrap">
@if(isset($data))
	@if(isset($data->bidang))
		@php $b = $data->bidang; @endphp
		@if(count($b->sdm) > 0 )
			@php $j = 1; @endphp
			@for($i = 0 ; $i < count($b->sdm) ; $i++) 
				@php $k = $b->sdm[$i]; @endphp

				
					<form class="form-horizontal form-ptl text-left" >
						
						<input type="hidden" name="id" value="{{ $k->id_lha_bidang}}">
						<div class="form-body">
							<div class="form-group">
				                <label for="no_sprin" class="col-md-2 col-sm-2 col-xs-12 control-label">Judul Temuan</label>
				                <div class="col-md-6 col-sm-6 col-xs-12">
				                  <p class="form-p"> {{ $k->judul_temuan}}</p>
				                </div>
			              	</div>
						</div>
						<button data-toggle="collapse" data-target="#form_sdm_{{$i}}" class="btn btn-primary btn-open collapsed" type="button" ></button>
						<div class="collapse" id="form_sdm_{{$i}}">
							<div class="form-body">
								<div class="form-group">
					                <label for="no_sprin" class="col-md-2 col-sm-2 col-xs-12 control-label">Kriteria</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                  <p class="form-p"> {{ $k->kriteria}}</p>
					                </div>
				              	</div>
							</div>
							<div class="form-body">
								<div class="form-group">
					                <label for="no_sprin" class="col-md-2 col-sm-2 col-xs-12 control-label">Tangggapan</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                  <p class="form-p"> {{ $k->tanggapan}}</p>
					                </div>
				              	</div>
							</div>
							<div class="form-body">
								<div class="form-group">
					                <label for="no_sprin" class="col-md-2 col-sm-2 col-xs-12 control-label">Sebab</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                  <p class="form-p"> {{ $k->sebab}}</p>
					                </div>
				              	</div>
							</div>
							<div class="form-body">
								<div class="form-group">
					                <label for="no_sprin" class="col-md-2 col-sm-2 col-xs-12 control-label">Akibat</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                  <p class="form-p"> {{ $k->akibat}}</p>
					                </div>
				              	</div>
							</div>
							<div class="form-body">
								<div class="form-group">
					                <label for="no_sprin" class="col-md-2 col-sm-2 col-xs-12 control-label">Bukti</label>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                 	<p class="form-p"> 
											<span class="help-block white">
												@if (isset($k->bukti))
													@php $file_path = 'upload/IrtamaPtl/BidangKinerja/';@endphp
													@if (File::exists($file_path.$data->bukti))
													    Lihat File : <a  target="_blank" class="link_file" href="{{url($file_path.$data->bukti)}}">{{$data->bukti}}</a>
													@endif
												@endif
											</span>
										</p>
					                </div>
				              	</div>
							</div>
						</div>

					</form>
				
				<table class="datatable-responsive table table-bordered dt-responsive nowrap text-left" cellspacing="0" width="100%">
					<thead>
						<tr>
							<td> No </td>
							<td> Judul Rekomendasi </td>
							<td> Kode Rekomendasi </td>
							<td> Nilai Rekomendasi </td>
							<td> Penanggung Jawab </td>
							<td> Action </td>
						</tr>
					</thead>
					<tbody>
						@php $rekomendasi = $k->rekomendasi_bidang; $z = 1; @endphp
						@if(count($rekomendasi) > 0 )
							@for($a = 0; $a < count($rekomendasi);$a++)
								@php $r = $rekomendasi[$a]; @endphp
								<tr>
									<td> {{$z}}</td>
									<td> {{$r->judul_rekomendasi}}</td>
									<td> {{$r->kode_rekomendasi}}</td>
									<td> {{$r->nilai_rekomendasi}}</td>
									<td> {{$r->penanggung_jawab}}</td>
									<td> <button type="button" class="btn btn-primary button-action" data-id="{{$r->id_rekomendasi}}" onClick="editRekomendasi(event,this)">
											<i class="fa fa-pencil"></i></button> 
										<!-- <button type="button" class="btn btn-primary button-action" data-toggle="modal"  data-target="#modal_edit_ptl">
											<i class="fa fa-trash"></i></button> -->
									</td>

								</tr>
								@php $z = $z + 1 ; @endphp
							@endfor
						@else
							<tr>
								<td colspan="6">
									<div>
										Data Bidang Sumber Daya Manusia Belum Tersedia
									</div>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
				<hr class="border"/>
			@endfor
		@else
			<div class="alert-messages alert-warning">
				Data Bidang Sumber Daya Manusia Belum Tersedia
			</div>
		@endif
	@endif
@endif
<input type="hidden" name="tipe" value="sdm"/>
</div>