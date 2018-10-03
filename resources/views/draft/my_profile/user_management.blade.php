@extends('Layouts.layoutsGlobal')

@section('content')
<div class="right_col" role="main">
        <div class="m-t-40">
          <div class="page-title">
            <div class="">
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        System
                    </li>
                    <li>
                        User Management
                    </li>
                    <li class="active">
                        Data
                    </li>
                </ul>
              <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Data <small></small></h2>
                  <ul class="nav navbar-right panel_toolbox">

                      <li class="">
                          <a href="{{route('user_management_add')}}" class="btn btn-lg btn-round btn-primary">
                              <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                          </a>
                      </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content ">

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
              </div>
            </div>
          </div>



        </div>
      </div>
@endsection
