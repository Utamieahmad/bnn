@extends('Layouts.layoutsGlobal')

@section('content')
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
                    </div>
                </div>
                <div class="row mainpage-menu">
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
                        <a class="" href="{{route('kasus')}}">
                            <div class="tile-stats">
                                <div class="col-sm-6 col-xs-6">
                                    <div class="icon">
                                        <i class="fa fa-shield c-yelow"></i>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="count">
                                        <h5>PEM BERAN TASAN</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
                        <a class="" href="#">
                            <div class="tile-stats">
                                <div class="col-sm-6 col-xs-6">
                                    <div class="icon">
                                        <i class="fa fa-heartbeat c-yelow"></i>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="count">
                                        <h5>REHA BILITASI</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
                        <a class="" href="#">
                            <div class="tile-stats">
                                <div class="col-sm-6 col-xs-6">
                                    <div class="icon">
                                        <i class="fa fa-ban c-yelow"></i>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="count">
                                        <h5>PEN CEGAHAN</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
                        <a class="" href="#">
                            <div class="tile-stats">
                                <div class="col-sm-6 col-xs-6">
                                    <div class="icon">
                                        <i class="fa fa-users c-yelow"></i>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="count">
                                        <h5>PEMBER DAYAAN MASYA RAKAT</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-5 col-sm-8 col-xs-12 p-0">
                        <div class="animated flipInY col-sm-6 col-xs-6">
                            <a class="" href="#">
                                <div class="tile-stats">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="icon m-center" style="top:20px !important;">
                                            <i class="fa fa-balance-scale" style="margin-left:-10px !important;"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="count text-center" style="top:68px !important;">
                                            <h5>HUKUM DAN KERJASAMA</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="animated flipInY col-sm-6 col-xs-6">
                            <a class="" href="#">
                                <div class="tile-stats">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="icon m-center" style="top:20px !important;">
                                            <i class="fa fa-search" style="margin-left:-10px !important;"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="count text-center" style="top:68px !important;">
                                            <h5>INSPEKTORAT UTAMA</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-7 col-sm-8 col-xs-12 p-0">
                        <div class="animated flipInY col-sm-6 col-xs-6">
                            <a class="" href="#">
                                <div class="tile-stats">
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="icon">
                                            <i class="fa fa-leanpub"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="count">
                                            <h5>BALAI DIKLAT</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="animated flipInY col-sm-6 col-xs-6">
                            <a href="#">
                                <div class="tile-stats">
                                    <div class="col-sm-5 col-xs-6">
                                        <div class="icon">
                                            <i class="fa fa-flask"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-xs-6">
                                        <div class="count">
                                            <h5>BALAI LABORATORIUM NARKOBA</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="animated flipInY col-md-3 col-sm-4 col-xs-6">
                        <a href="#">
                            <div class="tile-stats">
                                <div class="col-sm-6 col-xs-6">
                                    <div class="icon">
                                        <i class="fa fa-microchip"></i>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="count">
                                        <h5>PUSLIT DATIN</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="animated flipInY col-md-4 col-sm-4 col-xs-6">
                        <a href="#">
                            <div class="tile-stats">
                                <div class="col-sm-5 col-xs-6">
                                    <div class="icon">
                                        <i class="fa fa-id-badge"></i>
                                    </div>
                                </div>
                                <div class="col-sm-7 col-xs-6">
                                    <div class="count">
                                        <h5>ARAHAN KEPALA BNN</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-5 col-sm-8 col-xs-12 p-0">
                        <div class="animated flipInY col-sm-6 col-xs-6">
                            <a href="index.html">
                                <div class="tile-stats">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="icon m-center" style="top:20px !important;">
                                            <i class="fa fa-home" style="margin-left:-10px !important;"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="count text-center" style="top:68px !important;">
                                            <h5>KEMBALI KE BERANDA</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="animated flipInY col-sm-6 col-xs-6">
                            <a href="login.html">
                                <div class="tile-stats">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="icon m-center" style="top:20px !important;">
                                            <i class="fa fa-power-off" style="margin-left:-10px !important;"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="count text-center" style="top:68px !important;">
                                            <h5>KELUAR APLIKASI</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
@endsection
