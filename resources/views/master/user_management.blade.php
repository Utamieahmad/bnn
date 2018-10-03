@extends('layouts.base_layout')

@section('content')
	<div class="right_col" role="main">
          <div class="m-t-40">
            <div class="page-title">
              <div class="title_left">
                  <h3>MANAJEMEN <small> <strong>PENGGUNA</strong></small></h3>
              </div>
              <div class="title_right">
                  <ul class="page-breadcrumb breadcrumb pull-right">
                      <li>
                          Beranda
                      </li>
                      <li>
                          <a href="javascript:;" class="c-grey">Pengaturan</a>
                      </li>
                      <li class="active">
                          Manajemen Pengguna
                      </li>
                  </ul>
                <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title" style="border-bottom:none;">
                    <div class="flex">
                      <ul class="list-inline widget_profile_box">
                        <li>
                          <img src="images/sin-bnn/BNN-LOGO-BIG.png" alt="..." class="img-circle profile_img">
                        </li>
                        <li>
                          <a>
                            <i class="fa fa-users"></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content ">
                      <ul class="nav navbar-right panel_toolbox">
                        <li class="">
                            <a href="{{url('settings/user_role')}}" class="btn btn-lg btn-round btn-danger" >
                                <i class="fa fa-user-circle"></i> Pengaturan Role
                            </a>
                        </li>
                        <li class="">
                            <a href="#" class="btn btn-lg btn-round btn-primary" type="button" data-toggle="modal" data-target=".bs-tambah-user">
                                <i class="fa fa-plus-circle c-yelow"></i> Tambah Pengguna
                            </a>
                        </li>
                    </ul>
                     <div class="col-md-12 col-sm-12 col-xs-12 p-t-10 p-b-20">
                         <div class="" >

                             <div class="">
                                 <h4>Daftar Pengguna</h4>
                             </div>

                             <table class="table table-hover table-condensed table-striped" style="background:rgba(0,0,0,.1);">
                                 <thead class="c-yelow" style="background:#19396D">
                                    <tr>
                                        <th style="vertical-align: middle">No</th>
                                        <th style="vertical-align: middle">Nama</th>
                                        <th style="vertical-align: middle">Email</th>
                                        <th style="vertical-align: middle">Status</th>
                                        <th style="vertical-align: middle">Role</th>
                                        <th style="vertical-align: middle">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                        <td>001</td>
                                        <td>Dimas</td>
                                        <td>nugroho@bnn.go.id</td>
                                        <td>
                                            <form class="form-horizontal form-label-left">
                                                <div class="">
                                                    <label class="m-b-0">
                                                        <input type="checkbox" class="js-switch" checked />
                                                    </label>
                                                </div>
                                            </form>
                                        </td>
                                        <td>Admin</td>
                                        <td class="actionTable">
                                          <a href="#" type="button" data-toggle="modal" data-target=".bs-edit-user"><i class="fa fa-pencil f-18"></i></a>
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>002</td>
                                        <td>Dimas</td>
                                        <td>nugroho@bnn.go.id</td>
                                        <td>
                                            <form class="form-horizontal form-label-left">
                                                <div class="">
                                                    <label class="m-b-0">
                                                        <input type="checkbox" class="js-switch" />
                                                    </label>
                                                </div>
                                            </form>
                                        </td>
                                        <td>Contributor</td>
                                        <td class="actionTable">
                                          <a href="#" type="button" data-toggle="modal" data-target=".bs-edit-user"><i class="fa fa-pencil f-18"></i></a>
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>004</td>
                                        <td>Dimas</td>
                                        <td>nugroho@bnn.go.id</td>
                                        <td>
                                            <form class="form-horizontal form-label-left">
                                                <div class="">
                                                    <label class="m-b-0">
                                                        <input type="checkbox" class="js-switch" checked />
                                                    </label>
                                                </div>
                                            </form>
                                        </td>
                                        <td>Admin</td>
                                        <td class="actionTable">
                                          <a href="#" type="button" data-toggle="modal" data-target=".bs-edit-user"><i class="fa fa-pencil f-18"></i></a>
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>005</td>
                                        <td>Dimas</td>
                                        <td>nugroho@bnn.go.id</td>
                                        <td>
                                            <form class="form-horizontal form-label-left">
                                                <div class="">
                                                    <label class="m-b-0">
                                                        <input type="checkbox" class="js-switch" checked />
                                                    </label>
                                                </div>
                                            </form>
                                        </td>
                                        <td>Admin</td>
                                        <td class="actionTable">
                                          <a href="#" type="button" data-toggle="modal" data-target=".bs-edit-user"><i class="fa fa-pencil f-18"></i></a>
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
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
  
@endsection
