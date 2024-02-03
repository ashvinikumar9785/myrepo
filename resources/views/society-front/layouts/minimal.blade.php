<!DOCTYPE html>
<html dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
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
        <div class="main-wrapper">
            <!-- ============================================================== -->
            <!-- Preloader - style you can find in spinners.css -->
            <!-- ============================================================== -->
            <div class="preloader">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
                </div>
            </div>
            <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
                <div class="auth-box bg-dark border-secondary" style="background-color: #373737!important">
                    @include('society-front.layouts.flash')
                    <div class="text-center p-t-20 p-b-20">
                        <span class="db">
                            @if(file_exists(settingCache('site_logo')))
                            <img src="{{url(settingCache('site_logo'))}}" alt="logo" style="max-height: 100px" />
                            @endif
                        </span>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- All Required js -->
        <!-- ============================================================== -->
        <script src="{{url('public/front/assets/libs/jquery/dist/jquery.min.js')}}"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="{{url('public/front/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
        <script src="{{url('public/front/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{url('public/front/dist/js/form.js')}}"></script>
        <script src="{{url('public/front/assets/libs/SnackBar-master/dist/snackbar.min.js')}}"></script>
        <script src="{{url('public/front/dist/js/custom.js')}}"></script>
        @yield('scripts')
    </body>
</html>