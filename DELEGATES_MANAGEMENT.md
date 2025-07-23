# نظام إدارة المندوبين (Delegates Management)

## نظرة عامة

تم تفعيل صفحة إدارة المندوبين مع الحفاظ على نظام العزل بين المؤسسات. كل صاحب مؤسسة يرى فقط مندوبي مؤسسته.

## الميزات المطورة

### 📋 جدول المندوبين
- **#**: رقم تسلسلي للمندوب في المؤسسة
- **الاسم الكامل**: اسم المندوب كما سجل
- **الجوال**: رقم الهاتف للتواصل الميداني
- **المدينة**: المنطقة التي يعمل فيها
- **عدد المحطات**: عدد محطات التحلية التي يتابعها
- **عدد الطلبات**: إجمالي الطلبات التي أتمها أو يتابعها
- **آخر نشاط**: وقت آخر نشاط للمندوب
- **الحالة**: نشط / غير نشط
- **إجراءات**: عرض، تعديل، تعطيل/تفعيل، حذف

### 🔧 الوظائف المتاحة

#### 1. عرض المندوبين
- عرض جميع مندوبي المؤسسة فقط
- فلترة حسب المؤسسة (نظام العزل)

#### 2. إضافة مندوب جديد
- نموذج إضافة مندوب مع التحقق من البيانات
- تعيين المؤسسة تلقائياً

#### 3. تعديل بيانات المندوب
- تعديل جميع بيانات المندوب
- تغيير كلمة المرور (اختياري)

#### 4. تعطيل/تفعيل المندوب
- تغيير حالة المندوب من نشط إلى غير نشط والعكس
- منع الوصول للمندوب المعطل

#### 5. حذف المندوب
- حذف المندوب نهائياً من النظام
- تأكيد قبل الحذف

## هيكل قاعدة البيانات

### جدول المستخدمين (users)
- `id`: المعرف الفريد
- `name`: اسم المندوب
- `email`: البريد الإلكتروني
- `password`: كلمة المرور
- `role`: نوع المستخدم (`representative`)
- `phone`: رقم الهاتف
- `city`: المدينة
- `institution_id`: معرف المؤسسة
- `status`: الحالة (`active`, `inactive`)

### جدول طلبات المياه (water_requests)
- `id`: المعرف الفريد
- `user_id`: معرف المستخدم الطالب
- `representative_id`: معرف المندوب المسؤول
- `type`: نوع الطلب (`point`, `tanker`)
- `emergency`: طلب عاجل
- `quantity`: الكمية المطلوبة
- `status`: حالة الطلب
- `scheduled_time`: وقت الجدولة

## العلاقات

### في نموذج User
```php
public function orders()
{
    return $this->hasMany(WaterRequest::class, 'representative_id');
}

public function stations()
{
    return $this->hasMany(Station::class, 'representative_id');
}
```

### في نموذج WaterRequest
```php
public function representative()
{
    return $this->belongsTo(User::class, 'representative_id');
}
```

## المسارات المتاحة

### عرض المندوبين
```
GET /organization/delegates
```

### إضافة مندوب
```
POST /organization/delegates
```

### تعديل مندوب
```
GET /organization/delegates/{delegate}/edit
PUT /organization/delegates/{delegate}
```

### حذف مندوب
```
DELETE /organization/delegates/{delegate}
```

### تغيير حالة المندوب
```
POST /organization/delegates/{delegate}/toggle-status
```

## البيانات التجريبية

تم إنشاء بيانات تجريبية للمندوبين:

### المؤسسة الأولى (الرياض)
- **أحمد الشريف**: 24 طلب، نشط
- **سارة العلي**: 10 طلبات، غير نشط

### المؤسسة الثانية (جدة)
- **محمود حسن**: 15 طلب، نشط

## الأمان

- **عزل المؤسسات**: كل مؤسسة ترى فقط مندوبيها
- **التحقق من الصلاحيات**: middleware للتحقق من الوصول
- **فلترة البيانات**: جميع الاستعلامات تُفلتر حسب `institution_id`
- **التحقق من البيانات**: validation لجميع المدخلات

## الواجهة

### التصميم
- تصميم متجاوب ومتسق مع باقي الصفحات
- ألوان واضحة للحالات المختلفة
- أيقونات Font Awesome
- تأثيرات hover للتفاعل

### التفاعل
- أزرار الإجراءات مع tooltips
- رسائل تأكيد للحذف
- رسائل نجاح للعمليات
- عرض رسالة عند عدم وجود مندوبين

## الملفات المضافة/المعدلة

### الملفات الجديدة
- `resources/views/organization/delegates.blade.php`
- `app/Models/WaterRequest.php`
- `app/Models/DistributionPoint.php`
- `database/migrations/2025_06_26_150000_add_status_to_users_table.php`
- `database/migrations/2025_06_26_160000_add_representative_id_to_water_requests_table.php`
- `database/seeders/DelegatesSeeder.php`
- `database/seeders/WaterRequestsSeeder.php`
- `DELEGATES_MANAGEMENT.md`

### الملفات المعدلة
- `app/Http/Controllers/OrganizationController.php`
- `app/Models/User.php`
- `routes/web.php`

## كيفية الاختبار

1. سجل دخول كصاحب المؤسسة الأولى (`ahmed@institution1.com`)
2. انتقل إلى صفحة "المندوبون"
3. ستجد مندوبين المؤسسة الأولى فقط
4. جرب الوظائف المختلفة (إضافة، تعديل، تعطيل، حذف)
5. سجل دخول كصاحب المؤسسة الثانية (`fatima@institution2.com`)
6. ستجد مندوبين المؤسسة الثانية فقط

## ملاحظات تقنية

- تم الحفاظ على نظام العزل المطبق سابقاً
- جميع الوظائف تعمل مع نظام العزل
- البيانات التجريبية تشمل إحصائيات واقعية
- الواجهة متوافقة مع التصميم العام للمشروع 