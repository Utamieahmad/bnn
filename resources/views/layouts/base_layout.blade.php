<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link href="{{asset('assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{asset('assets/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/bootstrap-fileinput.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/bootstrap-fileinput.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/green.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/custom.css') }}" rel="stylesheet">
        <link href="{{asset('assets/css/customs.css') }}" rel="stylesheet">
        <!-- <link href="{{asset('/assets/css/jquery.dataTables.min.css')}}" rel="stylesheet"/> -->
        <!-- <link href="{{asset('/assets/css/datatables.min.css')}}" rel="stylesheet"/> -->
        <!-- <link href="{{asset('/assets/DataTables-1.10.16/css/jquery.dataTables.min.css')}}" rel="stylesheet"/> -->
        <!-- <link href="{{asset('/assets/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}" rel="stylesheet"/> -->
        <script>
    		var TOKEN = '{{Request::session()->get('token')}}';
    		var SOA_URL = '{{config('app.url_soa')}}';
    		</script>
    </head>


    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                @if(Auth::check() && (Route::currentRouteName() != 'forgot_password'))
                <div class="col-md-3 left_col menu_fixed">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="{{URL('/')}}" class="site_title">
                                <span><img alt="Logo SIN-BNN" src="{{asset('assets/images/BNN-LOGO-full.png')}}" class="img-responsive"></span>
                                <!--<i class="fa fa-paw"></i>-->
                                <i class="logo-bnn"></i>
                            </a>
                        </div>

                        <div class="clearfix"></div>
                        @include('layouts.side_menu')
                    </div>
                </div>
                <div class="top_nav">
                        <div class="nav_menu nav1">
                            <nav>
                                <ul class="nav navbar-nav navbar-right">
                                    <li role="presentation" class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-cog"></i>
                                            <!--<span class="badge bg-green">6</span>-->
                                        </a>
                                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                                            <li><a href="{{URL('/')}}"> <span class="glyphicon glyphicon-home" aria-hidden="true"></span>   Beranda </a></li>
                                            <li><a href="{{url('/profile')}}"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    My Profile </a></li>
                                            <li><a href="{{url('/settings/monitoring_nihil')}}"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Monitoring Nihil </a></li>
                                            <li><a href="{{url('/downloadapp')}}" target="_blank"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Mobile App </a></li>
                                            <li><a href="{{url('/usermanual')}}" target="_blank"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Manual Guide </a></li>
                                            {{-- <li><a href="{{url('/settings/user_management')}}"> <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>    User Management </a></li>
                                            <li><a href="javascript:;"> <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>    Instansi Management </a></li> --}}
                                            <li><a href="{{url('logout')}}"> <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>    Log Out </a></li>
                                        </ul>
                                    </li>

                                    <li role="presentation" class="dropdown">
                                        <!-- <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-user-circle"></i>
                                            <span class="badge bg-blue-light">6</span>
                                        </a> -->
                                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                            <li>
                                                <a>
                                                    <span class="image"><img src="{{url('assets/images/img.jpg')}}" alt="Profile Image" /></span>
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
                                                    <span class="image"><img src="{{url('assets/images/img.jpg')}}" alt="Profile Image" /></span>
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
                                                    <span class="image"><img src="{{url('assets/images/img.jpg')}}" alt="Profile Image" /></span>
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
                                                    <span class="image"><img src="{{url('assets/images/img.jpg')}}" alt="Profile Image" /></span>
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
                                        <h3 class="pull-right m-r-20 f-20 m-t-14">{{(isset($title)) ? $title : ""}} <i class="fa fa-home"></i></h3>
                                    </div>
                                </div>
                            </nav>
                        </div>
                </div>
                @endif
                @yield('content')
                <div class="text-center c-white">
                    Sistem Informasi Narkoba | Badan Narkotika Nasional - Copyright © 2017
                </div>
            </div>
        </div>
    </body>
    <footer>

        <script>
            var BASE_URL = "{{URL('/')}}";
            var CURRENT_URL = "{{Request::url()}}";
        </script>
        @if(isset($page))
        <script>
            var TOTAL_PAGES = {{$page['totalpage']}};
            var CURRENT_PAGE = {{$page['page']}};
        </script>   
        @endif
        <script src="{{asset('assets/jquery/dist/jquery.min.js') }}"> </script>
        <script src="{{asset('assets/bootstrap/dist/js/bootstrap.min.js') }}"> </script>
        <script src="{{asset('assets/js/jquery.twbsPagination.js') }}"> </script>
        <script src="{{asset('assets/fastclick/lib/fastclick.js') }}"> </script>
        <script src="{{asset('assets/nprogress/nprogress.js') }}"> </script>
        <script src="{{asset('assets/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"> </script>
        <script src="{{asset('assets/js/moment-with-locales.js')}}"></script>
        <script src="{{asset('assets/js/bootstrap-datetimepicker.js')}}"></script>
        <script src="{{asset('assets/js/select2.min.js') }}"> </script>
        <script src="{{asset('assets/js/bootstrap-fileinput.js') }}" type="text/javascript"></script>
        <script src="{{asset('assets/js/jquery.repeater.js') }}" type="text/javascript"></script>
        <script src="{{asset('assets/js/icheck.min.js') }}"> </script>
        <script type="text/javascript" src="{{asset('/assets/js/popper.js')}}"></script>
        <!-- <script type="text/javascript" src="{{asset('/assets/js/jquery.dataTables.js')}}"></script> -->
        <!-- <script type="text/javascript" src="{{asset('/assets/js/datatables.min.js')}}"></script> -->
        <!-- <script type="text/javascript" src="{{asset('/assets/DataTables-1.10.16/js/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/assets/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> -->
        <script src="{{asset('assets/js/custom.js') }}"> </script>
        <!-- <script src="{{asset('assets/js/custom.min.js') }}"> </script> -->
        <script src="{{asset('assets/js/script.js') }}"> </script>

    </footer>
</html>
