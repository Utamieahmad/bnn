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
              <div>
                <div class="login_wrapper">
                  <div class="animate form login_form">
                    <div class="col-md-3">

                    </div>
                    <div class="col-sm-6 col-xs-12 p-0">
                        {{-- <form class="form-horizontal form-label-left input_mask" action="{{$url}}" method="GET" enctype="multipart/form-data">
                          {{csrf_field()}} --}}

                          <div id="input_phl">
                            <div class="form-group has-feedback m-t-32" align="center">
                              <img src="{{asset('assets/images/sin_app_logo.png')}}" alt="SIN" height="125" width="125">
                            </div>

                            <div class="form-group has-feedback m-t-32" align="center" style="color:white; font-size:20px;">
                              <p>{{$title}}</p>
                            </div>

                          </div>

                      {{-- <section class="login_content"> --}}
                          <div class="m-t-32" style="text-align: center;padding-bottom: 6px;">
                            <a href="{{$url}}">
                            <input type="submit" name="submit" class="btn btn-primary btn-round submit" value="Download" style="margin-bottom: 20px; margin-top: -25px;"/>
                            </a>
                          </div>

                          </div>
                        {{-- </form> --}}
                      {{-- </section> --}}
                    </div>

                  </div>

                </div>
              </div>
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
