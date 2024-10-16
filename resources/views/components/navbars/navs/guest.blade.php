@props(['signin', 'signup'])

<nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
    <div class="container-fluid d-flex justify-content-between">
        <!-- Brand name -->
        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 d-flex flex-column" href="{{ url('/') }}">
            Capex Information System
        </a>

        <!-- Center the navigation menu with absolute positioning -->
        <div class="position-absolute top-50 start-50 translate-middle">
            <ul class="navbar-nav d-flex flex-row justify-content-center">
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route($signup) }}">
                        <i class="fas fa-user-circle opacity-6 text-dark me-1"></i> Sign Up
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route($signin) }}">
                        <i class="fas fa-key opacity-6 text-dark me-1"></i> Sign In
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
