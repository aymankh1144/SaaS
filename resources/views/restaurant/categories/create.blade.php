@extends('layouts.app')

@section('title', 'إضافة فئة جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus"></i> إضافة فئة جديدة
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('restaurant.categories.store', $restaurant->slug) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="fas fa-folder"></i> اسم الفئة
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="مثال: بيتزا كلاسيكية" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label fw-bold">
                            <i class="fas fa-icons"></i> رمز الفئة (Emoji)
                        </label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" placeholder="مثال: 🍕" maxlength="5">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            <i class="fas fa-align-left"></i> الوصف
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="وصف الفئة (اختياري)"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="fas fa-check"></i> إضافة الفئة
                        </button>
                        <a href="{{ route('restaurant.categories.index', $restaurant->slug) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection