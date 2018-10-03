@extends('layouts.base_layout')
@section('title', 'Tambah Kegiatan Biro Umum Sekretariat Utama')

@section('content')
    <div class="right_col" role="main">
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
                        <h2>Form Tambah Kegiatan Biro Umum Sekretariat Utama</h2>
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
                        <form  method="POST" action="{{route('save_settama_umum')}}" id="multipart-form" data-parsley-validate class="form-horizontal" enctype="multipart/form-data" onSubmit="validateForm(event,this)">
                        {{csrf_field()}}
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
                                                                <input type="radio" value="{{trim($rkey)}}" name="jns_rujukan" onclick="jenisRujukan(this)">
                                                                <span>{{trim($rval)}}</span>
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
                                        <input type="text" name="no_rujukan" class="form-control" placeholder="no rujukan" />
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
                                                        <input type='text' name="tgl_mulai" class="form-control" value=""/>
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
                                                        <input type='text' name="tgl_selesai" class="form-control" value=""/>
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
                                        <select name="pelaksana" id="pelaksana_settama" class="form-control select2" required>
                                            <!--option value="">-- Pilih Pelaksana--</option-->
                                            <option value="1" data-target="1" selected> Biro Umum </option>
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
                                                       <option value="{{trim($bval->id_settama_lookup)}}" data-target="{{ trim($bval->id_settama_lookup)}}"> {{trim($bval->lookup_name)}} </option>
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
                                                       <option value="{{trim($kval->id_lookup)}}"> {{trim($kval->lookup_name)}} </option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <input type="text" name="tempat_kegiatan" class="form-control" value=""/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Tujuan Kegiatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <input type="text" name="tujuan_kegiatan" class="form-control" value=""/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <div class="radio">
                                      <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="DIPA" name="sumber_anggaran" id="anggaran1">
                  						<span>Dipa</span>
                  						</label>
                  						<label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" value="NONDIPA" name="sumber_anggaran" id="anggaran2">
                  						<span>Non Dipa</span>
                  						</label>
                                    </div>
                                </div>
                            </div>

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

                            <div class="form-group -b-20">
                                <label for="tema" class="col-md-3 col-sm-3 col-xs-12 control-label">Peserta</label>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <div class="mt-repeater">
                                        <div data-repeater-list="meta_peserta">
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
                                </div>
                            </div>
                            </div>


                            <div class="form-actions fluid m-t-20">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">SIMPAN</button>
                                    <a href="{{route('settama_umum')}}" class="btn btn-primary" type="button">BATAL</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script type="text/javascript">
    $(document).ready(function(){
      var pelaksana = $('#pelaksana').val();
      alert(pelaksana);//getBagianPelaksana(null, pelaksana);
    });
    </script> --}}

@endsection
