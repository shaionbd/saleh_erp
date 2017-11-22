<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if(isset($title))
            <title>{{ config('app.name', 'Laravel') }} | {{ $title }}</title>
        @else
            <title>{{ config('app.name', 'Laravel') }}</title>
        @endif

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/font-awesome.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/notify.css') }}">
        @if (Auth::user())
            <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/AdminLTE.min.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/skin-blue.min.css') }}">
            <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
            <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom.css') }}">
        @else
            <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
            <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
            <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/awesome-bootstrap-checkbox/0.3.7/awesome-bootstrap-checkbox.min.css'>
            <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/login.css') }}"> 
        @endif
        @stack('css')

        <!-- Scripts -->
        <script>

            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>

        </script>
        <script>
            var token = '{{ Session::token() }}';
        </script>

    </head>

    @if (!Auth::user())

        <body class="login">

            <div id="particles-js"></div>

            <div>
                
                @yield('content')

            </div>

        </body>

    @else

        <body class="hold-transition skin-blue sidebar-mini">
    
            <div class="wrapper">

                <!-- Main Header -->
                <header class="main-header">
            
                    @include('layouts.navbar')

                </header>

                <!-- Left side column. contains the logo and sidebar -->
                <aside class="main-sidebar">

                    @include('layouts.left_sidebar')

                </aside>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    
                    @yield('content')

                </div><!-- /.content-wrapper -->

                <!-- Control Sidebar -->
                <aside class="control-sidebar control-sidebar-dark">

                    @include('layouts.right_sidebar')

                </aside>

                <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
                <div class="control-sidebar-bg"></div>

            </div><!-- ./wrapper -->

        </body>

    @endif

    <footer>

        @if (Auth::user())

            @include('layouts.footer')

        @endif

        <!-- Scripts -->
        <script type="text/javascript" src="{{ URL::asset('public/js/jquery.min.js') }}"></script>
       <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script> -->
        <script type="text/javascript" src="{{ URL::asset('public/js/bootstrap.min.js') }}"></script>
        @if (Auth::user())
            <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
            
            <script type="text/javascript" src="{{ URL::asset('public/js/notify.min.js') }}"></script>
            
            <script type="text/javascript" src="{{ URL::asset('public/js/app.min.js') }}"></script>

            <script type="text/javascript" src="{{ URL::asset('public/js/main.js') }}"></script>
        @else
            <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
            <script type="text/javascript" src="{{ URL::asset('public/js/login.js') }}"></script>
        @endif
        @stack('js')
        
    </footer>

</html>
