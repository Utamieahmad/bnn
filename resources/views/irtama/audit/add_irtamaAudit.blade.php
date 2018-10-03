@extends('layouts.base_layout')
@section('title', 'Tambah Data Audit Laporan Hasil Audit')

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
          <h2>Form Tambah Data Audit Laporan Hasil Audit Inspektorat Utama</h2>
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
            <form action="{{url('/irtama/audit/input_irtama_audit')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on" onSubmit="irtamaAudit(this,event)">
              {{ csrf_field() }}
              <input type="hidden" name="satker_id" value="{{$satker_id}}">
              <div class="form-body">

                <div class="form-group">
                  <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor LHA</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="" name="nomor_lha" type="text" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal LHA</label>
                  <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                    <input type='text' name="tanggal_lha" class="form-control col-md-7 col-xs-12 datepicker-only" required/>
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="nama_satker" class="col-md-3 control-label">Nama Satker</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="nama_satker" id="nama_satker" class="select2 form-control" tabindex="-1" aria-hidden="true" required>
                      <option selected="selected" value="">-- Pilih Satker--</option>
                      @if(count($satker))
                        @foreach($satker as $s => $sval)
                          <option value="{{$sval->nama}}" data-id="{{$sval->id}}">{{$sval->nama}}</option>
                        @endforeach
                      @endif

                    </select>
                  </div>
                  <input type="hidden" class="list_satker" name="list_satker" value=""/>
                </div>

                <div class="form-group">
                  <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                  <div class='col-md-6 col-sm-6 col-xs-6 input-group date year-only'>
                      <input type='text' name="tahun_anggaran" class="form-control" value=""/>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
                </div>

                <div class="clearfix"></div>
                  <div class="">
                    <div class="col-md-3 col-sm-3 col-xs-12 text-right">
                      <h2>Periode</h2>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                    </div>
                  </div>
                  <div class="clearfix"></div>

                <div class="form-group">
                    <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">&nbsp;</label>

                    <div class='col-md-6 col-sm-6 col-xs-12'>
                      <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="row">
                            <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
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
                            <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
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

                  <div class="clearfix"></div>
                  <div class="">
                    <div class="col-md-3 col-sm-3 col-xs-12 text-right">
                      <h2>Peran</h2>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                    </div>
                  </div>
                  <div class="clearfix"></div>

                <div class="form-group">
                  <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Mutu</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">

                    <select data-tags="true" class="form-control select2" id="pengendali_mutu" name="satker_pengendali_mutu" onChange="satkerPengendali(this,'pengendali_mutu')">
  										<option value="">-- Pilih Pengendali Mutu --</option>
  									@foreach($pegawai as $s)
  										<option value="{{$s->nama}}" data-nip="{{$s->nip}}">{{$s->nama}}</option>
  									@endforeach
  									</select>
                    <input type="hidden" name="pengendali_mutu" class="pengendali_mutu" value=""/>
                  </div>
                </div>

                <div class="form-group">
                  <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select data-tags="true" class="form-control select2" id="pengendali_mutu" name="satker_pengendali_teknis" onChange="satkerPengendali(this,'pengendali_teknis')">
  										<option value="">-- Pilih Pengendali Teknis --</option>
  									@foreach($pegawai as $s)
  										<option value="{{$s->nama}}" data-nip="{{$s->nip}}">{{$s->nama}}</option>
  									@endforeach
  									</select>
                    <input type="hidden" name="pengendali_teknis" class="pengendali_teknis" value=""/>
                  </div>
                </div>

                <div class="form-group">
                  <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select data-tags="true" class="form-control select2" id="pengendali_mutu" name="satker_ketua_tim" onChange="satkerPengendali(this,'ketua_tim')">
  										<option value="">-- Pilih Ketua Tim --</option>
  									@foreach($pegawai as $s)
  										<option value="{{$s->nama}}" data-nip="{{$s->nip}}">{{$s->nama}}</option>
  									@endforeach
  									</select>
                    <input type="hidden" name="ketua_tim" class="ketua_tim" value=""/>
                  </div>
                </div>


              <div class="form-group">
                <label for="instansi" class="col-md-3 control-label">Anggota</label>
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                    <div class="mt-repeater">
                        <div data-repeater-list="coll_anggota">
                            <div data-repeater-item="" class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                     <div class="col-md-11">
                                      <select data-tags="true" class="form-control metaSatker" id="meta_anggota" name="meta_anggota[][nama_anggota]">
                                        <option value="">-- Pilih Anggota --</option>
                                        @foreach($pegawai as $s)
                                          <option value="{{$s->nama}}" data-nip="{{$s->nip}}">{{$s->nama}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" data-repeater-delete class="btn btn-danger mt-repeater-delete onDeleteRepeater m-0" onClick="enableButton(this)">
                                            <i class="fa fa-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" data-repeater-create="" class="btn btn-info mt-repeater-add onCreateRepeater" >
                            <i class="fa fa-plus"></i> Tambah Anggota</button>
                    </div>
                    <input type="hidden" name="meta_anggota" class="anggota_collection"/>
                </div>
              </div>
              <div class="form-group">
                <label for="meta_kriminalitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Data PTL</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="checkbox">
                    <label class="mt-checkbox col-md-4 col-sm-4 col-xs-12">
                      <input type="checkbox" value="N" name="data_ptl" onClick="selectDataPtl(this)">
                      <span>&nbsp; Ya</span>
                    </label>
                  </div>
                </div>
              </div>

          </div>

              </div>

              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
    								<a href="{{route('irtama_audit')}}" class="btn btn-primary" type="button">BATAL</a>
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
@include ('modal.modal_rekomendasi_audit')
@endsection
