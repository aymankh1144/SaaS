@extends('layouts.app')

@section('title', 'إضافة مطعم جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus"></i> إضافة مطعم جديد
                </h4>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Info -->
                    <h5 class="fw-bold mb-3">المعلومات الأساسية</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="fas fa-utensils"></i> اسم المطعم
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="مثال: بيتزا بالاتشو" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope"></i> البريد الإلكتروني
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="owner@restaurant.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label fw-bold">
                            <i class="fas fa-image"></i> لوغو المطعم
                        </label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                        <small class="text-muted">صيغ مدعومة: JPG, PNG, GIF (الحد الأقصى: 2MB)</small>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <!-- Colors -->
                    <h5 class="fw-bold mb-3">الهوية البصرية - اختر 6 ألوان</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="primary_color" class="form-label fw-bold">
                                <i class="fas fa-palette"></i> اللون الأساسي
                            </label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror" id="primary_color" name="primary_color" value="#FF6B6B" required>
                                <input type="text" class="form-control @error('primary_color') is-invalid @enderror" id="primary_color_text" readonly>
                            </div>
                            @error('primary_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="secondary_color" class="form-label fw-bold">
                                <i class="fas fa-palette"></i> اللون الثانوي
                            </label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror" id="secondary_color" name="secondary_color" value="#4ECDC4" required>
                                <input type="text" class="form-control @error('secondary_color') is-invalid @enderror" id="secondary_color_text" readonly>
                            </div>
                            @error('secondary_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="accent_color" class="form-label fw-bold">
                                <i class="fas fa-palette"></i> اللون الإضافي
                            </label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('accent_color') is-invalid @enderror" id="accent_color" name="accent_color" value="#FFE66D" required>
                                <input type="text" class="form-control @error('accent_color') is-invalid @enderror" id="accent_color_text" readonly>
                            </div>
                            @error('accent_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="text_color" class="form-label fw-bold">
                                <i class="fas fa-palette"></i> لون النص
                            </label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('text_color') is-invalid @enderror" id="text_color" name="text_color" value="#333333" required>
                                <input type="text" class="form-control @error('text_color') is-invalid @enderror" id="text_color_text" readonly>
                            </div>
                            @error('text_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="border_color" class="form-label fw-bold">
                                <i class="fas fa-palette"></i> لون الحدود
                            </label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('border_color') is-invalid @enderror" id="border_color" name="border_color" value="#CCCCCC" required>
                                <input type="text" class="form-control @error('border_color') is-invalid @enderror" id="border_color_text" readonly>
                            </div>
                            @error('border_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="background_color" class="form-label fw-bold">
                                <i class="fas fa-palette"></i> لون الخلفية
                            </label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('background_color') is-invalid @enderror" id="background_color" name="background_color" value="#FFFFFF" required>
                                <input type="text" class="form-control @error('background_color') is-invalid @enderror" id="background_color_text" readonly>
                            </div>
                            @error('background_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mb-4 p-4 rounded" id="colorPreview" style="background-color: #FFFFFF; border: 2px solid #CCCCCC;">
                        <h6 style="color: #333333;" class="mb-3">معاينة الألوان:</h6>
                        <div style="background-color: #FF6B6B; color: white; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                            هذا النص على اللون الأساسي
                        </div>
                        <div style="background-color: #4ECDC4; color: white; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                            هذا النص على اللون الثانوي
                        </div>
                        <div style="background-color: #FFE66D; color: #333333; padding: 10px; border-radius: 5px;">
                            هذا النص على اللون الإضافي
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="fas fa-check"></i> إنشاء المطعم
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Update color preview
    const colorInputs = ['primary_color', 'secondary_color', 'accent_color', 'text_color', 'border_color', 'background_color'];

    colorInputs.forEach(id => {
        const input = document.getElementById(id);
        const textInput = document.getElementById(id + '_text');
        
        input.addEventListener('input', function() {
            textInput.value = this.value;
        });
        
        // Set initial value
        textInput.value = input.value;
    });
</script>
@endsection