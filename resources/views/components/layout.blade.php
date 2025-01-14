@props(['bodyClass'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::check() ? Auth::user()->id : '' }}">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets') }}/img/eco2.jpg">
    <link rel="icon" type="image/png" href="{{ asset('assets') }}/img/eco2.jpg">
    <title>@yield('title', config('app.name', 'Capex Information System'))</title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet"
        type="text/css"href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    
    <link href="{{ asset('assets') }}/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link href="{{ asset('assets') }}/fontawesome/css/all.min.css" rel="stylesheet" />

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets') }}/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="{{ asset('/js/tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

</head>

<body class="{{ $bodyClass }}">

    {{ $slot }}

    <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/smooth-scrollbar.min.js"></script>
    @stack('js')
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets') }}/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>
