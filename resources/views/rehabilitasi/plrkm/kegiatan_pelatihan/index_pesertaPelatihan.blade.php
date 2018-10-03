<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
      	<div class="x_content">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					<li role="presentation" class="active"><a href="#terperiksa" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Peserta</a>
					</li>
				</ul>
              	<div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tersangka" aria-labelledby="home-tab">
						<div class="tools pull-right" style="margin-bottom:15px;">
							<div class="btn-group btn-group-devided" data-toggle="buttons">
								<label class="btn btn-success" data-toggle="modal" data-target="#modal_add_peserta">
								<input type="radio" name="options" class="toggle" id="option1">Tambah Peserta</label>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
							<thead>
								<tr role="row" class="heading">
									<th width="5%"> No </th>
									<th width="30%"> Nama </th>
									<th width="20%"> NIP/NRP/KTP </th>
									<th width="15%"> Jenis Kelamin </th>
									<th width="15%"> Asal Instansi </th>
									<th width="15%"> Actions </th>
								</tr>
							</thead>
							<tbody>
								@php 
									$j =  1;
								@endphp
								@if(count($peserta))
									@foreach($peserta as $p)
										<tr>
											<td>{{$j}}</td>
											<td>{{$p->nama_peserta}}</td>
											<td>{{$p->nomor_identitas}}</td>
											<td> {{ ( ($p->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($p->kode_jeniskelamin == "L") ? 'Laki-Laki' : ''))}}</td>
											<td>{{$p->asal_instansilembaga}}</td>
											<td class="actionTable">
												<button type="button" class="btn btn-primary button-edit" data-target="{{$p->id_detail}}" onClick="open_modalEditPeserta(event,this,'/rehabilitasi/dir_plrkm/edit_peserta_pelatihan_plrkm/','modal_edit_form')"><i class="fa fa-pencil"></i></button>
                  								<button type="button" class="btn btn-primary button-delete" data-target="{{$p->id_detail}}" ><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									@php 
										$j = $j+1;
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan="6">
											<div class="alert-warning"> Data Peserta  Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia</div>
										</td>
									</tr>
								@endif
							</tbody>
						</table>
						<div class="pagination-wrap">
							<input type="hidden" name="current_page" value="1"/>
							<div class="pagination-wrap">
								{!! $pagination!!}
							</div>
						</div>
            		</div>
                </div>
          	</div>
        </div>
 	</div>
</div>
@include('modal.modal_deletePeserta')

@include('rehabilitasi.plrip.kegiatan_pelatihan.edit_pesertaPelatihan')
@include('rehabilitasi.plrip.kegiatan_pelatihan.add_pesertaPelatihan')

