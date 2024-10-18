@props(['signin', 'signup'])

<nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4" style="background-color: #ffffff;">
    <div class="container-fluid d-flex justify-content-between">
        <!-- Brand name -->
        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 d-flex flex-column" href="{{ url('/') }}">
            <img src="{{ asset('assets') }}/img/log1.png" style="width: 145px;">
        </a>

        <!-- Center the navigation menu with absolute positioning -->
        <div class="position-absolute top-50 start-50 translate-middle">
            <ul class="navbar-nav d-flex flex-row justify-content-center">
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route($signup) }}" style="font-size: 16px; font-weight: bold; color: #000000;">
                        <i class="fas fa-user-circle"></i> Sign Up
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route($signin) }}" style="font-size: 16px; font-weight: bold; color: #000000;">
                        <i class="fas fa-key"></i> Sign In
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Menambahkan efek hover pada link */
    .nav-link {
        transition: color 0.3s ease, transform 0.3s ease; /* Transisi halus */
    }

    .nav-link:hover {
        color: #007bff; /* Warna teks pada hover */
        transform: translateY(-2px); /* Efek naik sedikit pada hover */
    }

    /* Menambahkan efek bayangan pada navbar */
    .navbar {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
    }

    /* Gaya untuk ikon */
    .nav-link i {
        margin-right: 5px; /* Jarak antara ikon dan teks */
    }

    .navbar:hover {
        box-shadow: 0 8px 15px rgba(0,0,0,.1);
    }
    .navbar-brand img {
        max-height: 50px;
        transition: transform 0.3s ease;
    }
    .navbar-brand img:hover {
        transform: scale(1.05);
    }
        .nav-link {
            color: #2c3329 !important;
            font-weight: 600 !important;
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-link:hover {
            color: #2eb304 !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #a6da8a;
            transition: all 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }
    @media (max-width: 991.98px) {
        .navbar-nav {
            background-color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
    }
</style>