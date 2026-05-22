@extends('layouts.app')

@section('title', 'لوحة تحكم الأدمن')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="fw-bold">
            <i class="fas fa-tachometer-alt"></i> لوحة التحكم الرئيسية
        </h1>
        <p class="text-muted">مرحباً بك في منصة MenuSaaS</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة مطعم جديد
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h3 class="text-primary fw-bold">{{ $restaurants->total() }}</h3>
            <p class="text-muted mb-0">إجمالي المطاعم</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h3 class="text-success fw-bold">{{ $restaurants->count() }}</h3>
            <p class="text-muted mb-0">مطاعم نشطة</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h3 class="text-info fw-bold">{{ now()->format('H:i') }}</h3>
            <p class="text-muted mb-0">الوقت الحالي</p>
        </div>
    </div>
</div>

<!-- Restaurants Table -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-list"></i> قائمة المطاعم
        </h5>
    </div>
    <div class="card-body">
        @if($restaurants->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>اسم المطعم</th>
                            <th>المعرف</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restaurants as $restaurant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $restaurant->name }}</strong>
                                </td>
                                <td>
                                    <code>{{ $restaurant->slug }}</code>
                                </td>
                                <td>
                                    @if($restaurant->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">معطل</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $restaurant->created_at->format('Y-m-d') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('menu.show', $restaurant->slug) }}" class="btn btn-sm btn-outline-primary" title="عرض المينيو">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('restaurant.dashboard', $restaurant->slug) }}" class="btn btn-sm btn-outline-info" title="لوحة التحكم">
                                        <i class="fas fa-cogs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $restaurants->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <p class="mb-0">لا توجد مطاعم حالياً. اضغط على "إضافة مطعم جديد" للبدء</p>
            </div>
        @endif
    </div>
</div>

<!-- Logout -->
<div class="mt-4 text-center">
    <a href="{{ route('admin.logout') }}" class="btn btn-outline-danger">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </a>
</div>
@endsection