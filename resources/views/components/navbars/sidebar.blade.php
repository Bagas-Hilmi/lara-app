@props(['activePage'])
@php
    $user = Auth::user();
@endphp
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-xl fixed-start "
    id="sidenav-main">

    <div class="sidenav-header">
        <i class="fas fa-bars p-3 cursor-pointer" id="iconSidenav"
            style="color: black; opacity: 1; font-size: 20px; position: absolute; top: 0; right: 0;"></i>

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets') }}/img/log1.png" class="logo" alt="Logo"
                style="max-height:90px; width: auto;">
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-3">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-8 custom-text-color">Laravel</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'user-profile' ? 'active bg-gradient-success' : '' }} "
                    href="{{ route('user-profile') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">User Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'user-management' ? ' active bg-gradient-success' : '' }} "
                    href="{{ route('user-management') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1rem;" class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">User Management</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-8 custom-text-color">Pages</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'capex' ? ' active bg-gradient-success' : '' }} "
                    href="{{ route('capex.index') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-table ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Master Capex</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'Cip Cumulative Balance' ? ' active bg-gradient-success' : '' }}"
                    href="{{ route('cipcumbal.index') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-dollar-sign ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Cip Cumulative Balance</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'UPDOC' ? ' active bg-gradient-success' : '' }} "
                    href="{{ route('faglb.index') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-file-excel ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">UP-Doc FGLB + ZLIS1</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == '' ? 'active bg-gradient-success' : '' }}"
                    href="#submenu1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-list ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Report Capex</span>
                </a>
                <ul class="collapse list-unstyled" id="submenu1">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ $activePage == 'reportCip' ? 'active bg-gradient-success' : '' }}"
                        href="{{ route('report.index', ['flag' => 'cip']) }}" style="padding-left: 40px;">
                            <div class="custom-icon-color text-center me-1">
                                <i style="font-size: 1.2rem;" class="fas fa-table ps-2 pe-2 text-center"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Report CIP</span>
                        </a>
                        <a class="nav-link text-white {{ $activePage == 'reportCategory' ? 'active bg-gradient-success' : '' }}"
                        href="{{ route('report.index', ['flag' => 'category']) }}" style="padding-left: 40px;">
                            <div class="custom-icon-color text-center me-1">
                                <i style="font-size: 1.2rem;" class="fas fa-table ps-2 pe-2 text-center"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Report Category</span>
                        </a>
                    </li>
                </ul>
            </li>                             
        </ul>
    </div>
</aside>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<script>
    const sidebar = document.getElementById('sidenav-main');
    const iconSidenav = document.getElementById('iconSidenav');

    // Fungsi untuk mengecek status fixed dari localStorage
    function isFixedSidebar() {
        return localStorage.getItem('sidebarFixed') === 'true';
    }

    // Fungsi untuk set status fixed ke localStorage
    function setFixedSidebar(isFixed) {
        localStorage.setItem('sidebarFixed', isFixed);
    }   

    // Fungsi untuk mengatur perilaku sidebar
    function initializeSidebar() {
        // Mengecek status fixed dari localStorage saat halaman dimuat
        if (isFixedSidebar()) {
            sidebar.classList.remove('closed');
            iconSidenav.classList.add('active');
        } else {
            // Jika tidak fixed, terapkan perilaku default (closed)
            if (window.innerWidth > 767) {
                sidebar.classList.add('closed');
            }
        }
    }

    // Toggle sidebar ketika icon diklik
    iconSidenav.addEventListener('click', function() {
        const isFixed = isFixedSidebar();
        
        if (isFixed) {
            // Jika sedang fixed, ubah ke mode fleksibel
            setFixedSidebar(false);
            iconSidenav.classList.remove('active');
            if (window.innerWidth > 767) {
                sidebar.classList.add('closed');
            }
        } else {
            // Jika sedang fleksibel, ubah ke mode fixed
            setFixedSidebar(true);
            iconSidenav.classList.add('active');
            sidebar.classList.remove('closed');
        }
    });

    // Event untuk mouseenter pada layar lebar (desktop)
    sidebar.addEventListener('mouseenter', function() {
        if (window.innerWidth > 767 && !isFixedSidebar()) {
            sidebar.classList.remove('closed');
        }
    });

    // Event untuk mouseleave pada layar lebar (desktop)
    sidebar.addEventListener('mouseleave', function() {
        if (window.innerWidth > 767 && !isFixedSidebar()) {
            sidebar.classList.add('closed');
        }
    });

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', initializeSidebar);
</script>


