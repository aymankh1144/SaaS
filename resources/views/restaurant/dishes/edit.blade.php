@extends('layouts.app')

@section('title', 'تعديل الطبق')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-lg">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">
                    <i class="fas fa-edit"></i> تعديل الطبق
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('restaurant.dishes.update', [$restaurant->slug, $dish->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">
                            <i class="fas fa-folder"></i> الفئة
                        </label>
                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $dish->category_id == $category->id ? 'selected' : '' }}>{{ $category->icon ?? '📂' }} {{ $category->name }}</option>
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
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $dish->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">
                            <i class="fas fa-align-left"></i> الوصف
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ $dish->description }}</textarea>
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
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $dish->price }}" step="0.01" min="0" required>
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
                            @if($dish->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $dish->image_path) }}" alt="{{ $dish->name }}" style="height: 80px; object-fit: cover; border-radius: 5px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <small class="text-muted">اترك فارغاً للإبقاء على الصورة الحالية</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available" {{ $dish->is_available ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                <i class="fas fa-check-circle"></i> الطبق متوفر
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="is_vegan" name="is_vegan" {{ $dish->is_vegan ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_vegan">
                                <i class="fas fa-leaf"></i> طبق نباتي
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="is_spicy" name="is_spicy" {{ $dish->is_spicy ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_spicy">
                                <i class="fas fa-fire"></i> طبق حار
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-info btn-lg flex-grow-1">
                            <i class="fas fa-check"></i> حفظ التغييرات
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