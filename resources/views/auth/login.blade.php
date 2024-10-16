<x-layout bodyClass="bg-gray-200">

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <x-navbars.navs.guest signin='login' signup='register'></x-navbars.navs.guest>
                <!-- End Navbar -->
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100"
            style="background-image: url('/assets/img/eco6.jpg');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container mt-5">
                <div class="row signin-margin">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">SIGN IN</h4>
                                    <div class="row justify-content-center text-center">
                                        <div class="col-2">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-facebook text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-github text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-google text-white text-lg"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            
                            <div class="card-body">
                                <form role="form" method="POST" action="{{ route('login') }}" class="text-start">
                                    @csrf
                                    @if (session('status'))
                                    <div class="alert alert-success alert-dismissible text-white" role="alert">
                                        <span class="text-sm">{{ session('status') }}</span>
                                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                     @endif  
                                    <div class="input-group input-group-outline mt-3">
                                        <label class="form-label"></label>
                                        <input type="email" class="form-control" name="email" placeholder="your@email.com">
                                    </div>
                                    @error('email')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="input-group input-group-outline mt-3">
                                        <label class="form-label"></label>
                                        <input type="password" class="form-control" name="password" placeholder="Password">
                                    </div>
                                    @error('password')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="form-check form-switch d-flex align-items-center my-3">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label mb-0 ms-2" for="rememberMe">Remember
                                            me</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign
                                            in</button>
                                    </div>
                                    <p class="mt-4 text-sm text-center">
                                        Don't have an account?
                                        <a href="{{ route('register') }}"
                                            class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                    <p class="text-sm text-center">
                                        Forgot your password? Reset your password
                                        <a href="{{ route('verify') }}"
                                            class="text-primary text-gradient font-weight-bold">here</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.guest></x-footers.guest>
        </div>
    </main>
    @push('js')
<script src="{{ asset('assets') }}/js/jquery.min.js"></script>
<script>
$(function() {

var text_val = $(".input-group input").val();
if (text_val === "") {
  $(".input-group").removeClass('is-filled');
} else {
  $(".input-group").addClass('is-filled');
}
});
</script>
@endpush
</x-layout>
