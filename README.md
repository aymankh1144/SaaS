# MenuSaaS Platform 🍽️

منصة SaaS متقدمة لإدارة مينيوهات المطاعم والكافيهات بشكل احترافي وسهل.

## ✨ المميزات

### 👨‍💼 لوحة التحكم الرئيسية (للمنصة)
- ✅ إضافة ومدارة المطاعم
- ✅ تحميل اللوغو
- ✅ اختيار 6 ألوان متناسقة (الهوية البصرية)
- ✅ إنشاء بيانات اعتماد تلقائية (اسم مستخدم + كلمة مرور)
- ✅ تفعيل/تعطيل المطاعم

### 🏪 لوحات التحكم الخاصة بكل مطعم
كل مطعم له لوحة تحكم منفصلة تماماً:
- ✅ إدارة الفئات (الأطباق، المشروبات، الحلويات، إلخ)
- ✅ إدارة الأطباق (إضافة، تعديل، حذف)
- ✅ رفع صور الأطباق
- ✅ إدارة العروض والخصومات
- ✅ التحكم في توفر الأطباق

### 🎨 نظام الهوية البصرية المرن
- 6 ألوان قابلة للتخصيص لكل مطعم:
  - اللون الأساسي
  - اللون الثانوي
  - اللون الإضافي
  - لون النص
  - لون الحدود
  - لون الخلفية

### 📱 مينيو إلكتروني للزبائن
- تصميم جميل وسهل الاستخدام
- تطبيق الألوان المخصصة تلقائياً
- عرض الأطباق المتوفرة فقط
- عرض العروض النشطة

---

## 🚀 الخطوات الأساسية للتثبيت

### 1️⃣ المتطلبات الأساسية
```bash
- PHP 8.1+
- Composer
- MySQL 5.7+
- Node.js (اختياري، للـ CSS/JS الإضافية)
```

### 2️⃣ تثبيت المشروع
```bash
# استنساخ الريبو
git clone https://github.com/aymankh1144/SaaS.git
cd SaaS

# تثبيت المزيجات
composer install

# نسخ ملف البيئة
cp .env.example .env

# إنشاء مفتاح التطبيق
php artisan key:generate

# إنشاء قاعدة البيانات والجداول
php artisan migrate

# (اختياري) تعبئة بيانات تجريبية
php artisan db:seed
```

### 3️⃣ تشغيل المشروع
```bash
# بدء خادم التطوير
php artisan serve

# سيكون الموقع متاحاً على: http://localhost:8000
```

---

## 📊 بنية قاعدة البيانات

### الجداول الرئيسية:

#### `restaurants` - المطاعم
```sql
- id: معرف المطعم
- name: اسم المطعم
- slug: رابط فريد (مثلا: pizza-palace-abc123)
- logo_path: مسار اللوغو
- admin_email: بريد المدير
- admin_username: اسم مستخدم المدير
- admin_password: كلمة مرور المدير (مشفرة)
- is_active: حالة المطعم
```

#### `color_schemes` - نظام الألوان
```sql
- restaurant_id: المطعم
- primary_color: اللون الأساسي
- secondary_color: اللون الثانوي
- accent_color: اللون الإضافي
- text_color: لون النص
- border_color: لون الحدود
- background_color: لون الخلفية
```

#### `categories` - الفئات
```sql
- restaurant_id: المطعم
- name: اسم الفئة (مثلا: بيتزا، شاورما)
- description: الوصف
- icon: رمز الفئة
- sort_order: الترتيب
```

#### `dishes` - الأطباق
```sql
- restaurant_id: المطعم
- category_id: الفئة
- name: اسم الطبق
- description: الوصف
- price: السعر
- image_path: صورة الطبق
- is_available: متوفر أم لا
- is_vegan: هل الطبق نباتي
- is_spicy: هل الطبق حار
```

#### `offers` - العروض
```sql
- restaurant_id: المطعم
- title: عنوان العرض
- discount_type: نوع الخصم (نسبة / مبلغ ثابت)
- discount_value: قيمة الخصم
- start_date: تاريخ البداية
- end_date: تاريخ النهاية
```

