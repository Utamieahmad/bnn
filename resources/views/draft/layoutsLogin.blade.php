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
     <link href="{{asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="{{asset('malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{asset('css/custom.min.css') }}" rel="stylesheet">
</head>
<body class="login">
  @yield('content')

<!-- jQuery -->
<script src="{{asset('jquery/dist/jquery.min.js') }}"> </script>
<!-- Bootstrap -->
<script src="{{asset('bootstrap/dist/js/bootstrap.min.js') }}"> </script>
<!-- FastClick -->
<script src="{{asset('fastclick/lib/fastclick.js') }}"> </script>
<!-- NProgress -->
<script src="{{asset('nprogress/nprogress.js') }}"> </script>
<!-- jQuery custom content scroller -->
<script src="{{asset('malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"> </script>

<!-- Custom Theme Scripts -->
<script src="{{asset('js/custom.min.js') }}"> </script>
</body>
</html>
