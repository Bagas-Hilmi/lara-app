@extends('layouts.app') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    Akses Ditolak
                </div>
                <div class="card-body text-center">
                    <h1 class="text-danger mb-4">🚫 Akses Tidak Diizinkan</h1>
                    <p class="text-muted">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection