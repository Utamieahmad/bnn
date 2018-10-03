@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Pembelaan Hukum (Pendampingan)')

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
                    <h2>Form Ubah Data Kegiatan Pembelaan Hukum (Pendampingan) Direktorat Hukum</h2>
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
    <form action="{{URL('/huker/dir_hukum/update_hukum_pendampingan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">

        <div class="form-group">
            <label for="tgl_sidang" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pendampingan</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  @if($data_detail['tgl_sidang'] != "")
                  <input type='text' name="tgl_sidang" value="{{ \Carbon\Carbon::parse($data_detail['tgl_sidang'] )->format('d/m/Y') }}" class="form-control" required />
                  @else
                  <input type="text" name="tgl_sidang" value="" class="form-control" required />
                  @endif
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="tempatsidang" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['tempatsidang'] }}" id="tempatsidang" name="tempatsidang" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tempatsidang_idprovinsi" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="tempatsidang_idprovinsi" id="tempatsidang_idprovinsi" class="select2 form-control" placeholder="Pilih Kabupaten/Kota" tabindex="-1" aria-hidden="true">
                     <option value="">-- Pilih Kabupaten --</option>
                    @foreach($propkab['data'] as $keyGroup => $jenis )
                    <optgroup label="{{$keyGroup}}">
                    @foreach($jenis as $key => $val)
                    <option value="{{$key}}" {{($data_detail['tempatsidang_idprovinsi'] == $key) ? 'selected="selected"':""}}>{{$val}}</option>
                    @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_perkara" class="col-md-3 col-sm-3 col-xs-12 control-label">No Perka/Kasus</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['nomor_perkara'] }}" id="nomor_perkara" name="nomor_perkara" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="tgl_perkara" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Perka/Kasus</label>
            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  @if($data_detail['tgl_perkara'] != "")
                  <input type='text' name="tgl_perkara" value="{{ \Carbon\Carbon::parse($data_detail['tgl_perkara'] )->format('d/m/Y') }}" class="form-control" />
                  @else
                  <input type="text" name="tgl_perkara" value="" class="form-control" />
                  @endif
                  <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_kasus" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kasus</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['jenis_kasus'] }}" id="jenis_kasus" name="jenis_kasus" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="permasalahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Permasalahan Kasus</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ $data_detail['permasalahan'] }}" id="permasalahan" name="permasalahan" type="text" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="nip_didampingi" class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas yang Didampingi</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="row">
                    @php
                        $didampingi = json_decode($data_detail['meta_didampingi'], true);

                        $satker_id = $didampingi['list_satker'];
                    @endphp
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <label class="control-label">Satuan Kerja</label>
                        <select class="form-control select2" onchange="loadIdentitasPendamping(this)" name="meta_didampingi[list_satker]">
                            <option value="">-- Pilih Satuan Kerja --</option>
                              @if(count($satker))
                                @foreach($satker as $s => $sval)
                                  <option value="{{$sval->id}}" {{($didampingi['list_satker'] == $sval->id) ? 'selected="selected"':""}}>{{$sval->nama}}</option>
                                @endforeach
                              @endif
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <label class="control-label">NIP/Nama</label>
                        <select class="form-control select2" name="meta_didampingi[list_nip]">
                            <option value="">-- Pilih Identitas --</option>
                            @if(isset($pegawai[$satker_id]))
                              @foreach($pegawai[$satker_id] as $p)
                                <option value="{{$p['nip']}}" {{($didampingi['list_nip'] == $p['nip']) ? 'selected="selected"':""}}>{{ $p['nip'] . ' - ' . $p['nama']}}</option>
                              @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas Pendamping</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="meta-repeater">
                    <div data-repeater-list="meta_pendamping">
                                 @php
                                     $meta_pendamping = json_decode($data_detail['meta_pendamping'],true);
                                 @endphp
                                 @if (count($meta_pendamping)>0)
                                @foreach($meta_pendamping as $r1 => $c1)
                                <div data-repeater-item class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Satuan Kerja</label>
                                    <select class="form-control metaSatker" name="meta_pendamping[{{$r1}}][list_satker]" onchange="loadIdentitasPendampingList(this)">
                                        <option value="">-- Pilih Satuan Kerja --</option>
                                          @if(count($satker))
                                            @foreach($satker as $s => $sval)
                                              <option value="{{$sval->id}}" {{ (isset($c1['list_satker']) ? (($c1['list_satker'] == $sval->id) ? 'selected="selected"' : '') : '')}}>{{$sval->nama}}</option>
                                            @endforeach
                                          @endif
                                    </select>

                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">NIP/Nama</label>
                                    <select class="form-control metaSatker" name="meta_pendamping[{{$r1}}][list_nip]">
                                        <option value="">-- Pilih Identitas --</option>
                                        @php
                                            $satker_id = $c1['list_satker'];
                                        @endphp
                                        @if(isset($pegawai[$satker_id]))
                                          @foreach($pegawai[$satker_id] as $p)
                                            <option value="{{$p['nip']}}" {{($c1['list_nip'] == $p['nip']) ? 'selected="selected"':""}}>{{ $p['nip'] . ' - ' . $p['nama']}}</option>
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
                              @else
                                <div data-repeater-item class="mt-repeater-item">
                                    <div class="row mt-repeater-row">
                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                            <label class="control-label">Satuan Kerja</label>
                                            <select class="form-control metaSatker" name="meta_pendamping[][list_satker]" onchange="loadIdentitasPendampingList(this)">
                                                <option value="">-- Pilih Satuan Kerja --</option>
                                                  @if(count($satker))
                                                    @foreach($satker as $s => $sval)
                                                      <option value="{{$sval->id}}" data-id="{{$sval->id}}">{{$sval->nama}}</option>
                                                    @endforeach
                                                  @endif
                                            </select>

                                        </div>
                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                            <label class="control-label">NIP/Nama</label>
                                            <select class="form-control metaSatker" name="meta_pendamping[][list_nip]">
                                                <option value="">-- Pilih Identitas --</option>
                                            </select>
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
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreatePendamping">
                        <i class="fa fa-plus"></i> Tambah Identitas</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Identitas Pendamping Luar BNN</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_pendamping_luar_bnn">
                        <div data-repeater-item class="mt-repeater-item">
                            @if(count(json_decode($data_detail['meta_pendamping_luar_bnn'])) > 0)
                                @foreach(json_decode($data_detail['meta_pendamping_luar_bnn'],true) as $r1 => $c1)
                                <div class="row mt-repeater-row">
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <label class="control-label">Nama</label>
                                        <input name="meta_pendamping_luar_bnn[{{$r1}}][list_nama]" value="{{ (isset($c1['list_nama']) ? $c1['list_nama'] : '')}}" type="text" class="form-control"> </div>
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <label class="control-label">No Surat Kuasa</label>
                                        <input name="meta_pendamping_luar_bnn[{{$r1}}][list_no_surat]" value="{{ (isset($c1['list_no_surat']) ? $c1['list_no_surat'] : '')}}" type="text" class="form-control"> </div>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <label class="control-label">Kantor Hukum</label>
                                        <input name="meta_pendamping_luar_bnn[{{$r1}}][list_kantor_hukum]" value="{{ (isset($c1['list_kantor_hukum']) ? $c1['list_kantor_hukum'] : '')}}" type="text" class="form-control"> </div>
                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @else

                            <div class="row mt-repeater-row">
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">Nama</label>
                                    <input name="meta_pendamping_luar_bnn[][list_nama]" type="text" class="form-control"> </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <label class="control-label">No Surat Kuasa</label>
                                    <input name="meta_pendamping_luar_bnn[][list_no_surat]" type="text" class="form-control"> </div>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <label class="control-label">Kantor Hukum</label>
                                    <input name="meta_pendamping_luar_bnn[][list_kantor_hukum]" type="text" class="form-control"> </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>

                            @endif
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Tambah Identitas</a>
                </div>
            </div>
        </div>

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

    </div>

     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('hukum_pendampingan')}}" class="btn btn-primary" type="button">BATAL</a>
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