<style>
    #sidenav-main, #sidenav-main * {
        box-sizing: border-box;
    }
    :root {
        --sidebar-width: 250px;
        --sidebar-mini-width: 80px;
        --header-height: 100px;
    }
    /* Hover icon sidenav */
    #iconSidenav:hover {
        background-color: rgba(255, 255, 255, 0.2);
        /* Warna latar belakang saat hover */
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 40px;
        color: black;
        opacity: 1;
        cursor: pointer;
    }


    /* Tampilan sidebar */
    .sidenav {
        min-width: 250px;
        max-width: 250px;
        /* Lebar sidebar */
        position: fixed;
        /* Posisi tetap */
        transition: transform 0.3s ease;
        /* Efek transisi saat menggeser */
        z-index: 1050;
        /* Pastikan sidebar di atas elemen lain */
        background-color: #ffffff;
        /* Background default sidebar */

        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Membuat header, konten, dan footer berjarak */
        overflow-y: auto; /* Hanya tampilkan scrollbar vertikal saat dibutuhkan */
    overflow-x: hidden;/* Sembunyikan scrollbar horizontal */
    }

    /* Sidebar saat ditutup */
    .sidenav.closed {
        transform: translateX(-95%);
        /* Geser ke kiri untuk sembunyikan sidebar */
    }

    .sidenav-header {
        background-color: #D5D7D6;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100px;
        padding: 0px;
        position: relative;
    }

    .sidenav-header .navbar-brand {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
    }

    /* Warna custom sidebar */
    .custom-bg-black {
        background-color: #ffffff;
        /* Latar belakang sidebar */
    }

    .custom-text-color {
        color: #0D1E0F;
        /* Warna teks */
    }

    .custom-icon-color {
        color: #0D1E0F;
        /* Warna ikon */
    }

    /* Media queries untuk layar besar (desktop) */
    @media (min-width: 992px) {
        .sidenav {
            width: 250px;
            /* Lebar normal untuk desktop */
        }
    }

    /* Media queries untuk tablet */
    @media (max-width: 991px) and (min-width: 768px) {
        .sidenav {
            width: 200px;
            /* Lebar lebih kecil untuk tablet */
        }
    }

    /* Media queries untuk mobile (layar kecil) */
    @media (max-width: 767px) {
        .sidenav {
            width: 100%;
            /* Full width di mobile */
            position: absolute;
            /* Posisi absolute untuk menumpuk konten */
        }

        .sidenav.closed {
            transform: translateX(-100%);
            /* Geser ke kiri untuk sembunyikan sidebar */
        }
    }

    #iconSidenav {
        position: relative;
        font-size: 24px; /* Ukuran ikon */
        transition: transform 0.3s;
    }

    #iconSidenav::before {
        content: '\f0c9'; /* Kode ikon default (misalnya dari Font Awesome) */
        font-family: 'Font Awesome 5 Free'; /* Pastikan untuk menggunakan font yang benar */
        font-weight: 900; /* Pastikan untuk menggunakan berat yang benar */
    }

    #iconSidenav.active {
        transform: rotate(90deg);
        background-color: #D5D7D6;
    }

    #iconSidenav.active::before {
        content: '\f141'; /* Kode ikon saat aktif */
    }
</style>
