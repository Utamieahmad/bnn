 <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Sistem Informasi Narkoba | BNN </title>

    <!-- Bootstrap -->
     <link href="{{asset('assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="{{asset('assets/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('assets/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="{{asset('assets/google-code-prettify/bin/prettify.min.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{asset('assets/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <!-- Switchery -->
    <link href="{{asset('assets/switchery/dist/switchery.min.css') }}" rel="stylesheet">
    <!-- starrr -->
    <link href="{{asset('assets/starrr/dist/starrr.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{asset('assets/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="{{asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{asset('assets/css/custom.min.css') }}" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title">
                        <span><img alt="Logo SIN-BNN" src="images/BNN-LOGO-full.png" class="img-responsive"></span>
                        <!--<i class="fa fa-paw"></i>-->
                        <i class="logo-bnn"></i>
                    </a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="images/img.jpg" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>BNN Admin</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li><a data-toggle="tooltip" data-placement="right" title="Beranda" href="javascript:;"><i class="fa fa-home"></i><span class="sm-side"> Beranda </span><span class=""></span></a>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Pemberantasan" href="javascript:;"><i class="fa fa-shield"></i><span class="sm-side"> Pemberantasan </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="#">Direktorat Narkotika<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{route('kasus')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
                                            <li><a href="{{route('Narkotika_Pemusnahan_ladangganja')}}">Pendataan Pemusnahan Ladang Ganja</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat Psikotropika dan Prekursor<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{route('kasus')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat TPPU<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{route('kasus')}}">Pendataan TPPU</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat Intelijen<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{route('Intel_Jaringan_Narkotika')}}">Pendataan Jaringan Narkoba yang Sudah Diungkap</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat Wastahti<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{route('Wastahti_Pemusnahan_Barangbukti')}}">Pendataan Pemusnahan Barang Bukti</a></li>
                                            <li><a href="{{route('Wastahti_Pendataan_Tahanan')}}">Pendataan tahanan di BNN dan BNNP</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat Penindakan dan Pengejaran<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="data_tppu.html">Input Daftar Pencarian Orang (DPO)</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat Interdiksi<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="data_tppu.html">Input Daftar Pencarian Orang (DPO)</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Rehabilitasi" href="javascript:;"><i class="fa fa-heartbeat"></i><span class="sm-side"> Rehabilitasi </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="sirena.html">Data Pasien Aplikasi Sirena</a></li>
                                    <li><a href="#">Direktorat PLRIP<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="sirena.html">Informasi Umum Lembaga Rehabilitasi Instansi Pemerintah</a></li>
                                            <li><a href="sirena.html">Klien yang di klaim</a></li>
                                            <li><a href="sirena.html">Dokumen NSPK</a></li>
                                            <li><a href="sirena.html">Kegiatan Pelatihan</a></li>
                                            <li><a href="sirena.html">Penilaian Lembaga</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat PLRKM<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="sirena.html">Informasi Umum Lembaga Rehabilitasi Komponen Masyarakat</a></li>
                                            <li><a href="sirena.html">Klien yang di klaim</a></li>
                                            <li><a href="sirena.html">Dokumen NSPK</a></li>
                                            <li><a href="sirena.html">Kegiatan Pelatihan</a></li>
                                            <li><a href="sirena.html">Penilaian Lembaga</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Direktorat Pascarehabilitasi<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="sirena.html">Data Klien Pascarehabilitasi Rawat Jalan</a></li>
                                            <li><a href="sirena.html">Data Klien Pascarehabilitasi Rawat Lanjut</a></li>
                                            <li><a href="sirena.html">Data Klien Pascarehabilitasi Rawat Inap</a></li>
                                            <li><a href="sirena.html">Informasi Umum Lembaga</a></li>
                                            <li><a href="sirena.html">Dokumen NSPK</a></li>
                                            <li><a href="sirena.html">Kegiatan Pelatihan</a></li>
                                            <li><a href="sirena.html">Penilaian Lembaga</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Pencegahan" href="javascript:;"><i class="fa fa-ban"></i><span class="sm-side"> Pencegahan </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li><a href="#">Direktorat Advokasi<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Kegiatan Rapat Koordinasi</a></li>
                                          <li><a href="#">Kegiatan Membangun Jejaring</a></li>
                                          <li><a href="#">Kegiatan Asistensi</a></li>
                                          <li><a href="#">Kegiatan Penguatan Asistensi</a></li>
                                          <li><a href="#">Kegiatan Intervensi</a></li>
                                          <li><a href="#">Kegiatan Supervisi</a></li>
                                          <li><a href="#">Kegiatan Monitoring dan Evaluasi</a></li>
                                          <li><a href="#">Kegiatan Bimbingan Teknis</a></li>
                                          <li><a href="#">Kegiatan Sosialisasi</a></li>
                                      </ul>
                                  </li>
                                  <li><a href="#">Direktorat Diseminasi Informasi<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Media Online</a></li>
                                          <li><a href="#">Media Penyiaran</a></li>
                                          <li><a href="#">Media Cetak</a></li>
                                          <li><a href="#">Media Konvensional</a></li>
                                          <li><a href="#">Videotron</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Pemberdayaan Masyarakat" href="javascript:;"><i class="fa fa-users"></i><span class="sm-side"> Pemberdayaan Masyarakat </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li><a href="#">Direktorat Peran Serta Masyarakat<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Tes Narkoba</a></li>
                                          <li><a href="#">Penggiat Anti Narkotika</a></li>
                                          <li><a href="#">Pelatihan Penggiat Anti Narkotika</a></li>
                                          <li><a href="#">Pengembangan Kapasitas</a></li>
                                          <li><a href="#">Supervisi, Implementasi dan Renaksi P4GN</a></li>
                                          <li><a href="#">Pendataan Ormas/LSM Anti Narkoba</a></li>
                                      </ul>
                                  </li>
                                  <li><a href="#">Direktorat Alternative Development<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Alih Fungsi Lahan Ganja</a></li>
                                          <li><a href="#">Alih Jenis Profesi/Usaha</a></li>
                                          <li><a href="#">Kawasan Rawan Narkoba</a></li>
                                          <li><a href="#">Monitoring dan Evaluasi Kawasan Rawan Narkotika</a></li>
                                          <li><a href="#">Sinergitas</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Hukum dan Kerjasama" href="javascript:;"><i class="fa fa-balance-scale"></i><span class="sm-side"> Hukum dan Kerjasama </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li><a href="#">Direktorat Hukum<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Rakor dan Audiensi</a></li>
                                          <li><a href="#">Pembelaan Hukum (Pendampingan)</a></li>
                                          <li><a href="#">Pembelaan Hukum (Pra Peradilan)</a></li>
                                          <li><a href="#">Konsultasi Hukum (Non Litigasi)</a></li>
                                          <li><a href="#">Pembentukan Perka BNN</a></li>
                                          <li><a href="#">Sosialisasi Peraturan Perundang-undangan</a></li>
                                          <li><a href="#">Monev Peraturan Perundang-undangan</a></li>
                                      </ul>
                                  </li>
                                  <li><a href="#">Direktorat Kerja Sama<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Kerjasama Luar Negeri</a></li>
                                          <li><a href="#">Perjanjian Bilateral</a></li>
                                          <li><a href="#">Nota Kesepemahaman</a></li>
                                          <li><a href="#">Sosialisasi</a></li>
                                          <li><a href="#">Monev Hasil Pelaksanaan Kerjasama</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Inspektorat Utama" href="javascript:;"><i class="fa fa-search"></i><span class="sm-side"> Inspektorat Utama </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="#">Riktu/Riksus</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Balai Diklat" href="javascript:;"><i class="fa fa-leanpub"></i><span class="sm-side"> Balai Diklat </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="#">Pendidikan dan Pelatihan</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Balai Laboratorium Narkoba" href="javascript:;"><i class="fa fa-flask"></i><span class="sm-side"> Balai Laboratorium Narkoba </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="#">Pengujian Bahan Sediaan, Spesimen Biologi dan Toksikologi</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Puslitdatin" href="javascript:;"><i class="fa fa-microchip"></i><span class="sm-side"> Puslitdatin </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li><a href="#">Bidang Litbang<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Survey Nasional Penyalahgunaan Narkoba di Indonesia</a></li>
                                          <li><a href="#">Penyalah Guna Narkoba Coba Pakai</a></li>
                                          <li><a href="#">Penyalah Guna Narkoba Teratur Pakai</a></li>
                                          <li><a href="#">Penyalah Guna Narkoba Pecandu Suntik</a></li>
                                          <li><a href="#">Penyalah Guna Narkoba Pecandu Non Suntik</a></li>
                                          <li><a href="#">Penyalah Guna Narkoba Setahun Pakai</a></li>
                                          <li><a href="#">Permintaan Data Hasil Penelitian BNN</a></li>
                                          <li><a href="#">Riset Operasional Penyalahgunaan Narkoba di Indonesia</a></li>
                                      </ul>
                                  </li>
                                  <li><a href="#">Bidang TIK<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li><a href="#">Informasi Masuk Melalui Contact Center</a></li>
                                          <li><a href="#">Tindak Lanjut Contact Center BNN Pusat</a></li>
                                          <li><a href="#">Tindak Lanjut Contact Center BNNP/BNNK</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li><a data-toggle="tooltip" data-placement="right" title="Arahan Kepala BNN" href="javascript:;"><i class="fa fa-id-badge"></i><span class="sm-side"> Arahan Kepala BNN </span><span class=""></span></a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Keluar Aplikasi" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu nav1">
                <nav>


                    <ul class="nav navbar-nav navbar-right">
                        <!--<li class="">-->
                        <!--<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">-->
                        <!--<img src="images/img.jpg" alt="">John Doe-->
                        <!--<span class=" fa fa-angle-down"></span>-->
                        <!--</a>-->
                        <!--<ul class="dropdown-menu dropdown-usermenu pull-right">-->
                        <!--<li><a href="javascript:;"> Profile</a></li>-->
                        <!--<li>-->
                        <!--<a href="javascript:;">-->
                        <!--<span class="badge bg-red pull-right">50%</span>-->
                        <!--<span>Settings</span>-->
                        <!--</a>-->
                        <!--</li>-->
                        <!--<li><a href="javascript:;">Help</a></li>-->
                        <!--<li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>-->
                        <!--</ul>-->
                        <!--</li>-->
                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cog"></i>
                                <!--<span class="badge bg-green">6</span>-->
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">

                                <li><a href="javascript:;"> <span class="glyphicon glyphicon-home" aria-hidden="true"></span>   Beranda </a></li>
                                <li><a href="{{route('profile')}}"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    My Profile </a></li>
                                <li><a href="javascript:;"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Monitoring Nihil </a></li>
                                <li><a href="{{route('user_management')}}"> <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>    User Management </a></li>
                                <li><a href="javascript:;"> <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>    Instansi Management </a></li>
                                <li><a href="javascript:;"> <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>    Log Out </a></li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user-circle"></i>
                                <span class="badge bg-blue-light">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="nav_menu nav2">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <div class="nav navbar-nav navbar-right">
                        <div class="title_right">
                            <h3 class="pull-right m-r-20 f-20 m-t-14">BERANDA <i class="fa fa-home"></i></h3>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->


                    @yield('content')

        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="text-center">
                Sistem Informasi Narkoba | Badan Narkotika Nasional - Copyright © 2017
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('assets/jquery/dist/jquery.min.js') }}"> </script>
<!-- Bootstrap -->
<script src="{{asset('assets/bootstrap/dist/js/bootstrap.min.js') }}"> </script>
<!-- FastClick -->
<script src="{{asset('assets/fastclick/lib/fastclick.js') }}"> </script>
<!-- NProgress -->
<script src="{{asset('assets/nprogress/nprogress.js') }}"> </script>
<!-- jQuery custom content scroller -->
<script src="{{asset('assets/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"> </script>

<!-- Custom Theme Scripts -->
<script src="{{asset('assets/js/custom.min.js') }}"> </script>
</body>
</html>
