@extends('layouts.app')

@section('title', 'لوحة تحكم المطعم')

@section('content')
<div class="row align-items-center mb-5">
    <div class="col">
        <h1 class="fw-bold">
            @if($restaurant->logo_path)
                <img src="{{ asset('storage/' . $restaurant->logo_path) }}" alt="{{ $restaurant->name }}" style="height: 40px; object-fit: contain; margin-left: 10px;">
            @endif
            {{ $restaurant->name }}
        </h1>
        <p class="text-muted">لوحة التحكم الرئيسية</p>
    </div>
    <div class="col-auto">
        <form action="{{ route('restaurant.logout', $restaurant->slug) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h3 class="text-primary fw-bold">{{ $categoriesCount }}</h3>
            <p class="text-muted mb-0">الفئات</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h3 class="text-success fw-bold">{{ $dishesCount }}</h3>
            <p class="text-muted mb-0">الأطباق</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h3 class="text-info fw-bold">{{ $offersCount }}</h3>
            <p class="text-muted mb-0">العروض</p>
        </div>
    </div>
</div>

<!-- Menu Management -->
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div style="font-size: 2.5rem; color: #FF6B6B; margin-bottom: 15px;">
                    <i class="fas fa-folder"></i>
                </div>
                <h5 class="card-title fw-bold">إدارة الفئات</h5>
                <p class="card-text text-muted small">أضف وعدّل فئات الأطباق (بيتزا، شاورما، إلخ)</p>
                <a href="{{ route('restaurant.categories.index', $restaurant->slug) }}" class="btn btn-primary btn-sm w-100 mt-3">
                    <i class="fas fa-eye"></i> عرض الفئات
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div style="font-size: 2.5rem; color: #4ECDC4; margin-bottom: 15px;">
                    <i class="fas fa-plate-wheat"></i>
                </div>
                <h5 class="card-title fw-bold">إدارة الأطباق</h5>
                <p class="card-text text-muted small">أضف وعدّل الأطباق والأسعار والصور</p>
                <a href="{{ route('restaurant.dishes.index', $restaurant->slug) }}" class="btn btn-primary btn-sm w-100 mt-3">
                    <i class="fas fa-eye"></i> عرض الأطباق
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div style="font-size: 2.5rem; color: #FFE66D; margin-bottom: 15px;">
                    <i class="fas fa-tag"></i>
                </div>
                <h5 class="card-title fw-bold">إدارة العروض</h5>
                <p class="card-text text-muted small">أضف عروض وخصومات خاصة</p>
                <a href="{{ route('restaurant.offers.index', $restaurant->slug) }}" class="btn btn-primary btn-sm w-100 mt-3">
                    <i class="fas fa-eye"></i> عرض العروض
                </a>
            </div>
        </div>
    </div>
</div>

<!-- View Menu -->
<div class="mt-5 text-center p-4 rounded" style="background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%); color: white;">
    <h4 class="fw-bold mb-3">معاينة المينيو الخاص بك</h4>
    <p class="mb-3">اضغط هنا لعرض مينيوك كما يراه الزبائن</p>
    <a href="{{ route('menu.show', $restaurant->slug) }}" class="btn btn-light btn-lg">
        <i class="fas fa-eye"></i> عرض المينيو
    </a>
</div>
@endsection