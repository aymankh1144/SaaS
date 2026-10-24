# MenuSaaS Platform 🍽️

منصة SaaS متعددة المطاعم (Multi-tenant) لإدارة مينيوهات المطاعم والكافيهات، مبنية بـ **Laravel 10**.

> المجلد `mr-mkh/` تطبيق PHP/SQLite مستقل لمطعم واحد (MR. DONAR) وليس جزءاً من هذه المنصة؛ له إعداده الخاص.

## ✨ المميزات

### 👨‍💼 أدمن المنصة
- إضافة مطاعم جديدة برفع الشعار واختيار 6 ألوان للهوية البصرية
- توليد اسم مستخدم وكلمة مرور تلقائياً (تُعرض مرة واحدة فقط)
- تفعيل/تعطيل مطعم، إعادة تعيين كلمة مروره، أو حذفه مع كل بياناته

### 🏪 لوحة كل مطعم (معزولة عن باقي المطاعم)
- إدارة الفئات والأطباق (مع صور) والعروض والخصومات
- التحكم في توفر الأطباق (متوفر / غير متوفر)

### 📱 المينيو العام للزبائن
- يطبّق ألوان المطعم تلقائياً، ويعرض الأطباق المتوفرة والعروض السارية فقط

---

## 🚀 التثبيت

**المتطلبات:** PHP 8.1+ (مع إضافات `gd`, `mbstring`, `xml`, `curl`, `pdo_mysql` أو `pdo_sqlite`)، Composer، MySQL 5.7+ (أو SQLite للتجربة).

```bash
git clone https://github.com/aymankh1144/SaaS.git
cd SaaS

composer install
cp .env.example .env
php artisan key:generate
```

### 1) قاعدة البيانات
عدّل `DB_*` في `.env` ثم:
```bash
php artisan migrate
```
> للتجربة بدون MySQL: ضع `DB_CONNECTION=sqlite` واحذف باقي `DB_*`، ثم `touch database/database.sqlite`.

### 2) حساب أدمن المنصة (مطلوب)
لا توجد كلمة مرور افتراضية. ولّد الهاش وضعه في `.env`:
```bash
php artisan tinker --execute="echo Hash::make('كلمة-مرور-قوية');"
```
```dotenv
ADMIN_EMAIL=you@example.com
ADMIN_PASSWORD_HASH='$2y$12$.....'   # بين علامتي اقتباس مفردتين
```
بدونهما لا يستطيع أي شخص تسجيل الدخول كأدمن.

### 3) رفع الصور
```bash
php artisan storage:link
```

### 4) التشغيل
```bash
php artisan serve        # http://localhost:8000
```

### (اختياري) بيانات تجريبية — للتطوير فقط
```bash
php artisan db:seed
```
ينشئ مطعماً تجريبياً: `/restaurants/demo-restaurant/admin/login` ← `demo` / `password`.
الـ seeder يرفض العمل عندما `APP_ENV=production`.

---

## 🧪 الاختبارات
```bash
php artisan test
```
تعمل على SQLite في الذاكرة (مفعّلة في `phpunit.xml`) وتغطي: دخول الأدمن، إنشاء المطاعم، عزل بيانات المطاعم عن بعضها، رفع الصور وحذفها، التحقق من العروض، وإظهار المينيو العام.

---

## 🛣️ المسارات

| المسار | الوصف |
|---|---|
| `/` · `/about` · `/restaurants` | صفحات المنصة العامة |
| `/restaurants/{slug}` | المينيو العام للمطعم |
| `/admin/login` | دخول أدمن المنصة |
| `/admin/dashboard` | إدارة المطاعم |
| `/restaurants/{slug}/admin/login` | دخول صاحب المطعم |
| `/restaurants/{slug}/admin/dashboard` | لوحة المطعم |
| `/restaurants/{slug}/admin/{categories,dishes,offers}` | إدارة الفئات والأطباق والعروض |

تسجيل الخروج يتم بطلب `POST` فقط.

---

## 🔐 الأمان

- **عزل المطاعم:** كل استعلام في لوحة المطعم مقيّد بمطعم الجلسة، فلا يمكن لمطعم قراءة أو تعديل بيانات غيره بتغيير رقم في الرابط.
- **Rate limiting:** 5 محاولات دخول في الدقيقة (أدمن وأصحاب المطاعم).
- **الجلسات:** تُجدَّد بعد تسجيل الدخول والخروج (ضد session fixation)، ويُنهى دخول المطعم فوراً عند تعطيله.
- **الألوان** تُتحقق بصيغة `#RRGGBB` فقط (لا حقن CSS)، والصور بامتدادات `jpg/png/webp` وحجم أقصى.
- **كلمات المرور** مخزّنة مشفّرة (bcrypt) ولا تُحفظ أي أسرار في الكود.

### قبل النشر في الإنتاج
```dotenv
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
```
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
واجعل جذر الخادم (document root) هو مجلد `public/` فقط.

---

## 📊 قاعدة البيانات

`restaurants` · `color_schemes` · `categories` · `dishes` · `offers` · `restaurant_users`
(انظر `database/migrations`). حذف مطعم يحذف فئاته وأطباقه وعروضه (`ON DELETE CASCADE`).

---

## 🎯 قيد التطوير

- [ ] دخول الموظفين بأدوار (owner / manager / staff) — الجدول `restaurant_users` جاهز دون واجهة
- [ ] نظام الدفع والاشتراكات
- [ ] نظام الطلبات (Order Management)
- [ ] تحليلات متقدمة · تقييمات الزبائن · تكامل مع منصات التوصيل

---

## 📄 الترخيص

MIT License

**صُنع بـ ❤️ بواسطة Ayman Kh**
