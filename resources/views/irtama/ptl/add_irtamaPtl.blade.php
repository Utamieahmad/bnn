@extends('layouts.base_layout')
@section('title', 'Tambah Data Pemantauan Tindak Lanjut')

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
          <h2>Form Tambah Data Pemantauan Tindak Lanjut Inspektorat Utama</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form action="{{url('/irtama/ptl/input_irtama_ptl')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="form_method" value="create">
            <div class="form-body">

              <div class="form-group">
                <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor LHA</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="periode" name="periode" type="text" class="form-control" maxlength="6" required>
                </div>
              </div>

              <div class="form-group">
                <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal LHA</label>
                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tahun_anggaran" class="form-control col-md-7 col-xs-12 datetimepicker" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="kode_satker" class="col-md-3 control-label">Nama Satker</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="kode_satker" id="kode_satker" class="select2 form-control" tabindex="-1" aria-hidden="true" required>
                    <option>-- Pilih --</option>
                    <option value="puslitdatin">Puslitdatin</option>
                    <option value="sekretariat_utama">Sekretriat Utama</option>
                    <option value="biro_umum">Biro Umum</option>
                    <option value="sarana">Sarana prasarana</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="periode" name="periode" type="text" class="form-control" maxlength="6" required>
                </div>
              </div>

              <div class="x_title">
                <h2>Periode</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <div class="">
                <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Mulai</label>
                <div class="col-md-6 col-xs-6 col-sm-9"><div class="row"><div class="col-sm-5 col-md-5 col-xs-12">
              <!-- <div class="form-group"> -->

                <div class="row"><div class="col-md-11 col-sm-11 col-xs-12 input-group date tanggal">
                  <input type="text" name="tahun_anggaran" class="form-control col-md-7 col-xs-12 datetimepicker">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div></div>
              </div><div class="col-md-7 col-sm-7 col-xs-12 input-group date tanggal"><div class="row">
                  <label for="tahun_anggaran" class="col-md-5 col-sm-5 col-xs-12 control-label">Tanggal Selesai</label><div class="col-md-7 col-sm-7 col-xs-12 input-group date tanggal"><input type="text" name="tahun_anggaran" class="form-control  col-xs-12 datetimepicker"><span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span></div>

                </div></div></div></div>
              </div>
                <!-- <div class="col-sm-6 col-md-6 col-xs-12">
                <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Mulai</label>
                <div class='col-md-2 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tahun_anggaran" class="form-control col-md-7 col-xs-12 datetimepicker" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <div class="col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Akhir</label>
                <div class='col-md-2 col-sm-6 col-xs-12 input-group date tanggal'>
                  <input type='text' name="tahun_anggaran" class="form-control col-md-7 col-xs-12 datetimepicker" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div> -->
            </div>

              <div class="x_title">
                <h2>Peran</h2>
                <div class="clearfix"></div>
              </div>

              <div class="form-group">
                <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Mutu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="periode" name="periode" type="text" class="form-control" maxlength="6" required>
                </div>
              </div>

              <div class="form-group">
                <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="periode" name="periode" type="text" class="form-control" maxlength="6" required>
                </div>
              </div>

              <div class="form-group">
                <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="periode" name="periode" type="text" class="form-control" maxlength="6" required>
                </div>
              </div>

              <div class="form-group">
            <label for="instansi" class="col-md-3 control-label">Anggota</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="mt-repeater">
                    <div data-repeater-list="meta_instansi">
                        <div data-repeater-item="" class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-11">
                                    <label class="control-label">&nbsp;</label>
                                    <input name="meta_instansi[][list_nama_instansi]" type="text" class="form-control"> </div>
                                <div class="col-md-1">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Tambah Anggota</a>
                </div>
            </div>

            </div>
        </div>

              <!-- <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Perintah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="no_sprin" name="no_sprin" type="text" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label for="kode_temuan" class="col-md-3 col-sm-3 col-xs-12 control-label">Kode Temuan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="kode_temuan" name="kode_temuan" type="text" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Temuan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="nama_temuan" name="nama_temuan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Judul Temuan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="judul_temuan" name="judul_temuan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Kondisi Temuan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="kondisi_temuan" name="kondisi_temuan" type="text" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="tindak_lanjut" class="col-md-3 col-sm-3 col-xs-12 control-label">Tindak Lanjut</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="tindak_lanjut" name="tindak_lanjut" type="text" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label for="nilai_tindak_lanjut" class="col-md-3 col-sm-3 col-xs-12 control-label">Nilai Tindak Lanjut</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="" id="nilai_tindak_lanjut" name="nilai_tindak_lanjut" type="number" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label for="url_bukti_tindak_lanjut" class="col-md-3 control-label">Unggah Bukti Tindak Lanjut</label>
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
                    <span class="help-block">
                    </span>
                  </div>
                </div> -->

              </div>

              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
    								<a href="{{route('irtama_ptl')}}" class="btn btn-primary" type="button">BATAL</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_content">
                <div class="x_title">
                <h2>Bidang Audit</h2>
                <div class="clearfix"></div>
              </div>

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#kinerja" id="profile-tab" role="tab" data-toggle="tab" aria-expanded="true">Kinerja</a>
                    </li>
                    <li role="presentation" class=""><a href="#keuangan" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Keuangan</a>
                    </li>
                    <li role="presentation" class=""><a href="#sdm" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">SDM</a>
                    </li>
                    <li role="presentation" class=""><a href="#sarana_prasarana" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Sarana dan Prasarana</a>
                    </li>

                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="kinerja" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <button class="btn btn-success" data-toggle="modal" data-target="#modal_data_rekomendasi_audit">Rekomendasi Kinerja</button>
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th > Judul Temuan </th>
                            <th > Kode Temuan </th>
                            <th > Actions </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>a</td>
                            <td>b</td>
                            <td class="actionTable">
                              <a class="" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                              <a class="deleteTersangka" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                            </td>
                          </tr>

                        </tbody>

                      </table>
                    </div>


                    <div role="tabpanel" class="tab-pane fade active" id="keunagan" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <button class="btn btn-success" data-toggle="modal" data-target="#modal_data_rekomendasi_audit">Rekomendasi Keuangan</button>
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th > Judul Temuan </th>
                            <th > Kode Temuan </th>
                            <th > Actions </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>a</td>
                            <td>b</td>
                            <td class="actionTable">
                              <a class="" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                              <a class="deleteTersangka" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                            </td>
                          </tr>

                        </tbody>

                      </table>

                    </div>

                    <div role="tabpanel" class="tab-pane fade " id="sdm" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <button class="btn btn-success" data-toggle="modal" data-target="#modal_data_rekomendasi_audit">Rekomendasi SDM</button>
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th > Judul Temuan </th>
                            <th > Kode Temuan </th>
                            <th > Actions </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>a</td>
                            <td>b</td>
                            <td class="actionTable">
                              <a class="" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                              <a class="deleteTersangka" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                            </td>
                          </tr>

                        </tbody>

                      </table>

                    </div>

                    <div role="tabpanel" class="tab-pane fade " id="sarana_prasarana" aria-labelledby="profile-tab">
                      <div class="tools pull-right" style="margin-bottom:15px;">
                        <button class="btn btn-success" data-toggle="modal" data-target="#modal_data_rekomendasi_audit">Rekomendasi SaranaPrasarana</button>
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th > Judul Temuan </th>
                            <th > Kode Temuan </th>
                            <th > Actions </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>a</td>
                            <td>b</td>
                            <td class="actionTable">
                              <a class="" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-pencil f-18"></i></a>
                              <a class="deleteTersangka" data-url="{{URL('/pemberantasan/update_tersangka')}}" data-id="" data-api="" style="cursor:pointer"><i class="fa fa-trash f-18"></i></a>
                            </td>
                          </tr>

                        </tbody>

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
@include ('modal.modal_rekomendasi_audit')
@endsection
