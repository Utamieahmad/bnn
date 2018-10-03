@extends('Layouts.layoutsGlobal')

@section('content')
<div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
              <div class="">
                  <ul class="page-breadcrumb breadcrumb">
                    <li>
                        Home
                    </li>
                    <li>
                        Direktorat Wastahti
                    </li>
                    <li>
                      Pendataan tahanan di BNN dan BNNP
                    </li>
                    <li class="active">
                      Edit
                    </li>
                  </ul>
                <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
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
                    <h2>Form Design </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form  data-parsley-validate class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  ><h2>Data Penanganan Kasus</h2>
                        </label>

                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Jenis Tahanan
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="radio" class="flat" name="gender" id="genderM" value="M" checked="" required /> Tahanan BNN
                        </div>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                          <input  type="radio" class="flat" name="gender" id="genderF" value="F" />Tahanan Titipan Instansi Lain
                        </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >No. LKN/No. Surat Permohonan Penitipan
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN
                        </label>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  value="LKN" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  value="X" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  value="2017" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text"  value="BNN PUSAT" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Penanggung Jawab (Penyidik)
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Tanggal Kejadian
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >TKP Kasus
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Modus Operandi
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Negara Sumber Narkotika</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jalur Masuk</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Asal
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Transit
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Tujuan
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Kasus</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kasus</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">SUBMIT</button>
                          <button class="btn btn-primary" type="button">Cancel</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Tabs <small>Float left</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tersangka" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Tersangka</a>
                        </li>
                        <li role="presentation" class=""><a href="#barang_bukti_narkotika" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Barang Bukti Narkotika</a>
                        </li>
                        <li role="presentation" class=""><a href="#barang_bukti_prekursor" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Prekursor</a>
                        </li>
                        <li role="presentation" class=""><a href="#barang_bukti_aset" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Aset</a>
                        </li>
                        <li role="presentation" class=""><a href="#barang_bukti_nonnarkotika" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Non Narkotika</a>
                        </li>

                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tersangka" aria-labelledby="home-tab">
                          <div class="tools pull-right" style="margin-bottom:15px;">
                              <div class="btn-group btn-group-devided" data-toggle="buttons">
                                  <label class="btn btn-success " onclick="add_tersangka()">
                                      <input type="radio" name="options" class="toggle" id="option1">Tambah Tersangka</label>
                              </div>
                          </div>
                                  <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                      <thead>
                                          <tr role="row" class="heading">
                                              <th width="5%"> No </th>
                                              <th width="40%"> Nama Tersangka </th>
                                              <th width="15%"> Warga Negara </th>
                                              <th width="15%"> Peran Tersangka </th>
                                              <th width="15%"> Pendidikan Akhir </th>
                                              <th width="15%"> Pekerjaan </th>
                                              <th width="10%"> Actions </th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td>1</td>
                                            <td>Nixon</td>
                                            <td>WNI</td>
                                            <td>DISTIBUTOR</td>
                                            <td>S1</td>
                                            <td>tes</td>
                                            <td class="actionTable">
                                                <a href="{{route('detail_kasus')}}"  ><i class="fa fa-pencil f-18"></i></a>
                                                <a href="#"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                          </tr>
                                      </tbody>
                                  </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="barang_bukti_narkotika" aria-labelledby="profile-tab">
                          <div class="tools pull-right" style="margin-bottom:15px;">
                              <div class="btn-group btn-group-devided" data-toggle="buttons">
                                  <label class="btn btn-success " onclick="add_tersangka()">
                                      <input type="radio" name="options" class="toggle" id="option1">TAMBAH BARANGBUKTI NARKOTIKA</label>
                              </div>
                          </div>
                                  <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                      <thead>
                                        <tr role="row" class="heading">
                                            <th width="2%"> No </th>
                                            <th width="5%"> Jenis Barang Bukti </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="10%"> Actions </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td>1</td>
                                            <td>tes</td>
                                            <td>tes</td>
                                            <td>1</td>
                                            <td class="actionTable">
                                                <a href="{{route('detail_kasus')}}"  ><i class="fa fa-pencil f-18"></i></a>
                                                <a href="#"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                          </tr>
                                      </tbody>
                                  </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="barang_bukti_prekursor" aria-labelledby="profile-tab">
                          <div class="tools pull-right" style="margin-bottom:15px;">
                              <div class="btn-group btn-group-devided" data-toggle="buttons">
                                  <label class="btn btn-success " onclick="add_tersangka()">
                                      <input type="radio" name="options" class="toggle" id="option1">TAMBAH BARANGBUKTI PREKURSOR</label>
                              </div>
                          </div>
                                  <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                      <thead>
                                        <tr role="row" class="heading">
                                            <th width="2%"> No </th>
                                            <th width="5%"> Jenis Barang Bukti </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="10%"> Actions </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td>1</td>
                                            <td>tes</td>
                                            <td>tes</td>
                                            <td>1</td>
                                            <td class="actionTable">
                                                <a href="{{route('detail_kasus')}}"  ><i class="fa fa-pencil f-18"></i></a>
                                                <a href="#"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                          </tr>
                                      </tbody>
                                  </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="barang_bukti_aset" aria-labelledby="profile-tab">
                          <div class="tools pull-right" style="margin-bottom:15px;">
                              <div class="btn-group btn-group-devided" data-toggle="buttons">
                                  <label class="btn btn-success " onclick="add_tersangka()">
                                      <input type="radio" name="options" class="toggle" id="option1">TAMBAH BARANGBUKTI ASET</label>
                              </div>
                          </div>
                                  <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                      <thead>
                                        <tr role="row" class="heading">
                                          <th width="5%"> No </th>
                                          <th width="25%"> Nama Aset </th>
                                          <th width="5%"> Jumlah </th>
                                          <th width="25%"> Konversi (Rupiah) </th>
                                          <th width="20%"> Keterangan </th>
                                          <th width="10%"> Actions </th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td>1</td>
                                            <td>tes</td>
                                            <td>tes</td>
                                            <td>tes</td>
                                            <td>1</td>
                                            <td class="actionTable">
                                                <a href="{{route('detail_kasus')}}"  ><i class="fa fa-pencil f-18"></i></a>
                                                <a href="#"><i class="fa fa-trash f-18"></i></a>
                                            </td>
                                          </tr>
                                      </tbody>
                                  </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="barang_bukti_nonnarkotika" aria-labelledby="profile-tab">
                          <div class="tools pull-right" style="margin-bottom:15px;">
                              <div class="btn-group btn-group-devided" data-toggle="buttons">
                                  <label class="btn btn-success " onclick="add_tersangka()">
                                      <input type="radio" name="options" class="toggle" id="option1">TAMBAH BARANGBUKTI NON NARKOTIKA</label>
                              </div>
                          </div>
                                  <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                                      <thead>
                                        <tr role="row" class="heading">
                                            <th width="2%"> No </th>
                                            <th width="5%"> Jenis Barang Bukti </th>
                                            <th width="15%"> Nama Barang Bukti </th>
                                            <th width="20%"> Jumlah Barang Bukti </th>
                                            <th width="10%"> Actions </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td>1</td>
                                            <td>tes</td>
                                            <td>tes</td>
                                            <td>1</td>
                                            <td class="actionTable">
                                                <a href="{{route('detail_kasus')}}"  ><i class="fa fa-pencil f-18"></i></a>
                                                <a href="#"><i class="fa fa-trash f-18"></i></a>
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
@endsection
