@extends('layouts.app')

@section('title', 'الأطباق')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="fw-bold">
            <i class="fas fa-plate-wheat"></i> إدارة الأطباق
        </h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('restaurant.dishes.create', $restaurant->slug) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة طبق جديد
        </a>
    </div>
</div>

@if($dishes->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الصورة</th>
                    <th>اسم الطبق</th>
                    <th>الفئة</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dishes as $dish)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($dish->image_path)
                                <img src="{{ asset('storage/' . $dish->image_path) }}" alt="{{ $dish->name }}" style="height: 40px; width: 40px; object-fit: cover; border-radius: 5px;">
                            @else
                                <span class="badge bg-light text-dark">بدون صورة</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $dish->name }}</strong>
                            @if($dish->is_vegan)
                                <span class="badge bg-success ms-2">نباتي 🌱</span>
                            @endif
                            @if($dish->is_spicy)
                                <span class="badge bg-danger ms-1">حار 🌶️</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $dish->category->name ?? 'بدون فئة' }}</small>
                        </td>
                        <td>
                            <strong>{{ number_format($dish->price, 2) }} ر.س</strong>
                        </td>
                        <td>
                            @if($dish->is_available)
                                <span class="badge bg-success">متوفر</span>
                            @else
                                <span class="badge bg-danger">غير متوفر</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('restaurant.dishes.edit', [$restaurant->slug, $dish->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('restaurant.dishes.destroy', [$restaurant->slug, $dish->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $dishes->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <h4><i class="fas fa-info-circle"></i> لا توجد أطباق حالياً</h4>
        <p class="text-muted mt-2">اضغط على "إضافة طبق جديد" للبدء</p>
        <a href="{{ route('restaurant.dishes.create', $restaurant->slug) }}" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i> أضف الطبق الأول
        </a>
    </div>
@endif
@endsection