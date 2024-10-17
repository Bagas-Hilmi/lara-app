@props(['signin', 'signup'])

<nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4" style="background-color: #ffffff !important;">
    <div class="container-fluid d-flex justify-content-between">
        <!-- Brand name -->
        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 d-flex flex-column" href="{{ url('/') }}">
            <img src="{{ asset('assets') }}/img/log1.png"  style="width: 145px;">
        </a>

        <!-- Center the navigation menu with absolute positioning -->
        <div class="position-absolute top-50 start-50 translate-middle">
            <ul class="navbar-nav d-flex flex-row justify-content-center">
                <li class="nav-item">
                    <a class="nav-link me-2 " href="{{ route($signup) }}" style="font-size: 16px; font-weight: bold; color: #000000;">
                        <i class="fas fa-user-circle  "></i> Sign Up
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route($signin) }}" style="font-size: 16px; font-weight: bold; font-color: #000000;" >
                        <i class="fas fa-key  "></i> Sign In
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
