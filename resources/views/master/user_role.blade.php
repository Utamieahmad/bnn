@extends('layouts.base_layout')

@section('content')
	<div class="right_col" role="main">
          <div class="m-t-40">
            <div class="page-title">
              <div class="title_left">
                  <h3>PENGATURAN <small> <strong>ROLE PENGGUNA</strong></small></h3>
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
                          Role Pengguna
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
                            <a href="{{url('settings/user_management')}}" class="btn btn-lg btn-round btn-dark" >
                                <i class="fa fa-chevron-left c-yelow"></i> List Pengguna
                            </a>
                        </li>
                        <li class="">
                            <a href="#" class="btn btn-lg btn-round btn-primary" type="button" data-toggle="modal" data-target=".bs-tambah-role">
                                <i class="fa fa-plus-circle c-yelow"></i> Tambah Role
                            </a>
                        </li>
                    </ul>
                     <div class="col-md-12 col-sm-12 col-xs-12 p-t-10 p-b-20">
                         <div class="table-responsive" >

                             <div class="">
                                 <h4>Manajemen Role Pengguna</h4>
                             </div>

                             <table class="table table-hover table-condensed table-striped" style="background:rgba(0,0,0,.1);">
                                 <thead class="f-10 c-yelow" style="background:#19396D">
                                    <tr>
                                        <th rowspan="3" style="vertical-align: middle" class="text-center">No</th>
                                        <th rowspan="3" style="vertical-align: middle" class="text-center">Role Name</th>
                                        <th colspan="3" style="vertical-align: middle" class="">Manajemen pengguna</th>
                                        <th colspan="9" style="vertical-align: middle" class="">Pemberantasan</th>
                                        <th colspan="18" style="vertical-align: middle" class="">Rehabilitasi</th>
                                        <th rowspan="3" style="vertical-align: middle" class="text-center">Action</th>
                                    </tr>
                                    <tr>
                                        <th class="" rowspan="2" style="vertical-align: middle">Edit</th>
                                        <th class="" rowspan="2" style="vertical-align: middle">Tambah</th>
                                        <th class="" rowspan="2" style="vertical-align: middle">Hapus</th>

                                        <th colspan="2" style="vertical-align: middle">Direktorat Narkotika</th>
                                        <th colspan="1" style="vertical-align: middle">Direktorat Psikotropika</th>
                                        <th colspan="1" style="vertical-align: middle">Direktorat TPPU</th>
                                        <th colspan="1" style="vertical-align: middle">Direktorat Intelijen</th>
                                        <th colspan="2" style="vertical-align: middle">Direktorat Wastahti</th>
                                        <th colspan="1" style="vertical-align: middle">Direktorat Penindakan</th>
                                        <th colspan="1" style="vertical-align: middle">Direktorat Interdiksi</th>

                                        <th rowspan="2" class="" style="vertical-align: middle">Data Sirena</th>
                                        <th colspan="5" class="" style="vertical-align: middle">Direktorat PLRIP</th>
                                        <th colspan="5" class="" style="vertical-align: middle">Direktorat PLRKM</th>
                                        <th colspan="7" class="" style="vertical-align: middle">Direktorat Pascarehabilitasi</th>
                                    </tr>
                                        <th style="vertical-align: middle">Data LKN</th>
                                        <th style="vertical-align: middle">Pemusnahan Ladang Ganja</th>
                                        <th style="vertical-align: middle">Data LKN</th>
                                        <th style="vertical-align: middle">Data TPPU</th>
                                        <th style="vertical-align: middle">Data Jaringan Narkoba</th>
                                        <th style="vertical-align: middle">Data Pemusnahan Barang</th>
                                        <th style="vertical-align: middle">Data Tahanan di BNN &amp; BNNP</th>
                                        <th style="vertical-align: middle">DPO</th>
                                        <th style="vertical-align: middle">DPO</th>

                                        <th style="vertical-align: middle" class="">Informasi umum</th>
                                        <th style="vertical-align: middle" class="">Klien yg di klaim</th>
                                        <th style="vertical-align: middle" class="">Dokumen NSPK</th>
                                        <th style="vertical-align: middle" class="">Kegiatan pelatihan</th>
                                        <th style="vertical-align: middle" class="">Penilaian Lembaga</th>

                                        <th style="vertical-align: middle" class="">Informasi umum</th>
                                        <th style="vertical-align: middle" class="">Klien yg di klaim</th>
                                        <th style="vertical-align: middle" class="">Dokumen NSPK</th>
                                        <th style="vertical-align: middle" class="">Kegiatan pelatihan</th>
                                        <th style="vertical-align: middle" class="">Penilaian Lembaga</th>

                                        <th style="vertical-align: middle" class="">Data klien rawat jalan</th>
                                        <th style="vertical-align: middle" class="">Data klien rawat lanjut</th>
                                        <th style="vertical-align: middle" class="">Data klien rawat inap</th>
                                        <th style="vertical-align: middle" class="">Informasi umum lembaga</th>
                                        <th style="vertical-align: middle" class="">Dokumen NSPK</th>
                                        <th style="vertical-align: middle" class="">Kegiatan pelatihan</th>
                                        <th style="vertical-align: middle" class="">Penilaian Lembaga</th>
                                    <tr>

                                    </tr>

                                 </thead>
                                 <tbody class="f-10" >
                                    <tr>
                                        <td style="vertical-align: middle">001</td>
                                        <td style="vertical-align: middle">Admin</td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>

                                        <td style="vertical-align: middle" class="text-center actionTable">
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="vertical-align: middle">002</td>
                                        <td style="vertical-align: middle">Editor</td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>

                                        <td style="vertical-align: middle" class="text-center actionTable">
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="vertical-align: middle">003</td>
                                        <td style="vertical-align: middle">Kontributor</td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" checked>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" >
                                                </div>
                                            </div>
                                        </td>

                                        <td style="vertical-align: middle" class="text-center actionTable">
                                          <a data-toggle="modal" data-target=".hapus-modal" href="#"><i class="fa fa-trash f-18"></i></a>
                                        </td>
                                    </tr>

                                 </tbody>
                             </table>

             <!-- <form action="{{URL('/huker/dir_kerjasama/input_kerjasama_lainnya')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Menu</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="mt-repeater">
                                <div data-repeater-list="meta_peserta">
                                    <div data-repeater-item class="mt-repeater-item">
                                        <div class="row mt-repeater-row">
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <label class="control-label">Direktorat Narkotika</label>
                                                <input name="meta_peserta[][list_nama_instansi]" type="text" class="form-control"> </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <label class="control-label">Data LKN</label>
                                                <input name="meta_peserta[][list_alamat_instansi]" type="text" class="form-control"> </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <label class="control-label">Jumlah Peserta</label>
                                                <input name="meta_peserta[][list_jumlah_peserta]" type="text" class="form-control numeric" onkeydown="numeric(event)"> </div>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                    <i class="fa fa-plus"></i> Tambah Peserta</a>
                            </div>
                        </div>
                    </div>

                </div>
            </form> -->
                         </div>
                     </div>

                    <div class="row" style="margin-bottom:30px;">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-4 col-sm-offset-4 col-xs-offset-2">
                              <a class="btn btn-lg btn-primary btn-round" type="button" href="detil1.html">Batal</a>
                              <!--<button class="btn btn-primary btn-round" type="reset">Reset</button>-->
                              <button type="submit" class="btn btn-lg btn-success btn-round p-l-32 p-r-32">Simpan <i class="fa fa-save"></i></button>
                            </div>
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
