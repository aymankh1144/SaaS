@extends('layouts.app')

@section('title', 'الرئيسية - منصة المينيوهات')

@section('content')
<!-- Hero Section -->
<div class="row mb-5 fade-in">
    <div class="col-lg-6 d-flex align-items-center">
        <div>
            <h1 class="display-4 fw-bold text-primary mb-3">
                <i class="fas fa-utensils"></i> MenuSaaS
            </h1>
            <p class="lead text-muted mb-4">
                منصة احترافية وسهلة الاستخدام لإنشاء وإدارة مينيوهات المطاعم والكافيهات الإلكترونية.
            </p>
            <div class="d-grid gap-2 d-sm-flex">
                <a href="{{ route('platform.restaurants') }}" class="btn btn-primary btn-lg px-4 gap-3">
                    <i class="fas fa-eye"></i> استكشف المطاعم
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-outline-primary btn-lg px-4">
                    <i class="fas fa-sign-in-alt"></i> دخول الأدمن
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-6 text-center">
        <img src="https://via.placeholder.com/500x400?text=Restaurant+Menu" alt="menu" class="img-fluid rounded-3">
    </div>
</div>

<!-- Features Section -->
<section class="py-5">
    <h2 class="text-center mb-5 fw-bold">المميزات الرئيسية</h2>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center p-4">
                <div style="font-size: 3rem; color: #FF6B6B;" class="mb-3">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h5 class="card-title">تصميم احترافي</h5>
                <p class="card-text text-muted">تصاميم عصرية وجميلة قابلة للتخصيص بالكامل</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center p-4">
                <div style="font-size: 3rem; color: #4ECDC4;" class="mb-3">
                    <i class="fas fa-cogs"></i>
                </div>
                <h5 class="card-title">سهل الاستخدام</h5>
                <p class="card-text text-muted">إدارة بسيطة بدون الحاجة لخبرة تقنية</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center p-4">
                <div style="font-size: 3rem; color: #FFE66D;" class="mb-3">
                    <i class="fas fa-palette"></i>
                </div>
                <h5 class="card-title">ألوان مخصصة</h5>
                <p class="card-text text-muted">اختر 6 ألوان لتعكس هوية مطعمك</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center p-4">
                <div style="font-size: 3rem; color: #95E1D3;" class="mb-3">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h5 class="card-title">متوافق مع الجوال</h5>
                <p class="card-text text-muted">يعمل بشكل مثالي على جميع الأجهزة</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 mt-5 rounded-3" style="background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%); color: white;">
    <div class="row text-center">
        <div class="col-md-3">
            <h3 class="display-5 fw-bold">{{ $restaurants->total() }}</h3>
            <p>مطعم وكافيه</p>
        </div>
        <div class="col-md-3">
            <h3 class="display-5 fw-bold">{{ $restaurants->count() }}</h3>
            <p>مطعم نشط الآن</p>
        </div>
        <div class="col-md-3">
            <h3 class="display-5 fw-bold">1000+</h3>
            <p>مينيو الكتروني</p>
        </div>
        <div class="col-md-3">
            <h3 class="display-5 fw-bold">99.9%</h3>
            <p>جودة الخدمة</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-center mt-5">
    <h2 class="mb-4 fw-bold">هل تريد إنشاء مينيو لمطعمك؟</h2>
    <p class="lead text-muted mb-4">انضم إلينا الآن وابدأ إدارة مينيو مطعمك الإلكتروني</p>
    <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg px-5">
        <i class="fas fa-rocket"></i> ابدأ الآن مجاناً
    </a>
</section>
@endsection