<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SISTEMA TALLER</title>
        <!-- Tell the browser to be responsive to screen width -->
        <!-- Font Awesome -->
        <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
        <!-- iCheck -->
        <link href="{{ asset('plugins/iCheck/flat/blue.css') }}" rel="stylesheet">
        <!-- Morris chart -->
        <link href="{{ asset('plugins/morris/morris.css') }}" rel="stylesheet">
        <!-- jvectormap -->
        <link href="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">
        <!-- Date Picker -->
        <link href="{{ asset('plugins/datepicker/datepicker3.css') }}" rel="stylesheet">
        <!-- Daterange picker -->
        <link href="{{ asset('plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}" rel="stylesheet" type="text/css" media="screen">
        <link href="{{ asset('css/smartadmin-production.min.css') }}" rel="stylesheet">
        
        <link href="{{ asset('css/ui.jqgrid.css') }}" rel="stylesheet">
        <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet"> 
        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand border-bottom navbar-dark bg-danger">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Messages Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="fa fa-comments-o"></i>
                            <span class="badge badge-danger navbar-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!-- Message Start -->
                                <div class="media">
                                    <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
                                        </h3>
                                        <p class="text-sm">Call me whenever you can...</p>
                                        <form method="GET" action="{{ route('logout') }}">
                                            {{ csrf_field() }}
                                            <a href="{{ route('logout') }}">
                                                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> CERRAR SESION</p>
                                            </a>
                                        </form>
                                    </div>
                                </div>
                                <!-- Message End -->
                            </a>
                            <div class="dropdown-divider"></div>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar elevation-4 sidebar-light-danger">
                <!-- Brand Logo -->
                <a href="index3.html" class="brand-link bg-danger">
                    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                         style="opacity: .8">
                    <span class="brand-text font-weight-light">AdminLTE 3</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">Alexander Pierce</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="inicio" class="nav-link">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>Inicio</p>
                                </a>
                            </li>

                            @if(isset($menu_registro) && isset($menu_dashboard))  
                            <li class="nav-header"><center><h5>ADBLUE</h5></center></li>
                            <li class="nav-item has-treeview" id="men_registro">
                                <a href="#1" class="nav-link">
                                    <i class="nav-icon fa fa-edit"></i>
                                    <p>
                                        Registro
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($menu_registro as $men_reg)
                                    <li class="nav-item">
                                        <a href="{{ $men_reg->menu_rut }}" class="nav-link {{ $men_reg->menu_rut }}">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>{{ $men_reg->menu_desc }}</p>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            
                            <li class="nav-item has-treeview" id="men_dashboard">
                                <a href="#2" class="nav-link">
                                    <i class="nav-icon fa fa-dashboard"></i>
                                    <p>
                                        Dashboard
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($menu_dashboard as $men_dash)
                                    <li class="nav-item">
                                        <a href="{{ $men_dash->menu_rut }}" class="nav-link {{ $men_dash->menu_rut }}">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>{{ $men_dash->menu_desc }}</p>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @else
                            @endif
                        </ul>
                        <div class="dropdown-divider"></div>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>
                <label></label>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script type="text/javascript" src="{{ asset('js/jquery.jqGrid.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/grid.locale-es.js') }}"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script type="text/javascript" src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Sparkline -->
        <script type="text/javascript" src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script type="text/javascript" src="{{ asset('plugins/knob/jquery.knob.js') }}"></script>
        <!-- daterangepicker -->
        <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- datepicker -->
        <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script type="text/javascript" src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
        <!-- Slimscroll -->
        <script type="text/javascript" src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <!-- FastClick -->
        <script type="text/javascript" src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
        <!-- AdminLTE App -->
        <script type="text/javascript" src="{{ asset('dist/js/adminlte.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
        <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('archivos_js/funciones_globales.js') }}"></script>
        @yield('page-js-script')
    </body>
</html>
