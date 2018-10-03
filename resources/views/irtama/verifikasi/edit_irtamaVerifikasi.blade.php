@extends('layouts.base_layout')
@section('title', 'Ubah Data Verifikasi')

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
          <h2>Form Ubah Data Verifikasi Inspektorat Utama</h2>
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
          <form action="{{url('/irtama/verifikasi/update_irtama_verifikasi')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="form_method" value="create">
            <input type="hidden" name="id" value="{{$verifikasi['id']}}">
            <div class="form-body">

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Sprin</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$verifikasi['sprin']}}" id="sprin" name="sprin" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$verifikasi['lokasi']}}" id="lokasi" name="lokasi" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="lokasi_kegiatan_idkabkota" class="col-md-3 control-label">Satker</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                    $data_satker = $verifikasi['kode_satker'];
                    $id_satker = "";
                    if($data_satker){
                      $j = json_decode($data_satker,true);
                      $id_satker = $j['id'];
                    }else{
                      $id_satker = "";
                    }

                  ?>
                  <select name="kode_satker" id="kode_satker" class="select2 form-control" tabindex="-1" aria-hidden="true"  onChange="satker_code(this)">
                    <option value="">-- Pilih --</option>
                     @if(count($satker) > 0 )
                        @foreach($satker as $s => $sval)
                          <option value="{{$sval->nama}}" data-id="{{$sval->id}}" {{( ($sval->id==$id_satker) ? 'selected=selected' : '' )}}>{{$sval->nama}}</option>
                        @endforeach
                      @endif
                  </select>
                </div>
                <input type="hidden" name="list_satker" class="list_satker"/>
              </div>

              <div style="border:1px solid" class="col-md-12 col-sm-12 col-xs-12 border-aero m-b-32 m-t-32 p-b-20">
                <h4 class="text-center m-32">PEJABAT</h4>
                <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Yang Diganti</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input value="{{$verifikasi['pejabat_diganti']}}" id="pejabat_diganti" name="pejabat_diganti" type="text" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">SKEP</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input value="{{$verifikasi['pejabat_skep_diganti']}}" id="pejabat_skep_diganti" name="pejabat_skep_diganti" type="text" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal SKEP</label>
                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                      <input value="{{ ($verifikasi['pejabat_tgl_skep_diganti']) ? \Carbon\Carbon::parse($verifikasi['pejabat_tgl_skep_diganti'])->format('d/m/Y')  : ''}}" type='text' name="pejabat_tgl_skep_diganti" class="form-control" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Yang Baru</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input value="{{$verifikasi['pejabat_baru']}}" id="pejabat_baru" name="pejabat_baru" type="text" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">SKEP</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input value="{{$verifikasi['pejabat_skep_baru']}}" id="pejabat_skep_baru" name="pejabat_skep_baru" type="text" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal SKEP</label>
                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                      <input value="{{ ($verifikasi['pejabat_tgl_skep_baru']) ? \Carbon\Carbon::parse($verifikasi['pejabat_tgl_skep_baru'])->format('d/m/Y') : ''}}" type='text' name="pejabat_tgl_skep_baru" class="form-control" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>

                </div>

              </div>

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">No Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$verifikasi['no_laporan']}}" id="no_laporan" name="no_laporan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tgl_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input value="{{ ($verifikasi['tgl_laporan']) ? \Carbon\Carbon::parse($verifikasi['tgl_laporan'])->format('d/m/Y') : ''}}" type='text' name="tgl_laporan" class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Hal yg Menjadi Perhatian</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$verifikasi['hal_menjadi_perhatian']}}" id="hal_menjadi_perhatian" name="hal_menjadi_perhatian" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="no_hasil_laporan" class="col-md-3 col-sm-3 col-xs-12 control-label">Saran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$verifikasi['saran']}}" id="saran" name="saran" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="file_hasil_pemeriksaan" class="col-md-3 control-label">Unggah Dokumen</label>
                <div class="col-md-5">
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
                    <span class="help-block white">
                      
                       @if ($verifikasi['dokumen'])
                            @php 
                                $file = 'upload/IrtamaVerifikasi/'.$verifikasi['dokumen'];
                            @endphp
                            @if(file_exists($file))
                                Lihat file : <a target="__blank" class="link_file" href="{{\Storage::url('IrtamaVerifikasi/'.$verifikasi['dokumen'])}}"> {{$verifikasi['dokumen']}} </a>
                            @endif
                        @endif
                    </span>
                  </div>
                </div>

              </div>

              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
                    <a href="{{route('irtama_verifikasi')}}" class="btn btn-primary" type="button">BATAL</a>
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
