@extends('layouts.base_layout')
@section('title', 'Ubah Kegiatan Biro Perencanaan Sekretariat Utama')

@section('content')
    <div class="right_col edit_settama" role="main">
        <div class="m-t-40">
        <div class="page-title">
        <div class="">
        {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
        </div>
        </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Form Ubah Kegiatan Biro Perencanaan Sekretariat Utama</h2>
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
                        <form  method="POST" action="{{route('update_settama_perencanaan')}}" data-parsley-validate class="form-horizontal " enctype="multipart/form-data" id="multipart-form" onSubmit="validateForm(event,this)">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$data->id_settama}}" name="id"/>
                            <div class="form-body">
                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Rujukan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="radio">
                                            @if(isset($rujukan))
                                                @if(count($rujukan))
                                                    @foreach($rujukan as $rkey => $rval )
                                                        <div class="group">
                                                            <label class="mt-radio col-md-12 col-sm-12 col-xs-12">
                                                                <input type="radio" value="{{$rkey}}" name="jns_rujukan" onclick="jenisRujukan(this)" {{(isset($data) ? ( (trim($data->jns_rujukan) == $rkey) ? 'checked=checked' :''):'')}}>
                                                                <span>{{$rval}}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="no_rujukan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Rujukan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="no_rujukan" value="{{(isset($data) ? $data->no_rujukan :'')}}" class="form-control" placeholder="no rujukan" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <label for="tgl_mulai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
                                                        <input type='text' name="tgl_mulai" class="form-control" value="{{(isset($data)? ( $data->tgl_mulai ? date('d/m/Y',strtotime($data->tgl_mulai)):'' ): '')}}"/>
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <label for="tgl_selesai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_end'>
                                                        <input type='text' name="tgl_selesai" class="form-control" value="{{(isset($data)? ( $data->tgl_selesai ? date('d/m/Y',strtotime($data->tgl_selesai)) : '') : '')}}"/>
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="pelaksana" class="form-control select2 list_pelaksana" required>
                                            <!--option value="">-- Pilih Pelaksana--</option-->
                                            <option value="4" data-target="4" selected="selected"> Biro Perencanaan </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Bagian</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select required name="bagian" class="form-control pelaksana select2">
                                            <option value="">-- Pilih Bagian --</option>
                                            @if(isset($bagian))
                                                @if(count($bagian))
                                                     @foreach($bagian as $bkey => $bval )
                                                       <option value="{{trim($bval->id_settama_lookup)}}" data-target="{{ trim($bval->id_settama_lookup)}}" {{( isset($data) ? (trim($bval->id_settama_lookup) == trim($data->bagian) ? 'selected=selected' : ''):'')}}> {{trim($bval->lookup_name)}} </option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <select required name="jenis_kegiatan" class="form-control jenis_kegiatan select2">
                                            <option value="">-- Jenis Kegiatan --</option>
                                            @if(isset($kegiatan))
                                                @if(count($kegiatan))
                                                     @foreach($kegiatan as $kkey => $kval )
                                                       <option value="{{trim($kval->id_lookup)}}" {{( isset($data) ? (trim($kval->id_lookup) == trim($data->jenis_kegiatan) ? 'selected=selected' : ''):'')}}> {{trim($kval->lookup_name)}} </option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <input type="text" name="tempat_kegiatan" class="form-control" value="{{(isset($data) ? $data->tempat_kegiatan : '')}}"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tujuan Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <input type="text" name="tujuan_kegiatan" class="form-control" value="{{(isset($data) ? $data->tujuan_kegiatan : '')}}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <div class="radio">
                                      <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{(isset($data) ? ( trim($data->sumber_anggaran) == 'DIPA' ? 'checked=checked' :'') : '')}} value="DIPA" name="sumber_anggaran" id="anggaran1">
                          						<span>Dipa</span>
                          						</label>
                          						<label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{(isset($data) ? ( trim($data->sumber_anggaran) == 'NONDIPA' ? 'checked=checked' :'') : '')}} value="NONDIPA" name="sumber_anggaran" id="anggaran2">
                          						<span>Non Dipa</span>
                          						</label>
                                    </div>
                                </div>
                            </div>

                        		@if($data->anggaran_id != '')
                        			<div class="form-group" id="PilihAnggaran">
                        				<label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
                        				<div class="col-md-6 col-sm-6 col-xs-12">
                        					<select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                        						<option value="">-- Pilih Anggaran --</option>
                        					</select>
                        				</div>
                        			</div>

                          		<div class="form-group" id="DetailAnggaran" >
                          			<label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                        				<input type="hidden" name="asatker_code" id="kodeSatker" value="681595">
                        				<input type="hidden" id="kode_anggaran" value="{{$data_anggaran['data']['kode_anggaran']}}">
                        				<input type="hidden" name="aid_anggaran" id="aid_anggaran" value="{{$data_anggaran['data']['refid_anggaran']}}">
                          			<div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
                          				<table class="table table-striped nowrap">
                          					<tr><td>Kode Anggaran</td><td>{{$data_anggaran['data']['kode_anggaran']}}</td></tr>
                          					<tr><td>Sasaran</td><td>{{$data_anggaran['data']['sasaran']}}</td></tr>
                          					{{-- <tr><td>Pagu</td><td>{{$data_anggaran['data']['pagu']}}</td></tr> --}}
                          					<tr><td>Target Output</td><td>{{$data_anggaran['data']['target_output']}}</td></tr>
                          					<tr><td>Satuan Output</td><td>{{$data_anggaran['data']['satuan_output']}}</td></tr>
                          					<tr><td>Tahun</td><td>{{$data_anggaran['data']['tahun']}}</td></tr>
                          					{{-- <tr><td>Wilayah</td><td>{{$data_anggaran['data']['satker_code']}}</td></tr> --}}
                          				</table>
                          			</div>
                          		</div>
                            @else

                        			<div class="form-group" id="PilihAnggaran" style="display:none">
                        				<label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
                        				<div class="col-md-6 col-sm-6 col-xs-12">
                        					<select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                        						<option value="">-- Pilih Anggaran --</option>
                        					</select>
                        				</div>
                        			</div>

                        			<div class="form-group" id="DetailAnggaran" style="display:none">
                        				<label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                        				<input type="hidden" name="asatker_code" id="kodeSatker" value="681595">
                        				<div class="col-md-6 col-sm-6 col-xs-12" id="hasil">

                        				</div>
                        			</div>
                        		@endif

                            <div class="form-group -b-20">
                                <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <div class="mt-repeater">
                                        <div data-repeater-list="meta_peserta">
                                          @php $form = false; $m = "";$inst = "";$jml = ""; @endphp
                                          @if(isset($data))
                                              @if($data->meta_peserta)
                                                   @php $m = json_decode($data->meta_peserta,true);@endphp
                                                  @if(count($m) >0)
                                                    @for($i = 0 ; $i < count($m) ; $i++)
                                                        @php
                                                          $inst = $m[$i]['nama_instansi'];
                                                          $jml = $m[$i]['jumlah_peserta'];
                                                        @endphp
                                                        <div data-repeater-item="" class="mt-repeater-item">
                                                          <div class="row mt-repeater-row">
                                                              <div class="col-md-8 col-sm-8 col-xs-12">
                                                                  <label class="control-label">Nama Instansi</label>
                                                                  <input name="detail_instansi[{{$i}}][nama_instansi]" value="{{$inst}}" type="text" class="form-control"> </div>
                                                              <div class="col-md-3 col-sm-3 col-xs-12">
                                                                  <div class="row">
                                                                      <label class="control-label">Jumlah Peserta</label>
                                                                      <input name="detail_instansi[{{$i}}][jumlah_peserta]" value="{{$jml}}" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div>
                                                                  </div>
                                                              <div class="col-md-1 col-sm-1 col-xs-12">
                                                                  <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                                      <i class="fa fa-close"></i>
                                                                  </a>
                                                              </div>
                                                          </div>
                                                      </div>
                                                    @endfor
                                                    @php $form = true @endphp
                                                  @else
                                                       @php $form = false @endphp
                                                  @endif
                                              @else
                                               @php $form = false @endphp
                                              @endif
                                          @else
                                               @php $form = false @endphp
                                          @endif
                                          @if($form == false)
                                              <div data-repeater-item="" class="mt-repeater-item">
                                                  <div class="row mt-repeater-row">
                                                      <div class="col-md-8 col-sm-8 col-xs-12">
                                                          <label class="control-label">Nama Instansi</label>
                                                          <input name="detail_instansi[0][nama_instansi]" value="" type="text" class="form-control"> </div>
                                                      <div class="col-md-3 col-sm-3 col-xs-12">
                                                          <div class="row">
                                                              <label class="control-label">Jumlah Peserta</label>
                                                              <input name="detail_instansi[0][jumlah_peserta]" value="" type="text" class="form-control" onKeydown="numeric_only(event,this)"> </div>
                                                          </div>
                                                      <div class="col-md-1 col-sm-1 col-xs-12">
                                                          <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                              <i class="fa fa-close"></i>
                                                          </a>
                                                      </div>
                                                  </div>
                                              </div>
                                          @endif
                                        </div>
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                            <i class="fa fa-plus"></i> Tambah Instansi</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-20">
                                <label for="file_laporan" class="col-md-3 control-label">Laporan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="input-group input-large">
                                            <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                <span class="fileinput-filename"> </span>
                                            </div>
                                            <span class="input-group-addon btn default btn-file">
                                                <span class="fileinput-new"> Pilih Berkas </span>
                                                <span class="fileinput-exists"> Ganti </span>
                                                <input type="file" name="file_laporan" id="file-type"> </span>
                                            <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                        </div>
                                    </div>
                                     <span class="error"></span>
                                      <span class="help-block white">
                                          @if (isset($data))
                                            @if($data->file_laporan)
                                                  Lihat File : <a  target="_blank" class="link_file" href="{{url($file_path.$data->file_laporan)}}">{{$data->file_laporan}}</a>
                                            @endif
                                          @endif
                                      </span>
                                </div>
                            </div>
                            </div>

                            <div class="form-actions fluid m-t-20">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">SIMPAN</button>
                                    <a href="{{route('settama_perencanaan')}}" class="btn btn-primary" type="button">BATAL</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
