@props(['activePage'])
@php
        $user = Auth::user();
    @endphp
<aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 custom-bg-black " id="sidenav-main">
    <div class="sidenav-header">
        
        <i class="fas fa-times p-3 cursor-pointer color: black; opacity-100 position-absolute end-0 top-0" aria-hidden="true" id="iconSidenav"></i>
        
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logos.png" class="navbar-brand-img h-200" alt="main_logo">
            <span class="ms-2 font-weight-bold custom-text-color">Ecogreen Oleochemical</span>
        </a>

    </div>

    <hr class="horizontal light mt-0 mb-3">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-8 custom-text-color">Laravel</h6>
            </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'user-profile' ? 'active bg-gradient-info' : '' }} "
                        href="{{ route('user-profile') }}">
                        <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                            <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bold custom-text-color">User Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'user-management' ? ' active bg-gradient-info' : '' }} "
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
                <a class="nav-link text-white {{ $activePage == 'dashboard' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('dashboard') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'Cip Cumulative Balance' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('cipcumbal.index') }}">
                    <div class="custom-icon-color text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-heart"></i> 
                    </div>
                    <span class="nav-link-text ms-1 font-weight-bold custom-text-color">Cip Cumulative Balance</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'UPDOC' ? ' active bg-gradient-info' : '' }} "
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

    document.getElementById('iconSidenav').addEventListener('click', function() {
        sidebar.classList.toggle('closed'); // Menambahkan/Menghapus kelas 'closed'
    });

    // Event untuk mendeteksi mouseenter dan mouseleave
    sidebar.addEventListener('mouseenter', function() {
        sidebar.classList.remove('closed'); // Tampilkan sidebar
    });

    sidebar.addEventListener('mouseleave', function() {
        if (!sidebar.classList.contains('closed')) {
            sidebar.classList.add('closed'); // Sembunyikan sidebar
        }
    });
</script>

<style>
    #iconSidenav:hover {
        background-color: rgba(255, 255, 255, 0.2); /* Warna latar belakang saat hover */
    }

    .sidenav {
        width: 250px; /* Lebar sidebar */
        position: fixed; /* Posisi tetap */
        transition: transform 0.3s ease; /* Efek transisi saat menggeser */
        z-index: 1000; /* Pastikan sidebar di atas elemen lain */
    }

    .sidenav.closed {
        transform: translateX(-100%); /* Geser ke kiri */
    }
    .custom-bg-black {
        background-color: #ffffff !important; /* Mengubah latar belakang menjadi hitam  sidebar*/
    }
    .custom-text-color {    
    color: #3f3d3d; /* Sesuaikan dengan warna yang diinginkan */
    }
    .custom-icon-color {
    color: #323c34; /* Sesuaikan dengan warna yang diinginkan */
    }

</style>

