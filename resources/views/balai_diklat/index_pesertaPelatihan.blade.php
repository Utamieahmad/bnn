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
								<label class="btn btn-success " data-toggle="modal" data-target="#modal_add_peserta">
								<input type="radio" name="options" class="toggle" id="option1">Tambah Peserta</label>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
							<thead>
								<tr role="row" class="heading">
									<th width="5%"> No </th>
									<th width="30%"> Nama </th>
									<th width="20%"> NIP </th>
									<th width="15%"> Satker </th>
									<th width="15%"> Pangkat/Golongan </th>
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
											<td>{{$p->nama}}</td>
											<td>{{$p->nip}}</td>
											<td>{{$p->satker}}</td>
											<td>{{(isset($pangkat[$p->pangkat_golongan]) ? $pangkat[$p->pangkat_golongan] : $p->pangkat_golongan)}}</td>
											<td class="actionTable">
												<button type="button" class="btn btn-primary button-edit" data-target="{{$p->id}}" onClick="open_modalEditPesertaAlihProfesi(event,this,'/balai_diklat/pendidikan/edit_peserta_pelatihan/','modal_edit_form')"><i class="fa fa-pencil"></i></button>
                  								<button type="button" class="btn btn-primary button-delete" data-target="{{$p->id}}" ><i class="fa fa-trash"></i></button>
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
						<input type="hidden"name="current_page" value="{{$start_number}}"/> 
						<div class="pagination-wrap">
							{!! $pagination !!}
						</div>
            		</div>
                </div>
          	</div>
        </div>
 	</div>
</div>
@include('modal.modal_deletePeserta')
@include('balai_diklat.add_pesertaPelatihan')
@include('balai_diklat.edit_pesertaPelatihan')