#### `restaurant_users` - موظفو المطاعم
```sql
- restaurant_id: المطعم
- username: اسم المستخدم
- email: البريد الإلكتروني
- password: كلمة المرور
- role: الدور (owner, manager, staff)
```

---

## 🔐 نظام المصادقة والأمان

### مستويات الوصول:

#### 1️⃣ **الأدمن (أنت)**
- رابط منفصل: `/admin/login`
- إدارة جميع المطاعم
- إضافة/تعديل/حذف المطاعم
- الحقوق الكاملة

#### 2️⃣ **صاحب/مدير المطعم**
- رابط منفصل: `/restaurants/{slug}/admin/login`
- إدارة بيانات مطعمه فقط
- إدارة الفئات والأطباق والعروض
- عرض الإحصائيات

#### 3️⃣ **الموظفون**
- نفس الرابط مع صلاحيات محدودة
- يمكنهم تعديل معلومات محددة

---

## 📝 أمثلة الاستخدام

### مثال: إضافة مطعم جديد

```php
// من خلال لوحة التحكم أو API
$restaurant = Restaurant::create([
    'name' => 'بيتزا بالاتشو',
    'slug' => 'pizza-palazzo-xyz',
    'admin_email' => 'owner@pizza.com',
    'admin_username' => 'pizzaowner',
    'admin_password' => Hash::make('securePassword123'),
]);

// إنشاء نظام الألوان
ColorScheme::create([
    'restaurant_id' => $restaurant->id,
    'primary_color' => '#FF6B6B',      // أحمر
    'secondary_color' => '#FFA500',    // برتقالي
    'accent_color' => '#FFD700',       // ذهبي
    'text_color' => '#333333',         // رمادي غامق
    'border_color' => '#CCCCCC',       // رم��دي فاتح
    'background_color' => '#FFFFFF',   // أبيض
]);
```

### مثال: إضافة فئة وأطباق

```php
// إضافة فئة
$category = $restaurant->categories()->create([
    'name' => 'بيتزا كلاسيكية',
    'icon' => '🍕',
    'sort_order' => 1,
]);

// إضافة طبق
$dish = $restaurant->dishes()->create([
    'category_id' => $category->id,
    'name' => 'بيتزا مرغريتا',
    'description' => 'جبنة موتزاريلا طازة وصلصة طماطم',
    'price' => 45.00,
    'is_available' => true,
    'is_vegan' => true,
    'is_spicy' => false,
]);
```

---

## 🛣️ المسارات الرئيسية

### للعامة:
- `/` - الصفحة الرئيسية
- `/about` - معلومات المنصة
- `/restaurants` - قائمة المطاعم
- `/restaurants/{slug}` - مينيو المطعم

### للأدمن:
- `/admin/login` - تسجيل الدخول
- `/admin/dashboard` - لوحة التحكم
- `/admin/restaurants` - إدارة المطاعم

### لكل مطعم:
- `/restaurants/{slug}/admin/login` - تسجيل الدخول
- `/restaurants/{slug}/admin/dashboard` - لوحة التحكم
- `/restaurants/{slug}/admin/categories` - إدارة الفئات
- `/restaurants/{slug}/admin/dishes` - إدارة الأطباق
- `/restaurants/{slug}/admin/offers` - إدارة العروض

---

## 🎯 الميزات المستقبلية

- [ ] نظام الدفع والاشتراكات
- [ ] تطبيق موبايل
- [ ] تحليلات متقدمة
- [ ] نظام الطلبات (Order Management)
- [ ] تقييمات الزبائن
- [ ] تكامل مع منصات التوصيل

---

## 📄 الترخيص

MIT License - استخدم بحرية!

---

## 💬 الدعم والتواصل

للأسئلة والدعم:
- 📧 البريد الإلكتروني: support@menusaas.com
- 🐙 GitHub Issues: [اضغط هنا](https://github.com/aymankh1144/SaaS/issues)

---

**صُنع بـ ❤️ بواسطة Ayman Kh**
