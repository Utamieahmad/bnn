<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $__env->yieldContent('title'); ?></title>
        <link href="<?php echo e(asset('assets/bootstrap/dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/bootstrap-fileinput.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/bootstrap-fileinput.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/green.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/custom.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('assets/css/customs.css')); ?>" rel="stylesheet">
        <!-- <link href="<?php echo e(asset('/assets/css/jquery.dataTables.min.css')); ?>" rel="stylesheet"/> -->
        <!-- <link href="<?php echo e(asset('/assets/css/datatables.min.css')); ?>" rel="stylesheet"/> -->
        <!-- <link href="<?php echo e(asset('/assets/DataTables-1.10.16/css/jquery.dataTables.min.css')); ?>" rel="stylesheet"/> -->
        <!-- <link href="<?php echo e(asset('/assets/DataTables-1.10.16/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet"/> -->
        <script>
    		var TOKEN = '<?php echo e(Request::session()->get('token')); ?>';
    		var SOA_URL = '<?php echo e(config('app.url_soa')); ?>';
    		</script>
    </head>


    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <?php if(Auth::check() && (Route::currentRouteName() != 'forgot_password')): ?>
                <div class="col-md-3 left_col menu_fixed">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="<?php echo e(URL('/')); ?>" class="site_title">
                                <span><img alt="Logo SIN-BNN" src="<?php echo e(asset('assets/images/BNN-LOGO-full.png')); ?>" class="img-responsive"></span>
                                <!--<i class="fa fa-paw"></i>-->
                                <i class="logo-bnn"></i>
                            </a>
                        </div>

                        <div class="clearfix"></div>
                        <?php echo $__env->make('layouts.side_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
                                            <li><a href="<?php echo e(URL('/')); ?>"> <span class="glyphicon glyphicon-home" aria-hidden="true"></span>   Beranda </a></li>
                                            <li><a href="<?php echo e(url('/profile')); ?>"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    My Profile </a></li>
                                            <li><a href="<?php echo e(url('/settings/monitoring_nihil')); ?>"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Monitoring Nihil </a></li>
                                            <li><a href="<?php echo e(url('/downloadapp')); ?>" target="_blank"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Mobile App </a></li>
                                            <li><a href="<?php echo e(url('/usermanual')); ?>" target="_blank"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>    Manual Guide </a></li>
                                            
                                            <li><a href="<?php echo e(url('logout')); ?>"> <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>    Log Out </a></li>
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
                                                    <span class="image"><img src="<?php echo e(url('assets/images/img.jpg')); ?>" alt="Profile Image" /></span>
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
                                                    <span class="image"><img src="<?php echo e(url('assets/images/img.jpg')); ?>" alt="Profile Image" /></span>
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
                                                    <span class="image"><img src="<?php echo e(url('assets/images/img.jpg')); ?>" alt="Profile Image" /></span>
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
                                                    <span class="image"><img src="<?php echo e(url('assets/images/img.jpg')); ?>" alt="Profile Image" /></span>
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
                                        <h3 class="pull-right m-r-20 f-20 m-t-14"><?php echo e((isset($title)) ? $title : ""); ?> <i class="fa fa-home"></i></h3>
                                    </div>
                                </div>
                            </nav>
                        </div>
                </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
                <div class="text-center c-white">
                    Sistem Informasi Narkoba | Badan Narkotika Nasional - Copyright © 2017
                </div>
            </div>
        </div>
    </body>
    <footer>

        <script>
            var BASE_URL = "<?php echo e(URL('/')); ?>";
            var CURRENT_URL = "<?php echo e(Request::url()); ?>";
        </script>
        <?php if(isset($page)): ?>
        <script>
            var TOTAL_PAGES = <?php echo e($page['totalpage']); ?>;
            var CURRENT_PAGE = <?php echo e($page['page']); ?>;
        </script>   
        <?php endif; ?>
        <script src="<?php echo e(asset('assets/jquery/dist/jquery.min.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/bootstrap/dist/js/bootstrap.min.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/js/jquery.twbsPagination.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/fastclick/lib/fastclick.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/nprogress/nprogress.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/js/moment-with-locales.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/bootstrap-datetimepicker.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/select2.min.js')); ?>"> </script>
        <script src="<?php echo e(asset('assets/js/bootstrap-fileinput.js')); ?>" type="text/javascript"></script>
        <script src="<?php echo e(asset('assets/js/jquery.repeater.js')); ?>" type="text/javascript"></script>
        <script src="<?php echo e(asset('assets/js/icheck.min.js')); ?>"> </script>
        <script type="text/javascript" src="<?php echo e(asset('/assets/js/popper.js')); ?>"></script>
        <!-- <script type="text/javascript" src="<?php echo e(asset('/assets/js/jquery.dataTables.js')); ?>"></script> -->
        <!-- <script type="text/javascript" src="<?php echo e(asset('/assets/js/datatables.min.js')); ?>"></script> -->
        <!-- <script type="text/javascript" src="<?php echo e(asset('/assets/DataTables-1.10.16/js/jquery.dataTables.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('/assets/DataTables-1.10.16/js/dataTables.bootstrap.min.js')); ?>"></script> -->
        <script src="<?php echo e(asset('assets/js/custom.js')); ?>"> </script>
        <!-- <script src="<?php echo e(asset('assets/js/custom.min.js')); ?>"> </script> -->
        <script src="<?php echo e(asset('assets/js/script.js')); ?>"> </script>

    </footer>
</html>
