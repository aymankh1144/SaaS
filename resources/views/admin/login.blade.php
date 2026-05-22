@extends('layouts.app')

@section('title', 'دخول الأدمن')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary mb-2">
                            <i class="fas fa-user-shield"></i> لوحة التحكم
                        </h2>
                        <p class="text-muted">تسجيل دخول الأدمن</p>
                    </div>

                    <form action="{{ route('admin.login.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope"></i> البريد الإلكتروني
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock"></i> كلمة المرور
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="fas fa-sign-in-alt"></i> دخول
                        </button>
                    </form>

                    <div class="text-center mt-4 p-3 bg-light rounded">
                        <small class="text-muted">
                            <strong>بيانات تجريبية:</strong><br>
                            البريد: admin@menusaas.local<br>
                            كلمة المرور: admin123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection