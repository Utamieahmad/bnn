<ul class="nav navbar-right panel_toolbox">
	<li class="">
		<a href="#" class="btn btn-lg btn-round btn-primary" data-toggle="modal" data-target="#modal_peserta">
			<i class="fa fa-plus-circle c-yelow"></i> Tambah Data
		</a>
	</li>
</ul>
<div class="clearfix"></div>

	<table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left2" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th width="20%">No Identitas</th>
				<th>Asal Profesi</th>
				<th>Tanggal Lahir </th>
				<th>Actions</th>
			</tr>
		</thead>
	@if(count($peserta))
	<tbody>
		@php $i = $start_number; @endphp
		@foreach($peserta as $p)
			<tr>
				<td> {{$i}}</td>
				<td> {{$p->nama}}</td>
				<td> {{$p->no_identitas}}</td>
				<td> {{$p->kode_profesi}}</td>
				<td> {{($p->tgl_lahir ? date('d/m/Y',strtotime($p->tgl_lahir)) : "")}}</td>
				<td>
                  	<button type="button" class="btn btn-primary button-edit" data-target="{{$p->id}}" onClick="open_modalEditPesertaAlihProfesi(event,this,'/pemberdayaan/dir_alternative/edit_peserta_alih_profesi/','modal_edit_form')"><i class="fa fa-pencil"></i></button>
                  	<button type="button" class="btn btn-primary button-delete" data-target="{{$p->id}}" ><i class="fa fa-trash"></i></button>

                </td>
			</tr>
		@php $i = $i+1; @endphp
		@endforeach
	</tbody>
	@else
	<tbody>
		<tr> <td colspan="6">
			<div class="alert-warning">
				Data Peserta Alih Jenis Profesi/Usaha  Belum Tersedia.
			</div></td>
		</tr>
	</tbody>
	@endif
	</table>
	<input type="hidden"name="current_page" value="{{$start_number}}"/> 
	<div class="pagination-wrap">
		{!! isset($pagination) ? $pagination : '' !!}
	</div>
	<ul id="pagination-footer" class="pagination-sm"></ul>

@include('modal.modal_deletePeserta')

@include('pemberdayaan.alternative_development.peserta_alihProfesi.add_pesertaAlihProfesi')
@include('pemberdayaan.alternative_development.peserta_alihProfesi.edit_pesertaAlihProfesi')
