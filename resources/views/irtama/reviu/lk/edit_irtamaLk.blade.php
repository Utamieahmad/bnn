@extends('layouts.base_layout')
@section('title', 'Ubah Data Reviu Laporan Keuangan')

@section('content')

    <div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
                <div class="">
                    {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
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
          <h2>Form Tambah Data Reviu Laporan Keuangan Inspektorat Utama</h2>
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
          <form action="{{url('/irtama/reviu/update_irtama_lk')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$reviulk['id']}}">
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['no_sprint']}}" id="sprin" name="sprin" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="semester" class="col-md-3 col-sm-3 col-xs-12 control-label">Semester</label>
                <div class="col-md-4 col-sm-4 col-xs-12">

                  <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" {{($reviulk['semester'] == '1') ? 'checked="checked"':""}} value="1" name="semester">
                    <span>1</span>
                  </label>

                  <label class="mt-radio col-md-12 col-sm-12 col-xs-12"> <input type="radio" {{($reviulk['semester'] == '2') ? 'checked="checked"':""}} value="2" name="semester">
                    <span>2</span>
                  </label>

                </div>
              </div>

              <div class="form-group">
                <label for="thn_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date year-only'>
                  <input type='text' name="thn_anggaran" class="form-control" value="{{$reviulk['tahun_anggaran']}}"/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="x_title">
                <h2>Object Reviu</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="uappa" class="col-md-3 col-sm-3 col-xs-12 control-label">UAPPA</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['uappa']}}" id="uappa" name="uappa" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="uappa_e1" class="col-md-3 col-sm-3 col-xs-12 control-label">UAPPA-E1</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['uappa_e1']}}" id="uappa_e1" name="uappa_e1" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="uappa_w" class="col-md-3 col-sm-3 col-xs-12 control-label">UAPPA-W</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['uappa_w']}}" id="uappa_w" name="uappa_w" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="uakpa" class="col-md-3 col-sm-3 col-xs-12 control-label">UAKPA</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['uakpa']}}" id="uakpa" name="uakpa" type="text" class="form-control" >
                </div>
              </div>

              <div class="x_title">
                <h2>Tim</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="pengendali_teknis" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="pengendali_teknis" name="pengendali_teknis" required>
										<option value="">-- Pilih Pengendali Teknis --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}" {{ (($reviulk['pengendali_teknis'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              <div class="form-group">
                <label for="ketua_tim" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="ketua_tim" name="ketua_tim" required>
										<option value="">-- Pilih Ketua Tim --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}" {{ (($reviulk['ketua_tim'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              <div class="form-group">
                <label for="pereviu" class="col-md-3 col-sm-3 col-xs-12 control-label">Pereviu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2" id="pereviu" name="pereviu" required>
										<option value="">-- Pilih Pereviu --</option>
									@foreach($pegawai as $s)
										<option value="{{$s['nama']}}" {{ (($reviulk['pereviu'] == $s['nama'])? 'selected=selected' : '')}} >{{$s['nama']}}</option>
									@endforeach
									</select>
                </div>
              </div>

              {{-- <div class="form-group">
                <label for="sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="sprin" name="sprin" type="text" class="form-control" >
                </div>
              </div> --}}

              <div class="x_title">
                <h2>Hasil Reviu</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="lap_realisasi" class="col-md-3 col-sm-3 col-xs-12 control-label">Laporan Realisasi Anggaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['lap_realisasi']}}" id="lap_realisasi" name="lap_realisasi" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="neraca" class="col-md-3 col-sm-3 col-xs-12 control-label">Neraca</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['neraca']}}" id="neraca" name="neraca" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="lap_operasional" class="col-md-3 col-sm-3 col-xs-12 control-label">Laporan Operasional</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['lap_operasional']}}" id="lap_operasional" name="lap_operasional" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="lap_perubahan" class="col-md-3 col-sm-3 col-xs-12 control-label">Laporan Perubahan Ekuitas</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['lap_perubahan']}}" id="lap_perubahan" name="lap_perubahan" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="catatan_lap" class="col-md-3 col-sm-3 col-xs-12 control-label">Catatan Atas Laporan Keuangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['catatan_lap']}}" id="catatan_lap" name="catatan_lap" type="text" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label for="nomor_lap" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Laporan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$reviulk['nomor_lap']}}" id="nomor_lap" name="nomor_lap" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Laporan</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tanggal_lap" class="form-control col-md-7 col-xs-12 datetimepicker" value="{{( $reviulk['tanggal_lap'] ? \Carbon\Carbon::parse($reviulk['tanggal_lap'] )->format('d/m/Y') : '') }}"/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="url_bukti_tindak_lanjut" class="col-md-3 control-label">Laporan Reviu LK</label>
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
                    <span class="help-block" style="color:white">
                        @if (!empty($reviulk['lap_reviu']))
                            lihat file : <a style="color:yellow" href="{{\Storage::url('IrtamaReviuLk/'.$reviulk['lap_reviu'])}}">{{$reviulk['lap_reviu']}}</a>
                        @endif
                    </span>
                  </div>
                </div>


            </div>

            <div class="form-actions fluid">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success">SIMPAN</button>
                  <a href="{{route('irtama_lk')}}" class="btn btn-primary" type="button">BATAL</a>
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
