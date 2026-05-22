@extends('layouts.app')

@section('title', 'عن المنصة')

@section('content')
<div class="container my-5">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 fw-bold mb-4">عن منصة MenuSaaS</h1>
            <p class="lead text-muted">
                نحن نقدم أفضل حل SaaS لإدارة مينيوهات المطاعم والكافيهات والفنادق بطريقة احترافية وسهلة.
            </p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <h3 class="fw-bold mb-3">رؤيتنا</h3>
            <p class="text-muted">
                جعل إدارة مينيوهات المطاعم سهلة وبسيطة للجميع، بدون الحاجة لخبرة تقنية أو استثمارات كبيرة.
                نريد أن يركز أصحاب المطاعم على جودة الطعام والخدمة، بينما نتولى إدارة المينيو الإلكتروني.
            </p>
        </div>
        <div class="col-lg-6">
            <h3 class="fw-bold mb-3">مهمتنا</h3>
            <p class="text-muted">
                توفير منصة موثوقة وسهلة الاستخدام لإنشاء وإدارة مينيوهات احترافية، مع الحفاظ على
                الهوية البصرية الفريدة لكل مطعم، وتقديم تجربة ممتازة للزبائن.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-bolt text-primary"></i> سرعة
                </h5>
                <p class="card-text text-muted">
                    إنشاء مينيو احترافي في دقائق، وليس ساعات أو أيام.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-lock text-success"></i> أمان
                </h5>
                <p class="card-text text-muted">
                    بيانات مطعمك محمية بأعلى مستويات الأمان والتشفير.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-headset text-info"></i> دعم
                </h5>
                <p class="card-text text-muted">
                    فريق دعم متخصص متاح دائماً لمساعدتك.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection