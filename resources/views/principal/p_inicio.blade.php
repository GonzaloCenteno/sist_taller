<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>TALLER - @yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <!-- Font Awesome -->
        <link rel="icon" type="image/png" href="{{ asset('img/bus-home.png') }}" />
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
        <link href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}" rel="stylesheet" type="text/css" media="screen">
        <link href="{{ asset('css/smartadmin-production.min.css') }}" rel="stylesheet">
        
        <link href="{{ asset('css/ui.jqgrid.css') }}" rel="stylesheet">
        <link href="{{ asset('css/ui.jqgrid-bootstrap4.css') }}" rel="stylesheet">
        <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet"> 
        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jquery-confirm.css') }}" rel="stylesheet"> 
        
        <style>
            .ui-jqgrid {
                /*display: flex;*/
                flex-direction: column;
                flex:1 1 auto;
                width:auto !important;
              }
              .ui-jqgrid > .ui-jqgrid-view
              {
                display:flex;
                flex:1 1 auto;
                flex-direction:column;
                overflow:auto;
                width:auto !important;
              }

               .ui-jqgrid > .ui-jqgrid-view,
               .ui-jqgrid > .ui-jqgrid-view > .ui-jqgrid-bdiv {
                    flex:1 1 auto;
                    width: auto !important;
              }

              .ui-jqgrid > .ui-jqgrid-pager,
              .ui-jqgrid > .ui-jqgrid-view > .ui-jqgrid-hdiv {
                  flex: 0 1 auto;
                width: auto !important;
              }
              /* enable scrollbar */
              .ui-jqgrid-bdiv {
                  overflow: auto
              }
              
              .btn-round-animate {border-radius: 50%;}
              
              a.nav-link p{
                white-space:normal !important;
                height:auto !important;
                padding:2px;
            }
        </style>
    </head>
    <body class="hold-transition sidebar-mini" id="body_push">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand border-bottom navbar-dark bg-danger">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" id="menu_push"><i class="fa fa-bars"></i></a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Messages Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" style="padding-bottom: 36px;">
                            <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                            <label>
                                BIENVENIDO: {{ session('id_usuario') }} | 
                                <?php $sql = DB::table('permisos.vw_rol_menu_usuario')->select('sro_id')->where([['sist_id',session('sist_id')],['ume_usuario',session('id_usuario')]])->first(); 
                                if($sql)
                                {
                                    $cargo = DB::table('permisos.tblsistemasrol_sro')->select('sro_descripcion')->where('sro_id',$sql->sro_id)->first();  
                                    echo 'ROL : '.$cargo->sro_descripcion; 
                                }
                                ?>
                            </label>
                        </a>
                        
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <form method="GET" action="{{ route('logout') }}">
                                {{ csrf_field() }}
                                <a href="{{ route('logout') }}">
                                    <h3 class="text-lg text-muted"> CERRAR SESION</h3>
                                </a>
                            </form>   
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar elevation-4 sidebar-light-danger">
                <!-- Brand Logo -->
                <a href="#" class="brand-link bg-danger">
                    <img src="{{ asset('img/bus-home.png') }}" alt="SISTEMA TALLER - CROMOTEX" class="brand-image img-circle elevation-3"
                         style="opacity: .8">
                    <span class="brand-text font-weight-light">Sist - Taller</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <hr>
                    <div class="col-md-12 text-center user-panel">
                        <div class="info text-center">
                            <b><a href="#" class="d-block">{{ session('nomb_usuario') }}</a></b>
                            @if(isset($cargo->sro_descripcion))<b><a href="#" class="d-block">{{ $cargo->sro_descripcion }}</a></b>@else<b><a href="#" class="d-block"></a></b>@endif
                        </div>
                    </div>
                    <hr>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            @if(isset($menu))
                                @foreach($menu as $men)
                                <li class="nav-item has-treeview" id="{{ $men->men_sistema }}">
                                    <a href="#" class="nav-link {{ $men->men_sistema }}">
                                        <i class="nav-icon fa fa-edit"></i>
                                        <p>
                                            {{ $men->men_titulo }}
                                            <i class="right fa fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <?php $submenu = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['men_id',$men->men_id],['btn_view',1]])->orderBy('usm_orden','asc')->get();?>
                                        @foreach($submenu as $sub)
                                        <li class="nav-item">
                                            <a href="{{ $sub->sme_ruta }}" class="nav-link {{ $sub->sme_ruta }}">
                                                <i class="fa fa-circle-o nav-icon"></i>
                                                <p>{{ $sub->sme_titulo }}</p>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <div class="dropdown-divider"></div>
                                @endforeach
                            @else
                            @endif
                        </ul>
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
        <script type="text/javascript" src="{{ asset('js/block_ui.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery-confirm.js') }}"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script type="text/javascript" src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Sparkline -->
        <script type="text/javascript" src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script type="text/javascript" src="{{ asset('plugins/knob/jquery.knob.js') }}"></script>
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
        <script type="text/javascript" src="{{ asset('js/qz/qz-tray.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/qz/dependencies/rsvp-3.1.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/qz/dependencies/sha-256.min.js') }}"></script>
        <script>
            var rol_descripcion = '{{ isset($cargo->sro_descripcion) ? $cargo->sro_descripcion : '' }}';
        </script>
        @yield('page-js-script')
    </body>
</html>
