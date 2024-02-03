<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    @if(file_exists(settingCache('site_favicon')))
    <link rel="icon" type="image/png" sizes="16x16" href="{{url(settingCache('site_favicon'))}}">
    @else
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('public/front/assets/images/favicon.png')}}">
    @endif
    <title>{{isset($title)?$title:config('app.name', 'Laravel')}}</title>
    <!-- Custom CSS -->
    <link href="{{url('public/front/dist/css/style.min.css')}}" rel="stylesheet">
    <link href="{{url('public/front/assets/libs/SnackBar-master/dist/snackbar.min.css')}}" rel="stylesheet">
    <link href="{{url('public/front/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet"> 
    <link href="{{url('public/front/assets/libs/dropify-master/dist/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{url('public/front/assets/libs/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{url('public/front/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/front/dist/css/custom.css')}}" rel="stylesheet">
    @yield('styles')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-sidebartype="{{(@Auth::guard('web')->user()->sidebar_style=='MINI')?'mini-sidebar':'full'}}" class="{{(@Auth::guard('web')->user()->sidebar_style=='MINI')?'mini-sidebar':''}}">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        @include('society-front.layouts.header')
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @include('society-front.layouts.sidebar')
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
             @include('society-front.layouts.breadcrumb')
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
               @include('society-front.layouts.flash')
               @yield('content')
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            @include('society-front.layouts.footer')
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{url('public/front/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{url('public/front/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{url('public/front/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{url('public/front/dist/js/form.js')}}"></script>
    <script src="{{url('public/front/assets/libs/SnackBar-master/dist/snackbar.min.js')}}"></script>  
    <!--Wave Effects -->
    <script src="{{url('public/front/dist/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{url('public/front/dist/js/sidebarmenu.js')}}"></script>
    <script src="{{url('public/front/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{url('public/front/assets/libs/dropify-master/dist/js/dropify.min.js')}}"></script>
    <script src="{{url('public/front/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{url('public/front/assets/libs/select2/dist/js/select2.min.js')}}"></script>
    <script src="{{url('public/front/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{url('public/front/dist/js/custom.js')}}"></script>
    @yield('scripts')
</body>

</html>