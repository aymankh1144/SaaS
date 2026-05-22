@extends('layouts.app')

@section('title', $restaurant->name . ' - المينيو')

@section('content')
<!-- Hero Section -->
<div class="mb-5" style="background: linear-gradient(135deg, {{ $restaurant->colorScheme->primary_color ?? '#FF6B6B' }} 0%, {{ $restaurant->colorScheme->secondary_color ?? '#4ECDC4' }} 100%); color: white; padding: 3rem; border-radius: 15px;">
    <div class="row align-items-center">
        <div class="col-md-3 text-center">
            @if($restaurant->logo_path)
                <img src="{{ asset('storage/' . $restaurant->logo_path) }}" alt="{{ $restaurant->name }}" style="height: 120px; object-fit: contain; filter: brightness(0) invert(1);">
            @else
                <div style="font-size: 4rem;">
                    <i class="fas fa-utensils"></i>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <h1 class="display-5 fw-bold">{{ $restaurant->name }}</h1>
            <p class="lead mb-0">مينيو إلكتروني احترافي</p>
        </div>
    </div>
</div>

<!-- Offers Section -->
@if($offers->count() > 0)
    <div class="mb-5">
        <h3 class="fw-bold mb-3">
            <i class="fas fa-tag"></i> العروض الخاصة
        </h3>
        <div class="row g-3">
            @foreach($offers as $offer)
                <div class="col-md-6 col-lg-4">
                    <div class="card" style="border-left: 4px solid {{ $restaurant->colorScheme->accent_color ?? '#FFE66D' }};">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $offer->title }}</h5>
                            <div style="color: {{ $restaurant->colorScheme->primary_color ?? '#FF6B6B' }};" class="fw-bold">
                                @if($offer->discount_type === 'percentage')
                                    خصم {{ $offer->discount_value }}%
                                @else
                                    خصم {{ number_format($offer->discount_value, 2) }} ر.س
                                @endif
                            </div>
                            <small class="text-muted">ساري إلى {{ $offer->end_date->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Categories and Dishes -->
@if($categories->count() > 0)
    @foreach($categories as $category)
        <div class="mb-5">
            <h3 class="fw-bold mb-3" style="color: {{ $restaurant->colorScheme->primary_color ?? '#FF6B6B' }};">
                {{ $category->icon ?? '📂' }} {{ $category->name }}
            </h3>
            @if($category->description)
                <p class="text-muted mb-3">{{ $category->description }}</p>
            @endif

            @php
                $dishes = $category->dishes()->where('is_available', true)->get();
            @endphp

            @if($dishes->count() > 0)
                <div class="row g-4">
                    @foreach($dishes as $dish)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100" style="border: 2px solid {{ $restaurant->colorScheme->border_color ?? '#CCCCCC' }};">
                                @if($dish->image_path)
                                    <img src="{{ asset('storage/' . $dish->image_path) }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title fw-bold mb-0">{{ $dish->name }}</h5>
                                        <span style="color: {{ $restaurant->colorScheme->primary_color ?? '#FF6B6B' }};" class="badge">
                                            {{ number_format($dish->price, 2) }} ر.س
                                        </span>
                                    </div>
                                    <p class="card-text text-muted small">{{ Str::limit($dish->description, 100) }}</p>
                                    <div class="d-flex gap-2">
                                        @if($dish->is_vegan)
                                            <span class="badge bg-success">نباتي 🌱</span>
                                        @endif
                                        @if($dish->is_spicy)
                                            <span class="badge bg-danger">حار 🌶️</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> لا توجد أطباق متاحة في هذه الفئة
                </div>
            @endif
        </div>
    @endforeach
@else
    <div class="alert alert-info text-center py-5">
        <h4><i class="fas fa-info-circle"></i> المينيو قيد الإنشاء</h4>
        <p class="text-muted">يرجى العودة لاحقاً</p>
    </div>
@endif
@endsection