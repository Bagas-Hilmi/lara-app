@props(['activePage'])
@php
        $user = Auth::user();
    @endphp
<aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 custom-bg-black " id="sidenav-main">
    <div class="sidenav-header">
        
        <i class="fas fa-times p-3 cursor-pointer color: black; opacity-100 position-absolute end-0 top-0 font-size: 40px;" id="iconSidenav"></i>
        
        <a class="navbar-brand d-flex align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logos.png" class="logo" alt="Logo">
            <span class="ms-2 font-weight-bold text-white">Ecogreen Oleochemical</span>
        </a>

    </div>

    <hr class="horizontal light mt-0 mb-3">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-8 custom-text-color">Laravel</h6>
            </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'user-profile' ? 'active bg-gradient-primary' : '' }} "
                        href="{{ route('user-profile') }}">
                        <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                            <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bold custom-text-color">User Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'user-management' ? ' active bg-gradient-primary' : '' }} "
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
                <a class="nav-link text-white {{ $activePage == 'dashboard' ? ' active bg-gradient-primary' : '' }} "
                    href="{{ route('dashboard') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'Cip Cumulative Balance' ? ' active bg-gradient-primary' : '' }} "
                    href="{{ route('cipcumbal.index') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-heart"></i> 
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Cip Cumulative Balance</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'UPDOC' ? ' active bg-gradient-primary' : '' }} "
                    href="{{ route('faglb.index') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-heart"></i> 
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">UP-Doc FGLB + ZLIS1</span>
                </a>
            </li>  
            
        </ul>
    </div>
</aside>    

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const sidebar = document.getElementById('sidenav-main');

    // Toggle sidebar ketika icon diklik
    document.getElementById('iconSidenav').addEventListener('click', function() {
        sidebar.classList.toggle('closed'); // Menambahkan/Menghapus kelas 'closed'
    });

    // Event untuk mouseenter dan mouseleave pada layar lebar (desktop)
    sidebar.addEventListener('mouseenter', function() {
        if (window.innerWidth > 767) {
            sidebar.classList.remove('closed'); // Tampilkan sidebar saat mouse masuk
        }
    });

    sidebar.addEventListener('mouseleave', function() {
        if (window.innerWidth > 767 && !sidebar.classList.contains('closed')) {
            sidebar.classList.add('closed'); // Sembunyikan sidebar saat mouse keluar
        }
    });

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Deteksi ukuran layar berubah
    window.addEventListener('resize', debounce(function() {
        const width = window.innerWidth;
        if (width <= 767 && !sidebar.classList.contains('closed')) {
            sidebar.classList.add('closed'); // Pastikan sidebar tertutup di layar kecil
        }
    }, 100)); // Menentukan waktu tunggu (100ms, bisa diubah sesuai kebutuhan)
</script>


<style>
    .logo {
            width: 100%; /* Atur lebar sesuai kebutuhan */
            height: 100%; /* Atur tinggi sesuai kebutuhan */
        }
    /* Hover icon sidenav */
    #iconSidenav:hover {
        background-color: rgba(255, 255, 255, 0.2); /* Warna latar belakang saat hover */
    }

    /* Tampilan sidebar */
    .sidenav {
        width: 250px; /* Lebar sidebar */
        position: fixed; /* Posisi tetap */
        transition: transform 0.3s ease; /* Efek transisi saat menggeser */
        z-index: 1000; /* Pastikan sidebar di atas elemen lain */
        background-color: #ffffff !important; /* Background default sidebar */
    }

    /* Sidebar saat ditutup */
    .sidenav.closed {
        transform: translateX(-100%); /* Geser ke kiri untuk sembunyikan sidebar */
    }

    .sidenav-header {
        background-color: #e93b76; /* Ganti dengan warna latar belakang yang Anda inginkan */
    }

    /* Warna custom sidebar */
    .custom-bg-black {
        background-color: #ffffff !important; /* Latar belakang sidebar */
    }

    .custom-text-color {
        color: #b4b4b4; /* Warna teks */
    }

    .custom-icon-color {
        color: #b4b4b4; /* Warna ikon */
    }

    /* Media queries untuk layar besar (desktop) */
    @media (min-width: 992px) {
        .sidenav {
            width: 250px; /* Lebar normal untuk desktop */
        }
    }

    /* Media queries untuk tablet */
    @media (max-width: 991px) and (min-width: 768px) {
        .sidenav {
            width: 200px; /* Lebar lebih kecil untuk tablet */
        }
    }

    /* Media queries untuk mobile (layar kecil) */
    @media (max-width: 767px) {
        .sidenav {
            width: 100%; /* Full width di mobile */
            position: absolute; /* Posisi absolute untuk menumpuk konten */
        }

        .sidenav.closed {
            transform: translateX(-100%); /* Geser ke kiri untuk sembunyikan sidebar */
        }
    }
</style>

