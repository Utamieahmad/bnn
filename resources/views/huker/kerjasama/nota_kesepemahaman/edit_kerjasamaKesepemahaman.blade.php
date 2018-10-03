@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Nota Kesepahaman')

@section('content')
    <div class="right_col" role="main">
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
                    <h2>Form Ubah Data Kegiatan Nota Kesepahaman Direktorat Kerja Sama</h2>
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
    <form action="{{URL('/huker/dir_kerjasama/update_kerjasama_kesepemahaman')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
    		{{ csrf_field() }}
    		<input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">

        <div class="form-group">
            <label for="jenis_kerjasama" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kerja Sama</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="mt-radio-list">

                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" {{($data_detail['data']['jenis_kerjasama'] == 'Nota Kesepahaman') ? 'checked="checked"':""}} value="Nota Kesepahaman" name="jenis_kerjasama" required >
                    <span>Nota Kesepahaman / Memorandum of Understanding</span>
                    </label>

                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" {{($data_detail['data']['jenis_kerjasama'] == 'Perjanjian Kerja Sama') ? 'checked="checked"':""}} value="Perjanjian Kerja Sama" name="jenis_kerjasama">
                    <span>Perjanjian Kerja Sama / Plan of Action</span>
                    </label>

                    <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" {{($data_detail['data']['jenis_kerjasama'] == 'Letter of Intent') ? 'checked="checked"':""}} value="Letter of Intent" name="jenis_kerjasama">
                    <span>Letter of Intent</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="nama_instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Instansi Mitra</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['nama_instansi']}}" id="nama_instansi" name="nama_instansi" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_sprint" class="col-md-3 col-sm-3 col-xs-12 control-label">No. MoU/PKS</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['nomor_sprint']}}" id="nomor_sprint" name="nomor_sprint" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_ttd" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Ditandatangani</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
              @if($data_detail['data']['tgl_ttd'] != "")
              <input type='text' name="tgl_ttd" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_ttd'])->format('d/m/Y') }}" class="form-control" />
              @else
              <input type="text" name="tgl_ttd" value="" class="form-control" />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_berakhir" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Berakhir</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date date_end'>
              @if($data_detail['data']['tgl_berakhir'] != "")
              <input type='text' name="tgl_berakhir" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_berakhir'] )->format('d/m/Y') }}" class="form-control" />
              @else
              <input type="text" name="tgl_berakhir" value="" class="form-control" />
              @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_ttd" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Penandatanganan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['tempat_ttd']}}" id="tempat_ttd" name="tempat_ttd" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tema_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tema</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{$data_detail['data']['tema']}}" id="tema" name="tema" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Ruang Lingkup Kerja Sama</label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_ruanglingkup_kerjasama">
                      @foreach(json_decode($data_detail['data']['meta_ruanglingkup_kerjasama'],true) as $r1 => $c1)
                        <div data-repeater-item="" class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label"></label>
                                    <input name="meta_ruanglingkup_kerjasama[{{$r1}}][list_isi]" value="{{$c1['list_isi']}}" type="text" class="form-control"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                      @endforeach
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i>Tambah Ruang Lingkup Kerja Sama</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Unit Kerja Pelaksana</label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_unitkerja_pelaksana">
                      @foreach(json_decode($data_detail['data']['meta_unitkerja_pelaksana'],true) as $r1 => $c1)
                        <div data-repeater-item="" class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label"></label>
                                    <input name="meta_unitkerja_pelaksana[{{$r1}}][list_isi]" value="{{$c1['list_isi']}}" type="text" class="form-control"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                      @endforeach
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i>Tambah Unit Kerja Pelaksana</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="kodesumberanggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sumber Anggaran</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="mt-radio-list">
                  <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['kode_sumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} value="DIPA" name="kodesumberanggaran" id="anggaran1">
                  <span>Dipa</span>
                  </label>
                  <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['kode_sumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                  <span>Non Dipa</span>
                  </label>
                </div>
            </div>
        </div>

        <div class="form-group" style="display:none">
            <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>

          @if($data_detail['data']['anggaran_id'] != '')
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

        <div class="form-group">
            <label for="keterangan" class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="mt-radio-list">

                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['kodejenisinstansi'] == 'Instansi Pemerintah') ? 'checked="checked"':""}} value="Instansi Pemerintah" name="kodejenisinstansi">
                    <span>Instansi Pemerintah</span>
                    </label>

                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['kodejenisinstansi'] == 'Komponen Masyarakat') ? 'checked="checked"':""}} value="Komponen Masyarakat" name="kodejenisinstansi">
                    <span>Komponen Masyarakat</span>
                    </label>

                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['kodejenisinstansi'] == 'Regional') ? 'checked="checked"':""}} value="Regional" name="kodejenisinstansi">
                    <span>Regional</span>
                    </label>

                    <label class="mt-radio col-md-9 col-sm-9 col-xs-12"> <input type="radio" {{($data_detail['data']['kodejenisinstansi'] == 'Internasional') ? 'checked="checked"':""}} value="Internasional" name="kodejenisinstansi">
                    <span>Internasional</span>
                    </label>


                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="file_hasil" class="col-md-3 col-sm-3 col-xs-12 control-label">Dokumen Kegiatan</label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group input-large">
                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                            <span class="fileinput-filename"> </span>
                        </div>
                        <span class="input-group-addon btn default btn-file">
                            <span class="fileinput-new"> Pilih Berkas </span>
                            <span class="fileinput-exists"> Ganti </span>
                            <input type="file" name="file_upload"> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                    </div>
                </div>
                <span class="help-block" style="color:white">
    				@if (!empty($data_detail['data']['file_upload']))
						lihat file : <a style="color:yellow" href="{{\Storage::url('KerjasamaNotakesepahaman/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
    				@endif
				</span>
            </div>
        </div>

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
				<a href="{{route('kerjasama_kesepemahaman')}}" class="btn btn-primary" type="button">BATAL</a>
            </div>
        </div>
    </div>
</form>
                </div>
            </div>
            </div>
            </div>
        </div>
    </div>
@endsection
