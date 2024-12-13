<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage=""></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            
                        </div>
                        <div class="me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="#" data-bs-toggle="modal" data-bs-target="#modal-form">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Form
                            </a>
                        </div>
                        
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.auth></x-footers.auth>
        </div>
    </main>


</x-layout>

<style>
    .main-content {
    margin-left: 250px; /* Memberikan ruang untuk sidebar */
    transition: margin-left 0.3s ease; /* Transisi saat sidebar dibuka/tutup */
}

.sidenav.closed ~ .main-content {
    margin-left: 0; /* Menghapus margin saat sidebar ditutup */
}

/* Responsif untuk tablet */
@media (max-width: 991px) {
    .main-content {
        margin-left: 200px; /* Mengurangi margin untuk tablet */
    }
}

/* Responsif untuk mobile */
@media (max-width: 767px) {
    .main-content {
        margin-left: 0; /* Hapus margin di mobile */
        padding: 10px; /* Mengurangi padding di mobile */
    }

    .sidenav.closed ~ .main-content {
        margin-left: 0; /* Pastikan konten utama tidak overlap saat sidebar ditutup */
    }

    /* Mengatur lebar tabel agar responsif */
    #faglb-table {
        width: 100%; /* Memastikan tabel mengambil 100% lebar */
    }
}
</style>