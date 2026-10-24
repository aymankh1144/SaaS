@extends('layouts.app')

@section('title', 'الفئات')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="fw-bold">
            <i class="fas fa-folder"></i> إدارة الفئات
        </h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('restaurant.categories.create', $restaurant->slug) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة فئة جديدة
        </a>
    </div>
</div>

@if($categories->count() > 0)
    <div class="row g-3">
        @foreach($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title fw-bold mb-0">
                                {{ $category->icon ?? '📂' }} {{ $category->name }}
                            </h5>
                        </div>
                        @if($category->description)
                            <p class="card-text text-muted small">{{ $category->description }}</p>
                        @endif
                        <small class="text-muted">الأطباق: {{ $category->dishes_count }}</small>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('restaurant.categories.edit', [$restaurant->slug, $category->id]) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <form action="{{ route('restaurant.categories.destroy', [$restaurant->slug, $category->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد؟')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <h4><i class="fas fa-info-circle"></i> لا توجد فئات حالياً</h4>
        <p class="text-muted mt-2">اضغط على "إضافة فئة جديدة" للبدء</p>
        <a href="{{ route('restaurant.categories.create', $restaurant->slug) }}" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i> أضف الفئة الأولى
        </a>
    </div>
@endif
@endsection