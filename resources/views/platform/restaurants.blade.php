@extends('layouts.app')

@section('title', 'المطاعم - منصة المينيوهات')

@section('content')
<h1 class="mb-5 fw-bold">
    <i class="fas fa-store"></i> المطاعم والكافيهات
</h1>

@if($restaurants->count() > 0)
    <div class="row g-4 mb-5">
        @foreach($restaurants as $restaurant)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 overflow-hidden">
                    @if($restaurant->logo_path)
                        <img src="{{ asset('storage/' . $restaurant->logo_path) }}" class="card-img-top" alt="{{ $restaurant->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $restaurant->name }}</h5>
                        <p class="card-text text-muted small">
                            معرف المطعم: <code>{{ $restaurant->slug }}</code>
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('menu.show', $restaurant->slug) }}" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-eye"></i> عرض المينيو
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $restaurants->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <h4><i class="fas fa-info-circle"></i> لا توجد مطاعم متاحة حالياً</h4>
        <p class="text-muted mt-2">سيتم إضافة المطاعم قريباً</p>
    </div>
@endif
@endsection