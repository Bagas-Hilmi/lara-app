<x-layout bodyClass="">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-navbars.navs.guest signup='register' signin='login'></x-navbars.navs.guest>
            </div>
        </div>
    </div>
    <div class="page-header justify-content-center min-vh-100"
        style="background-image: url('/assets/img/eco4.jpg'); background-size: cover; background-position: center;"
        role="img" aria-label="Eco-friendly nature background with greenery.">
        <!-- Masking for dark overlay -->
        <span class="mask bg-gradient-dark opacity-6"></span>

        <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
            <!-- Welcome text -->
            <h1 class="text-light text-center" style="font-size: 3rem; font-weight: bold;">
                Welcome to Capex Information System
            </h1>
        </div>
    </div>


</x-layout>
