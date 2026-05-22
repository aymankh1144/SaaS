@extends('layouts.app')

@section('title', 'إضافة طبق جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus"></i> إضافة طبق جديد
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('restaurant.dishes.store', $restaurant->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">
                            <i class="fas fa-folder"></i> الفئة
                        </label>
                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- اختر فئة --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->icon ?? '📂' }} {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="fas fa-utensils"></i> اسم الطبق
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="مثال: بيتزا مرغريتا" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">
                            <i class="fas fa-align-left"></i> الوصف
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="وصف الطبق"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave"></i> السعر
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="0.00" step="0.01" min="0" required>
                                <span class="input-group-text">ر.س</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label fw-bold">
                                <i class="fas fa-image"></i> صورة الطبق
                            </label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available" checked>
                            <label class="form-check-label" for="is_available">
                                <i class="fas fa-check-circle"></i> الطبق متوفر الآن
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="is_vegan" name="is_vegan">
                            <label class="form-check-label" for="is_vegan">
                                <i class="fas fa-leaf"></i> طبق نباتي
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="is_spicy" name="is_spicy">
                            <label class="form-check-label" for="is_spicy">
                                <i class="fas fa-fire"></i> طبق حار
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="fas fa-check"></i> إضافة الطبق
                        </button>
                        <a href="{{ route('restaurant.dishes.index', $restaurant->slug) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection