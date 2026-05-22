@extends('layouts.app')

@section('title', 'إضافة عرض جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus"></i> إضافة عرض جديد
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('restaurant.offers.store', $restaurant->slug) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">
                            <i class="fas fa-tag"></i> عنوان العرض
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="مثال: خصم 20% على كل شيء" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="discount_type" class="form-label fw-bold">
                                <i class="fas fa-percent"></i> نوع الخصم
                            </label>
                            <select class="form-control @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required onchange="updateUnit()">
                                <option value="percentage">نسبة مئوية %</option>
                                <option value="fixed">مبلغ ثابت ر.س</option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="discount_value" class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave"></i> القيمة
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" placeholder="0" step="0.01" min="0" required>
                                <span class="input-group-text" id="unit">%</span>
                            </div>
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt"></i> تاريخ البداية
                            </label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt"></i> تاريخ النهاية
                            </label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="fas fa-check"></i> إضافة العرض
                        </button>
                        <a href="{{ route('restaurant.offers.index', $restaurant->slug) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateUnit() {
        const type = document.getElementById('discount_type').value;
        const unit = document.getElementById('unit');
        unit.textContent = type === 'percentage' ? '%' : 'ر.س';
    }
</script>
@endsection