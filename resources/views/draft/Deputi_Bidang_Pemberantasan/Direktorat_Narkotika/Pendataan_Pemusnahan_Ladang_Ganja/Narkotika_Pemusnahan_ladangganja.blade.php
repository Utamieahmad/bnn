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
                        Direktorat Narkotika
                    </li>
                    <li class="active">
                        Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)
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
                  <h2>Pendataan Pemusnahan Ladang Ganja</h2>

                  <ul class="nav navbar-right panel_toolbox">
                      <li class="">
                          <a href="#" class="btn btn-lg btn-round btn-danger">
                              <i class="fa fa-plus-circle"></i> Input Nihil
                          </a>
                      </li>
                      <li class="">
                          <a href="{{route('Narkotika_Pemusnahan_ladangganja_tambah')}}" class="btn btn-lg btn-round btn-primary">
                              <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                          </a>
                      </li>
                  </ul>

                  <div class="clearfix"></div>
                </div>
                <div class="ln_solid"></div>

                <div class="x_content ">

                  <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nomor Sprint</th>
                        <th>Tanggal Penyidik</th>
                        <th>luas Lahan Ganja</th>
                        <th>Luas lahan Dimusnahkan</th>
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
                        <td class="actionTable">
                            <a href="{{route('Narkotika_Pemusnahan_ladangganja_detail')}}"  ><i class="fa fa-pencil f-18"></i></a>
                            <a href="#"><i class="fa fa-trash f-18"></i></a>
                        </td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td class="actionTable">
                            <a href="{{route('Narkotika_Pemusnahan_ladangganja_detail')}}"><i class="fa fa-pencil f-18"></i></a>
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
