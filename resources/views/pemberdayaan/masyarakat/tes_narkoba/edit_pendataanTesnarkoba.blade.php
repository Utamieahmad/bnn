@extends('layouts.base_layout')
@section('title', 'Ubah Data Tes Narkoba')

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
  <div class="right_col withAnggaran" role="main">
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
            <h2>Form Ubah Data Tes Narkoba Direktorat Peran Serta Masyarakat</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />
            @if (session('status'))
                      @php
                        $session= session('status');
                      @endphp
                <div class="alert alert-{{$session['status']}}">
                    {{ $session['messages'] }}
                </div>
            @endif

            <form action="{{URL('/pemberdayaan/dir_masyarakat/update_pendataan_tes_narkoba')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
              {{ csrf_field() }}
              <input type="hidden" name="id" value="{{$id}}">
              <div class="form-body">

                <div class="form-group">
                  <label for="no_surat_permohonan" class="col-md-3 control-label">Nomor Surat Permohonan</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{$data_tes['data']['no_surat_permohonan']}}" id="no_surat_permohonan" name="no_surat_permohonan" type="text" class="form-control" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="pelaksana" class="col-md-3 control-label">Pelaksana</label>
                  <input type="hidden" name="id_pelaksana" class="id_pelaksana" value="{{$data_tes['data']['id_instansi']}}"/>
                  <div class="col-md-6">
                    <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required>
                      @foreach($instansi as $wil)
                        <option value="{{$wil['id_instansi']}}" {{($wil['id_instansi'] == $data_tes['data']['id_instansi']) ? 'selected="selected"':""}} >{{$wil['nm_instansi']}}</option>
                      @endforeach
                    </select>

                  </div>
                </div>
                <div class="form-group">
                  <label for="tgl_tes" class="col-md-3 control-label">Tanggal Tes</label>
                  <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                    @if($data_tes['data']['tgl_test'] != "kosong")
                      <input type='text' name="tgl_tes" value="{{ \Carbon\Carbon::parse($data_tes['data']['tgl_test'] )->format('d/m/Y') }}" class="form-control" required />
                    @else
                      <input type="text" name="tgl_tes" value="" class="form-control" required/>
                    @endif
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="sasaran" class="col-md-3 control-label">Sasaran</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">

                    <select class="form-control select2 " name="sasaran">
                      <option value="">-- Pilih Sasaran --</option>
                      @if(isset($sasaran))
                        @if(count($sasaran)>0)
                          @foreach($sasaran as $sa =>$sval)
                            <option value="{{$sa}}" {{($sa == $data_tes['data']['kode_sasaran_test']) ? 'selected="selected"':""}} >{{$sval}}</option>
                          @endforeach
                        @endif
                      @endif
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="jumlah_peserta" class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Peserta</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{$data_tes['data']['jmlh_peserta']}}" id="jumlah_peserta" name="jumlah_peserta" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
                  </div>
                </div>
                  
                <div class="form-group">
                    <label for="jmlh_positif" class="col-md-3 col-sm-3 col-xs-12 control-label">Total Yang Terindikasi Positif</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="{{$data_tes['data']['jmlh_positif']}}" id="jmlh_positif" name="jmlh_positif" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)">
                    </div>
                </div>

                <div class="form-group">
                  <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
                  <div class="col-md-4">
                    <div class="mt-radio-list">
                      <label class="mt-radio col-md-9"> <input  {{($data_tes['data']['kodesumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                        <span>Dipa</span>
                      </label>
                      <label class="mt-radio col-md-9"> <input  {{($data_tes['data']['kodesumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                        <span>Non Dipa</span>
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                    <label for="lokasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Tes</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="{{$data_tes['data']['lokasi']}}" id="lokasi" name="lokasi" type="text" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan_lainnya" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan Lain</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="{{$data_tes['data']['keterangan_lainnya']}}" id="keterangan_lainnya" name="keterangan_lainnya" type="text" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                  
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">         
                        @if ($data_tes['data']['foto1'])
                            <img src="data:image/png;base64,{{$data_tes['data']['foto1']}}" id="blah" style="width:100%;height:150px;" />
                        @else
                            <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
                        @endif                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12"> 
                        @if ($data_tes['data']['foto2'])
                            <img src="data:image/png;base64,{{$data_tes['data']['foto2']}}" id="blah2" style="width:100%;height:150px;" />
                        @else
                            <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
                        @endif                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12"> 
                        @if ($data_tes['data']['foto3'])
                            <img src="data:image/png;base64,{{$data_tes['data']['foto3']}}" id="blah3" style="width:100%;height:150px;" />
                        @else
                            <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
                        @endif                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type='file' name="foto1" onchange="readURL(this);" />
                        <input type="text" name="foto1_old" hidden value="{{$data_tes['data']['foto1']}}"/>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type='file' name="foto2" onchange="readURL2(this);" />
                        <input type="text" name="foto2_old" hidden value="{{$data_tes['data']['foto2']}}"/>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type='file' name="foto3" onchange="readURL3(this);" />
                        <input type="text" name="foto3_old" hidden value="{{$data_tes['data']['foto3']}}"/>
                    </div>
                </div>
                  
                <div class="form-group" id="PilihAnggaran" {{(($data_tes['data']['kodesumberanggaran'] == 'DIPA') ? '' : 'style=display:none;') }}>
                    <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                            <option value="">-- Pilih Anggaran --</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" id="DetailAnggaran" >
                    <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                    <input type="hidden" name="asatker_code" id="kodeSatker" value="">
                    <input type="hidden" name="kode_anggaran" id="kode_anggaran" value="{{( isset($data_anggaran['kode_anggaran']) ? $data_anggaran['kode_anggaran'] : '')}}">
                    <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
                      <table class="table table-striped nowrap">
                        @if($data_tes['data']['anggaran_id'] )
                            @if(count($data_anggaran) > 0)
                                @php $d = $data_anggaran; @endphp
                                <tr><td>Kode Anggaran</td><td>{{$d['kode_anggaran']}}</td></tr>
                                <tr><td>Sasaran</td><td>{{$d['sasaran']}}</td></tr>
                                <tr><td>Target Output</td><td>{{$d['target_output']}}</td></tr>
                                <tr><td>Satuan Output</td><td>{{$d['satuan_output']}}</td></tr>
                                <tr><td>Tahun</td><td>{{$d['tahun']}}</td></tr>
                            @endif
                        @endif
                      </table>
                    </div>
                </div>

              </div>

              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
                    <a href="{{route('pendataan_tes_narkoba')}}" class="btn btn-primary" type="button">BATAL</a>
                  </div>
                </div>
              </div>
            </form>

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
                              <input type="radio" name="options" class="toggle" id="option1">Tambah Terperiksa</label>
                            </div>
                          </div>
                          <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                            <thead>
                              <tr role="row" class="heading">
                                <th width="5%"> No </th>
                                <th width="40%"> Nama </th>
                                <th width="10%"> Usia </th>
                                <th width="10%"> Jenis Kelamin </th>
                                <th width="20%"> Tempat Lahir </th>
                                <th width="15%"> Actions </th>
                              </tr>
                            </thead>
                            @php $i = 1; @endphp
                            @if(count($peserta['data']))
                              <tbody>
                                @foreach($peserta['data'] as $t)
                                  <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$t['peserta_inisial']}}</td>
                                    <td>{{$t['peserta_usia']}}</td>
                                    <td>{{( ($t['kode_jenis_kelamin'] == 'P' ) ?  'Perempuan' : (($t['kode_jenis_kelamin'] == 'L' ) ? 'Laki-Laki' : '')  )}}</td>
                                    <td>{{$t['peserta_tempat_lahir']}}</td>
                                    <td class="actionTable">
                                      <a onclick="edit_datadetail({{$t['peserta_id']}})"><i class="fa fa-pencil"></i></a>

                                      <button data-url='{{$titledel}}' type="button" class="btn btn-primary button-delete" data-target="{{$t['peserta_id']}}" ><i class="fa fa-trash"></i></button>
                                    </td>
                                  </tr>
                                  @php $i = $i+1; @endphp
                                @endforeach
                              </tbody>
                            @else
                              <tbody>
                                <tr>
                                  <td colspan="6">
                                    Data Peserta belum tersedia.
                                  </td>
                                </tr>
                              </tbody>
                            @endif
                          </table>
                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
    var TOKEN = '{{$token}}';
    var TITLE = '{{$title}}';
    </script>

    @include('modal.modal_add_perserta')

    <script>
    function edit_datadetail(id)
    {
      // $('#modal_add_peserta')[0].reset(); // reset form on modals
      // $('.form-group').removeClass('has-error'); // clear error class
      // $('.help-block').empty(); // clear error string
      // $('[name="form_method"]').val('update');
      // $('#xhr_adapter_file_materi').html('');

      $.ajax({
        url : "{{url('api/tesnarkobapeserta')}}" + "/" + id,
        type: "GET",
        dataType: "JSON",
        headers: {
          'Authorization' : 'Bearer '+ TOKEN
        },
        success: function(data)
        {
          //$('.date-picker').datepicker().children('input').val(data.peserta_tanggal_lahir);
          //alert(data.data.peserta_tanggal_lahir);
          $('#formPeserta').attr("action", "{{URL('pemberdayaan/dir_masyarakat/update_peserta')}}");
          $('#modal_add_peserta input[name="peserta_id"]').val(data.data.peserta_id);
          $('#modal_add_peserta input[name="peserta_inisial"]').val(data.data.peserta_inisial);
          $('#modal_add_peserta input[name="peserta_tempat_lahir"]').val(data.data.peserta_tempat_lahir);
          if (data.data.peserta_tanggal_lahir != null){
            var tanggal = data.data.peserta_tanggal_lahir.split("-");
            $('#modal_add_peserta input[name="peserta_tanggal_lahir"]').val(tanggal[2]+ '/' + tanggal[1] + "/" +tanggal[0]);
          }
          $('#modal_add_peserta input[name="peserta_usia"]').val(data.data.peserta_usia);
          $('#modal_add_peserta input[name="no_identitas"]').val(data.data.no_identitas);
          $('#modal_add_peserta select[name="id_brgbukti"]').val(data.data.id_brgbukti).trigger("change");
          $('#modal_add_peserta select[name="kode_negara"]').val(data.data.kode_negara).trigger("change");

          $('#modal_add_peserta #kode_jenisidentitas_'+ data.data.kode_jenis_identitas).prop("checked", true);
          $('#modal_add_peserta #kode_jenis_kelamin_'+ data.data.kode_jenis_kelamin).prop("checked", true);
          $('#modal_add_peserta #kode_pendidikan_akhir_'+ data.data.kode_pendidikan_akhir).prop("checked", true);
          $('#modal_add_peserta #kode_pekerjaan_'+ data.data.kode_pekerjaan).prop("checked", true);
          $('#modal_add_peserta #kode_warga_negara_'+ data.data.kode_warga_negara).prop("checked", true);

          $('#modal_add_peserta').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Peserta'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error get data from ajax');
        }
      });
    }

  </script>

  <div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
				</div>
				<div class="modal-body">
					Apakah Anda ingin menghapus data ini ?
				</div>
				<input type="hidden" class="target_id" value=""/>
        <input type="hidden" class="audit_menu" value="Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Tes Narkoba"/>
        <input type="hidden" class="audit_url" value="http://{{ $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}"/>
        <input type="hidden" class="audit_ip_address" value="{{ $_SERVER['SERVER_ADDR'] }}"/>
        <input type="hidden" class="audit_user_agent" value="{{ $_SERVER['HTTP_USER_AGENT'] }}"/>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
					<button type="button" class="btn btn-primary confirm" onclick="deleteData()">Ya</button>
				</div>
			</div>
		</div>
	</div>
@endsection
