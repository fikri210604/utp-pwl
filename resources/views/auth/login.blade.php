@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-12 col-md-7 col-lg-5 col-xl-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <h1 class="h4 fw-bold text-center mb-3">Selamat Datang 👋</h1>
                <p class="text-secondary text-center mb-4">Masuk untuk melanjutkan ke dashboard.</p>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="password" type="password" name="password" required
                                class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">

                            <span class="input-group-text bg-white" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="passwordIcon"></i>
                            </span>
                        </div>

                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-3 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");
    const passwordIcon = document.querySelector("#passwordIcon");

    togglePassword.addEventListener("click", function () {
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);

        // Switch icon
        passwordIcon.classList.toggle("bi-eye");
        passwordIcon.classList.toggle("bi-eye-slash");
    });
</script>
@endsection
