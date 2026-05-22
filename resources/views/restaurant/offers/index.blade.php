@extends('layouts.app')

@section('title', 'العروض')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="fw-bold">
            <i class="fas fa-tag"></i> إدارة العروض
        </h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('restaurant.offers.create', $restaurant->slug) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة عرض جديد
        </a>
    </div>
</div>

@if($offers->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>عنوان العرض</th>
                    <th>نوع الخصم</th>
                    <th>القيمة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $offer->title }}</strong>
                        </td>
                        <td>
                            @if($offer->discount_type === 'percentage')
                                <span class="badge bg-info">نسبة مئوية %</span>
                            @else
                                <span class="badge bg-warning">مبلغ ثابت</span>
                            @endif
                        </td>
                        <td>
                            @if($offer->discount_type === 'percentage')
                                {{ $offer->discount_value }}%
                            @else
                                {{ number_format($offer->discount_value, 2) }} ر.س
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $offer->start_date->format('Y-m-d') }} إلى {{ $offer->end_date->format('Y-m-d') }}</small>
                        </td>
                        <td>
                            @if($offer->isActive())
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('restaurant.offers.edit', [$restaurant->slug, $offer->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('restaurant.offers.destroy', [$restaurant->slug, $offer->id]) }}" method="POST" style="display: inline;">
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
        {{ $offers->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <h4><i class="fas fa-info-circle"></i> لا توجد عروض حالياً</h4>
        <p class="text-muted mt-2">اضغط على "إضافة عرض جديد" للبدء</p>
        <a href="{{ route('restaurant.offers.create', $restaurant->slug) }}" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i> أضف العرض الأول
        </a>
    </div>
@endif
@endsection