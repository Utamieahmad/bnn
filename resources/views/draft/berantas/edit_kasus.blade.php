@extends('Layouts.layoutsGlobal')
<script>
function add_barangbukti_nonnarkotika()
    {
        $('#form_barangbuktinonnarkotika')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_barangbuktinonnarkotika').modal('show');
        $('.modal-title').text('Tambah Barang Bukti Non Narkotika');
        $('[name="form_method"]').val('create');
    }
</script>
@section('content')
<div class="right_col" role="main">
   <div class="page-title">
      <ul class="page-breadcrumb breadcrumb">

        <li>
          Direktorat Narkotika
        </li>
        <li >
          Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)
        </li>
        <li class="active">
          Tambah
        </li>
      </ul>
  </div>
    <div class="clearfix">
    </div>
      <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div>
                    <i class="fa fa-plus-square"></i> Form Entri</div>
                    <div class="ln_solid"></div>
                    <div class="clearfix"></div>
                  </div>

                    <form  data-parsley-validate class="form-horizontal">
                      <div class="form-group">
                        <div class="col-md-2" >
                        <label  for="first-name">Tanggal LKN
                        </label>
                      </div>
                        <div class="col-md-3">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                      <label class="col-md-2">Pelakasana</label>
                      <div class="col-md-6">
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
                        <div class="col-md-2">
                          <label >Nomor LKN
                          </label>
                        </div>
                          <div class="col-md-1" >
                          <input name="vlkn_tipe" value="LKN" class="form-control" readonly>
                          </div>
                          <div class="col-md-1" >
                          <input name="vlkn_no" class="form-control mask_number" value="1234" maxlength="4">
                          </div>
                          <div class="col-md-2">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                          <div class="col-md-1">
                          <input value="x" readonly name="vlkn_bulan" class="form-control">
                          </div>
                          <div class="col-md-1">
                          <input value="2017" readonly name="vlkn_tahun" value="2016" class="form-control">
                          </div>
                          <div class="col-md-2">
                           <input readonly name="vlkn_wilayah" value="BNN PUSAT" class="form-control">
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label hidden="" >Nomor LKN
                        </label>
                      </div>
                        <div class="col-md-5">
                          <input type="text" id="last-name" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-2">
                        <label  >Penanggung Jawab (Penyidik)
                        </label>
                        </div>
                        <div class="col-md-4">
                          <input type="text" required="required" class="form-control col-md-6">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label  >Tanggal Kejadian
                        </label>
                        </div>
                        <div class="col-md-3">
                          <input type="text" required="required" class="form-control col-md-6">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label  >TKP Kasus
                        </label>
                        </div>
                        <div class="col-md-4">
                          <input type="text" required="required" class="form-control col-md-6">
                        </div>
                      </div>

                    <div class="form-group">
                      <div class="col-md-2">
                      <label hidden="" >Provinsi</label>
                    </div>
                      <div class="col-md-3">
                        <select class="form-control">
                          <option>Provinsi</option>
                          <option>Option one</option>
                          <option>Option two</option>
                          <option>Option three</option>
                          <option>Option four</option>
                        </select>
                      </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label hidden="" >Kabupaten / kota</label>
                      </div>
                        <div class="col-md-3">
                          <select class="form-control">
                            <option>Kabupaten / Kota </option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                          <div class="col-md-2">
                          <label  >Modus Operandi
                          </label>
                          </div>
                          <div class="col-md-4">
                            <input type="text" required="required" class="form-control col-md-6">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-2">
                          <label  >Negara Sumber Narkotika</label>
                        </div>
                          <div class="col-md-3">
                            <select class="form-control">
                              <option>Negara </option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                          </div>

                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Rute Asal
                            </label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" required="required" class="form-control col-md-6">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Rute Transit
                            </label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" required="required" class="form-control col-md-6">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Rute Tujuan
                            </label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" required="required" class="form-control col-md-6">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Kelompok Kasus
                            </label>
                          </div>
                            <div class="col-md-3">
                              <select class="form-control">
                                <option>Kelompok Kasus </option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                                <option>Option four</option>
                              </select>
                            </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-2">
                              <label  >Jenis Kasus</label>
                            </div>
                              <div class="col-md-3">
                                <select class="form-control">
                                  <option>Jenis Kasus </option>
                                  <option>Option one</option>
                                  <option>Option two</option>
                                  <option>Option three</option>
                                  <option>Option four</option>
                                </select>
                              </div>
                              </div>

                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">SUBMIT</button>
                          <button class="btn btn-primary" type="button">Cancel</button>
                        </div>
                      </div>

                    </form>
                    <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_tersangka" data-toggle="tab"> Tersangka </a>
                    </li>
                    <li>
                        <a href="#tab_barangbukti_narkotika" data-toggle="tab"> Barang Bukti Narkotika </a>
                    </li>
                    <li>
                        <a href="#tab_barangbukti_prekursor" data-toggle="tab"> Barang Bukti Prekursor </a>
                    </li>
                    <li>
                        <a href="#tab_barangbukti_aset" data-toggle="tab"> Barang Bukti Aset </a>
                    </li>
                    <li>
                        <a href="#tab_barangbukti_nonnarkotika" data-toggle="tab"> Barang Bukti Non Narkotika </a>
                    </li>
                </ul>
                <div class="tab-pane fade" id="tab_barangbukti_nonnarkotika">
            <div class="tools pull-right" style="margin-bottom:15px;">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <label class="btn btn-primary " data-toggle="modal" data-target="#myModal">
                        <input type="radio" name="options" class="toggle" id="option1">Tambah Barang Bukti Non Narkotika</label>
                </div>
            </div>
            <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Instansi</th>
                  <th>Periode/Kelompok Kasus</th>
                  <th>Nomor Kasus</th>
                  <th>Tersangka</th>
                  <th>Barang Bukti</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Nixon</td>
                  <td>System Architect</td>
                  <td>Edinburgh</td>
                  <td>61</td>
                  <td>2011/04/25</td>
                  <td class="actionTable">
                      <a href="{{route('detail_kasus')}}"  ><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Winters</td>
                  <td>Accountant</td>
                  <td>Tokyo</td>
                  <td>63</td>
                  <td>2011/07/25</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Cox</td>
                  <td>Junior Technical Author</td>
                  <td>San Francisco</td>
                  <td>66</td>
                  <td>2009/01/12</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>Kelly</td>
                  <td>Senior Javascript Developer</td>
                  <td>Edinburgh</td>
                  <td>22</td>
                  <td>2012/03/29</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>5</td>
                  <td>Satou</td>
                  <td>Accountant</td>
                  <td>Tokyo</td>
                  <td>33</td>
                  <td>2008/11/28</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>6</td>
                  <td>Williamson</td>
                  <td>Integration Specialist</td>
                  <td>New York</td>
                  <td>61</td>
                  <td>2012/12/02</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>7</td>
                  <td>Chandler</td>
                  <td>Sales Assistant</td>
                  <td>San Francisco</td>
                  <td>59</td>
                  <td>2012/08/06</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>8</td>
                  <td>Davidson</td>
                  <td>Integration Specialist</td>
                  <td>Tokyo</td>
                  <td>55</td>
                  <td>2010/10/14</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>9</td>
                  <td>Hurst</td>
                  <td>Javascript Developer</td>
                  <td>San Francisco</td>
                  <td>39</td>
                  <td>2009/09/15</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>
                <tr>
                  <td>10</td>
                  <td>Frost</td>
                  <td>Software Engineer</td>
                  <td>Edinburgh</td>
                  <td>23</td>
                  <td>2008/12/13</td>
                  <td class="actionTable">
                      <a href="detil1.html"><i class="fa fa-pencil f-18"></i></a>
                      <a href="#"><i class="fa fa-trash f-18"></i></a>
                  </td>
                </tr>

              </tbody>
            </table>
        </div>
        <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
                  </div>
                </div>

       </div>

</div>
@endsection
