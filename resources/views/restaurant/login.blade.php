@extends('layouts.app')

@section('title', 'دخول المطعم')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        @if($restaurant->logo_path)
                            <img src="{{ asset('storage/' . $restaurant->logo_path) }}" alt="{{ $restaurant->name }}" style="height: 80px; object-fit: contain; margin-bottom: 15px;">
                        @else
                            <div style="font-size: 3rem; color: #FF6B6B; margin-bottom: 15px;">
                                <i class="fas fa-utensils"></i>
                            </div>
                        @endif
                        <h2 class="fw-bold mb-1">{{ $restaurant->name }}</h2>
                        <p class="text-muted">لوحة تحكم المطعم</p>
                    </div>

                    <form action="{{ route('restaurant.login.store', $restaurant->slug) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">
                                <i class="fas fa-user"></i> اسم المستخدم
                            </label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock"></i> كلمة المرور
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">
                            <i class="fas fa-sign-in-alt"></i> دخول
                        </button>

                        <a href="{{ route('menu.show', $restaurant->slug) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-eye"></i> عرض المينيو
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection