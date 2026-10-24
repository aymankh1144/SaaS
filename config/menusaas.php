<?php

return [

    /*
    | بيانات دخول أدمن المنصة. تُقرأ من ملف .env فقط ولا توجد قيم افتراضية
    | عن قصد: إذا لم تُضبط فلن يستطيع أحد تسجيل الدخول كأدمن.
    |
    | لتوليد الهاش:
    |   php artisan tinker --execute="echo Hash::make('كلمة-المرور-القوية');"
    | ثم ضعه بين علامتي اقتباس مفردتين في .env (لأنه يحتوي على $):
    |   ADMIN_PASSWORD_HASH='$2y$12$...'
    */
    'admin_email' => env('ADMIN_EMAIL'),
    'admin_password_hash' => env('ADMIN_PASSWORD_HASH'),

];
