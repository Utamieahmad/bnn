@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Pembelaan Hukum (Pra Peradilan)')

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
                    <h2>Form Ubah Data Kegiatan Pembelaan Hukum (Pra Peradilan) Direktorat Hukum</h2>
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
   <form action="{{URL('/huker/dir_hukum/update_hukum_prapradilan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">
        <div class="form-group" style="display:none">
            <label for="pelaksana" class="col-md-3 control-label">Pelaksana</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
                  {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_mulai" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Kegiatan Pra Peradilan</label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_mulai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date datepicker-only'>
                                @if($data_detail['tgl_mulai'] != '')
                                    <input type='text' name="tgl_mulai" class="form-control" value="{{ \Carbon\Carbon::parse($data_detail['tgl_mulai'] )->format('d/m/Y') }}" required />
                                @else
                                    <input type='text' name="tgl_mulai" class="form-control" value="" required />
                                @endif
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label for="tgl_selesai" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                            <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal'>
                                @if($data_detail['tgl_selesai'] != '')
                                    <input type='text' name="tgl_selesai" class="form-control" value="{{ \Carbon\Carbon::parse($data_detail['tgl_selesai'] )->format('d/m/Y') }}" required />
                                @else
                                    <input type='text' name="tgl_selesai" class="form-control" value="" required />
                                @endif
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
            <label for="tempat_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['tempat_kegiatan'] }}" id="tempat_kegiatan" name="tempat_kegiatan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="lokasi_kegiatan" id="lokasi_kegiatan" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                     <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab['data'] as $keyGroup => $jenis )
                    <optgroup label="{{$keyGroup}}">
                    @foreach($jenis as $key => $val)
                    <option value="{{$key}}" {{($data_detail['lokasi_kegiatan'] == $key) ? 'selected="selected"':""}}>{{$val}}</option>
                    @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_permohonan_praperadilan" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Permohonan Pra Peradilan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['no_permohonan'] }}" id="nomor_permohonan_praperadilan" name="nomor_permohonan_praperadilan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_permohonan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Permohonan Pra Peradilan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                @if($data_detail['tgl_permohonan'] != '')
                    <input type='text' name="tgl_permohonan" class="form-control" value="{{ \Carbon\Carbon::parse($data_detail['tgl_permohonan'] )->format('d/m/Y') }}" />
                @else
                    <input type='text' name="tgl_permohonan" class="form-control" value="" />
                @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="permasalahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Permasalahan Pra Peradilan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['permasalahan'] }}" id="permasalahan" name="permasalahan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="no_perkara" class="col-md-3 col-sm-3 col-xs-12 control-label">No Perkara</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['no_perkara'] }}" id="no_perkara" name="no_perkara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tergugat" class="col-md-3 col-sm-3 col-xs-12 control-label">Tergugat</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['tergugat'] }}" id="tergugat" name="tergugat" placeholder="Jabatan" type="text" class="form-control">
            </div>
        </div>

        <div class="x_title">
            <h2>Identitas Pemohon Peradilan</h2>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Identitas</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['jns_identitas_pemohon'] }}" id="jenis_identitas" name="jenis_identitas" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="no_identitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Identitas</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['no_identitas_pemohon'] }}" id="no_identitas" name="no_identitas" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nama_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['nama_pemohon'] }}" id="nama_pemohon" name="nama_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Lahir</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['tempat_lahir_pemohon'] }}" id="tempat_lahir" name="tempat_lahir" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_lahir_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Lahir</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                @if($data_detail['tgl_lahir_pemohon'] != '')
                    <input value="{{ \Carbon\Carbon::parse($data_detail['tgl_lahir_pemohon'] )->format('d/m/Y') }}" type='text' name="tgl_lahir_pemohon" class="form-control" />
                @else
                    <input value="" type='text' name="tgl_lahir_pemohon" class="form-control" />
                @endif
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Pemohon</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['lokasi_pemohon'] }}" id="lokasi_pemohon" name="lokasi_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Pemohon</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['alamat_pemohon'] }}" id="alamat_pemohon" name="alamat_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_pemohon" class="col-md-3 col-sm-3 col-xs-12 control-label">Pekerjaan Pemohon</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['pekerjaan_pemohon'] }}" id="pekerjaan_pemohon" name="pekerjaan_pemohon" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana Pra Peradilankan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    @php
                        $meta_praperadilan = json_decode($data_detail['meta_praperadilan'], true);
                    @endphp
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label">No Surat Kuasa</label>
                        <input value="{{ (isset($meta_praperadilan['no_surat_kuasa'])) ?  $meta_praperadilan['no_surat_kuasa'] : '' }}" name="meta_praperadilan[no_surat_kuasa]" type="text" class="form-control"> </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label">No Surat Perintah</label>
                        <input value="{{ (isset($meta_praperadilan['no_sprint'])) ? $meta_praperadilan['no_sprint'] : '' }}" name="meta_praperadilan[no_sprint]" type="text" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas Pelaksana</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="meta-repeater">
                    <div data-repeater-list="meta_pelaksana">
                        @php
                            $meta_pelaksana = json_decode($data_detail['meta_pelaksana'],true);
                        @endphp
                        @foreach($meta_pelaksana as $r1 => $c1)
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Satuan Kerja</label>
                                    <select class="form-control metaSatker" name="meta_pelaksana[{{$r1}}][list_satker]" onchange="loadIdentitasPelaksanaList(this)">
                                        <option value="">-- Pilih Satuan Kerja --</option>
                                          @if(count($satker))
                                            @foreach($satker as $s => $sval)
                                              <option value="{{$sval->id}}" data-id="{{$sval->id}}" {{(isset($c1['list_satker'])) ? (($c1['list_satker'] == $sval->id) ? 'selected="selected"':"") : ''}}>{{$sval->nama}}</option>
                                            @endforeach
                                          @endif
                                    </select>

                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">NIP/Nama</label>
                                    <select class="form-control metaSatker" name="meta_pelaksana[{{$r1}}][list_nip]">
                                        <option value="">-- Pilih Identitas --</option>
                                        @php
                                            $satker_id = $c1['list_satker'];
                                        @endphp
                                        @if(isset($pegawai[$satker_id]))
                                          @foreach($pegawai[$satker_id] as $p)
                                            <option value="{{$p['nip']}}" {{(isset($c1['list_nip'])) ? (($c1['list_nip'] == $p['nip']) ? 'selected="selected"':"") : ''}}>{{ $p['nip'] . ' - ' . $p['nama']}}</option>
                                          @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreatePendamping">
                        <i class="fa fa-plus"></i> Tambah Pelaksana</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas Ahli Hukum</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_ahli">
                        @php
                            $meta_ahli = json_decode($data_detail['meta_ahli_hukum'],true);
                        @endphp
                        @foreach($meta_ahli as $r1 => $c1)
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Nama</label>
                                    <input value="{{ (isset($c1['nama'])) ? $c1['nama'] : '' }}" name="meta_ahli[{{$r1}}][nama]" type="text" class="form-control"> </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">No Surat Tugas</label>
                                    <input value="{{ (isset($c1['no_surat_tugas'])) ? $c1['no_surat_tugas'] : '' }}" name="meta_ahli[{{$r1}}][no_surat_tugas]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <label class="control-label">Akademisi</label>
                                    <input value="{{ (isset($c1['akademisi'])) ? $c1['akademisi'] : '' }}" name="meta_ahli[{{$r1}}][akademisi]" type="text" class="form-control"> </div>
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
                        <i class="fa fa-plus"></i> Tambah Ahli Hukum</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Data Sidang</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="mt-repeater">
                    @php
                        $meta_sidang = json_decode($data_detail['meta_sidang'],true);
                    @endphp
                    @foreach($meta_sidang as $r1 => $c1)
                    <div data-repeater-list="meta_sidang">
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Sidang Ke</label>
                                    <input value="{{ (isset($c1['sidang'])) ? $c1['sidang'] : '' }}" name="meta_sidang[][sidang]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                <div class="col-md-5 col-sm-5 col-xs-12" >
                                    <label class="control-label">Tanggal Sidang</label>
                                    <div class='col-md-12 col-sm-12 col-xs-12 input-group date tanggal p-l-0 p-r-0' >
                                        <input type='text' value="{{ (isset($c1['tgl_sidang'])) ? $c1['tgl_sidang'] : '' }}" name="meta_sidang[][tgl_sidang]" class="form-control metaDate" />
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div> </div>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <label class="control-label">Note</label>
                                    <input name="meta_sidang[][note]" value="{{ (isset($c1['note'])) ? $c1['note'] : '' }}" type="text" class="form-control"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreateDataSidang">
                        <i class="fa fa-plus"></i> Tambah Data</a>
                </div>
            </div>
        </div>

        <div class="form-group">
          <label for="sumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
          <div class="col-md-4">
            <div class="mt-radio-list">
              <label class="mt-radio col-md-9"> <input {{(trim($data_detail['sumberanggaran']) == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="sumberanggaran" id="anggaran1">
                <span>Dipa</span>
              </label>

              <label class="mt-radio col-md-9"> <input {{(trim($data_detail['sumberanggaran']) == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="sumberanggaran" id="anggaran2">
                <span>Non Dipa</span>
              </label>
            </div>
          </div>
        </div>

          @if($data_detail['anggaran_id'] != '')
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

        <div class="form-group" id="DetailAnggaran" style="display:none">
            <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
            <input type="hidden" name="asatker_code" id="kodeSatker" value="">
            <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
            </div>
        </div>

        <div class="form-group">
            <label for="hasil_akhir" class="col-md-3 col-sm-3 col-xs-12 control-label">Hasil Akhir</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="hasil_akhir" id="hasil_akhir" class="select2 form-control" tabindex="-1" aria-hidden="true">
                    <option value="">-- Pilih Hasil --</option>
                    <option value="Menang" {{(trim($data_detail['hasil_akhir']) == 'Menang') ? 'selected="selected"':""}}>Menang</option>
                    <option value="Kalah" {{(trim($data_detail['hasil_akhir']) == 'Kalah') ? 'selected="selected"':""}}>Kalah</option>
                </select>
            </div>
        </div>
    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('hukum_prapradilan')}}" class="btn btn-primary" type="button">BATAL</a>
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
