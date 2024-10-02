@props(['titlePage'])

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <img src="{{ asset('assets') }}/img/log1.png" style="width: 250px; height: auto;">
            </ol>
            <h6 class="font-weight-bolder mb-0">{{ $titlePage }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                
            </div>
            <form method="POST" action="{{ route('logout') }}" class="d-none" id="logout-form">
                @csrf
            </form>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar rounded-circle bg-primary d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                <i class="fa fa-user text-white" style="font-size: 20px;"></i>
                            </div>
                            <span class="d-sm-inline d-none" style="font-size: 16px; color: black;">
                                {{ Auth::user()->name }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#" onclick="showLogoutSwal();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Sign Out
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showLogoutSwal() {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to sign out?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Yes, sign out!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

<style>
    .user-avatar {
        transition: background-color 0.3s ease;
    }
    
    .nav-link:hover .user-avatar {
        background-color: #0b0e0b !important;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .dropdown-item {
        color: #323c34;
        transition: background-color 0.3s ease;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    </style>