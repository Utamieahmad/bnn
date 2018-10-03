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
                    <li class="active">
                      Pendataan tahanan di BNN dan BNNP
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
                  <h2>Data Kasus</h2>

                  <ul class="nav navbar-right panel_toolbox">
                      <li class="">
                          <a href="#" class="btn btn-lg btn-round btn-danger">
                              <i class="fa fa-plus-circle"></i> Input Nihil
                          </a>
                      </li>
                      <li class="">
                          <a href="{{route('Wastahti_Pendataan_Tahanan_tambah')}}" class="btn btn-lg btn-round btn-primary">
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
                        <th>Jenis tahanan</th>
                        <th>No.Kasus</th>
                        <th>Nama Tahanan</th>
                        <th>Alamat Tahanan</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>TAHANAN_BNN</td>
                        <td>67888</td>
                        <td>Edinburgh</td>
                        <td>te</td>

                        <td class="actionTable">
                            <a href="{{route('Wastahti_Pendataan_Tahanan_detail')}}"  ><i class="fa fa-pencil f-18"></i></a>
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
