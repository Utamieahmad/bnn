@extends('layouts.base_layout')
@section('title', 'Sistem Informasi Narkoba BNN - Copyright © 2017 | Home')

@section('content')
	<div class="right_col" role="main">
	    <div class="">
	        <div class="page-title">
	            <div class="title_left">
	                <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
	            </div>
	        </div>
						@php $menu = Session::get("menu"); @endphp
	        <div class="row mainpage-menu">
	           <!--  <div class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <a class="" href="">
	                    <div class="tile-stats">
	                        <div class="col-sm-4 col-xs-4">
	                            <div class="icon">
	                                <i class="fa fa-shield c-yelow"></i>
	                            </div>
	                        </div>
	                        <div class="col-sm-8 col-xs-8">
	                            <div class="count">
	                                <a href="{{url('pemberantasan/dir_narkotika/pendataan_lkn')}}"><h5>PEMBERANTASAN</h5></a>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div> -->

	            <?php
	            // print_r($menu);
	            // exit();
	            ?>

	            <div @php if(!in_array(111, $menu))  echo 'style="display:none;"'; @endphp class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <div class="animated flipInY">
												<a href="#" class="" data-toggle="modal" data-target="#modal_home_settama">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:30px !important; width: 50px;">
	                                	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/sekretariat_utama.png')}}" class="img-responsive m-l-10"></span>

	                                    <!-- <i class="fa fa-search" style="margin-left:-10px !important;"></i> -->
	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="count text-center" style="top:68px !important;">
	                                    <h5>SEKRETARIAT UTAMA</h5>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div>
	            </div>

	            <div @php if(!in_array(7, $menu))  echo 'style="display:none;"'; @endphp class=" col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <div class="animated flipInY ">
												<a href="#" class="" data-toggle="modal" data-target="#modal_home_irtama">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:20px !important; width: 50px;">
	                                	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/inspektorat_utama.png')}}" class="img-responsive m-l-10"></span>

	                                    <!-- <i class="fa fa-search" style="margin-left:-10px !important;"></i> -->
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



	            <div @php if(!in_array(2, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
										<a href="#" class="" data-toggle="modal" data-target="#modal_home_berantas">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:20px !important; width: 50px;">
	                            	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/pemberantasan.png')}}" class="img-responsive m-l-10"></span>
	                                <!-- <i class="fa fa-shield"></i> -->
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>PEMBERANTASAN</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(3, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
									<a href="#" class="" data-toggle="modal" data-target="#modal_home_rehab">
										<div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:20px !important; width: 50px;">
	                            	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/rehabilitasi.png')}}" class="img-responsive m-l-10"></span>
	                                <!-- <i class="fa fa-heartbeat c-grey"></i> -->
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>REHABILITASI</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(4, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <a href="#" class="" data-toggle="modal" data-target="#modal_home_cegah">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:20px !important; width: 50px;">
	                            	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/pencegahan.png')}}" class="img-responsive m-l-10"></span>

	                                <!-- <i class="fa fa-ban c-grey"></i> -->
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>PENCEGAHAN</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(5, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <a href="#" class="" data-toggle="modal" data-target="#modal_home_dayamas">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:20px !important; width: 50px;">
	                            	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/pemberdayaan_masyarakat.png')}}" class="img-responsive m-l-10"></span>
	                                <!-- <i class="fa fa-users c-grey"></i> -->
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>PEMBERDAYAAN MASYARAKAT</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(6, $menu))  echo 'style="display:none;"'; @endphp class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <div class="animated flipInY">
	                    <a href="#" class="" data-toggle="modal" data-target="#modal_home_huker">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:20px !important; width: 50px;">
	                                	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/hukum_kerjasama.png')}}" class="img-responsive m-l-10"></span>

	                                    <!-- <i class="fa fa-balance-scale" style="margin-left:-10px !important;"></i> -->
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
	            </div>

	            <div @php if(!in_array(109, $menu))  echo 'style="display:none;"'; @endphp class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <div class="animated flipInY">
	                    <a class="" href="{{url('/balai_besar/magang/data_magang')}}">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:20px !important; width: 50px;">
	                                	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/balai_besar.png')}}" class="img-responsive m-l-10"></span>
	                                    <!-- <i class="fa fa-leanpub"></i> -->
	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="count text-center" style="top:68px !important;">
	                                    <h5>BALAI BESAR</h5>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div>
	            </div>

	            <div @php if(!in_array(8, $menu))  echo 'style="display:none;"'; @endphp class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                <div class="animated flipInY ">
	                    <a class="" href="{{url('/balai_diklat/pendidikan/pendidikan_pelatihan')}}">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:20px !important; width: 50px;">
	                                	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/balai_diklat.png')}}" class="img-responsive m-l-10"></span>
	                                    <!-- <i class="fa fa-leanpub"></i> -->
	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="count text-center" style="top:68px !important;">
	                                    <h5>BALAI DIKLAT</h5>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div>
	            </div>

	                <div @php if(!in_array(9, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                    <a href="{{url('/balai_lab/pengujian/berkas_sampel')}}">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:15px !important; width: 50px;">
	                                	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/balai_laboratorium.png')}}" class="img-responsive m-l-10"></span>
	                                    <!-- <i class="fa fa-flask"></i> -->
	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="count text-center" style="top:68px !important;">
	                                    <h5>BALAI LABORATORIUM NARKOBA</h5>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div>



	            <div @php if(!in_array(10, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-md-3 col-sm-4 col-xs-6">
	                <a href="#" class="" data-toggle="modal" data-target="#modal_home_datin">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:20px !important; width: 50px;">
	                            	<span><img alt="Logo SIN-BNN" src="{{asset('assets/icon/puslitdatin.png')}}" class="img-responsive m-l-10"></span>
	                                <!-- <i class="fa fa-microchip"></i> -->
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>PUSLITDATIN</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(11, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-md-3 col-sm-4 col-xs-6">
	                <a href="{{url('/arahan/pimpinan/arahan_pimpinan')}}">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:30px !important; width: 35px;">
	                            	<span><img alt="Logo SIN-BNN" style="margin-top: -20px !important;" src="{{asset('assets/icon/arahan_kepala_bnn.png')}}" class="img-responsive m-l-10"></span>
	                                <!-- <i class="fa fa-id-badge"></i> -->
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>ARAHAN PIMPINAN</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(130, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-md-3 col-sm-4 col-xs-6">
	                <a href="#" class="" data-toggle="modal" data-target="#modal_home_user">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:30px !important; width: 35px;">
	                            	<i class="fa fa-home" style="margin-top: -20px !important;"></i>
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>USER MANAGEMENT</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div @php if(!in_array(131, $menu))  echo 'style="display:none;"'; @endphp class="animated flipInY col-md-3 col-sm-4 col-xs-6">
	                <a href="#" class="" data-toggle="modal" data-target="#modal_home_master">
	                    <div class="tile-stats">
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="icon m-center" style="top:30px !important; width: 35px;">
	                            	<i class="fa fa-home" style="margin-top: -20px !important;"></i>
	                            </div>
	                        </div>
	                        <div class="col-sm-12 col-xs-12">
	                            <div class="count text-center" style="top:68px !important;">
	                                <h5>MASTER DATA</h5>
	                            </div>
	                        </div>
	                    </div>
	                </a>
	            </div>

	            <div class="col-md-6 col-sm-8 col-xs-12 p-0">

	                <!-- <div class="animated flipInY col-sm-6 col-xs-6">
	                    <a href="{{url('customer/customer_service')}}">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:20px !important;">
	                                    <i class="fa fa-home" style="margin-left:-10px !important;"></i>
	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="count text-center" style="top:68px !important;">
	                                    <h5>CUSTOMER SERVICE</h5>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div>

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
	                                    <h5>PUSAT INFORMASI</h5>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div> -->

	                <div class="animated flipInY col-sm-6 col-xs-6">
	                    <a href="{{url('logout')}}">
	                        <div class="tile-stats">
	                            <div class="col-sm-12 col-xs-12">
	                                <div class="icon m-center" style="top:20px !important;">
	                                    <i class="fa fa-power-off" style="margin-left:-5px !important;"></i>
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


	@include('modal.modal_home_settama')
	@include('modal.modal_home_irtama')
	@include('modal.modal_home_berantas')
	@include('modal.modal_home_rehab')
	@include('modal.modal_home_cegah')
	@include('modal.modal_home_dayamas')
	@include('modal.modal_home_huker')
	@include('modal.modal_home_datin')
	@include('modal.modal_home_user')
	@include('modal.modal_home_master')
@endsection
