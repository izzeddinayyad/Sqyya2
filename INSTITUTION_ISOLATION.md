# نظام عزل المؤسسات (Institution Isolation)

## نظرة عامة

تم تطبيق نظام عزل كامل بين المؤسسات في المشروع، حيث كل مؤسسة مستقلة تماماً عن الأخرى. كل صاحب مؤسسة يرى فقط بيانات مؤسسته الخاصة.

## هيكل قاعدة البيانات

### جدول المستخدمين (users)
- `id`: المعرف الفريد
- `name`: اسم المستخدم
- `email`: البريد الإلكتروني
- `password`: كلمة المرور
- `role`: نوع المستخدم (`org_owner`, `driver`, `representative`)
- `phone`: رقم الهاتف
- `city`: المدينة
- `institution_id`: معرف المؤسسة (null لصاحب المؤسسة)

### أنواع المستخدمين

#### 1. صاحب المؤسسة (org_owner)
- `institution_id` = `null`
- يمكنه الوصول لجميع بيانات مؤسسته
- يمكنه إدارة السائقين والمحطات والشاحنات

#### 2. السائق (driver)
- `institution_id` = معرف صاحب المؤسسة
- يمكنه الوصول لبيانات مؤسسته فقط

#### 3. المندوب (representative)
- `institution_id` = معرف صاحب المؤسسة
- يمكنه الوصول لبيانات مؤسسته فقط

## الجداول المرتبطة

### المحطات (stations)
- `institution_id`: معرف المؤسسة المالكة

### الشاحنات (trucks)
- `institution_id`: معرف المؤسسة المالكة
- `driver_id`: معرف السائق المعين

### السائقين (drivers)
- `institution_id`: معرف المؤسسة المالكة

## آلية العزل

### 1. في الكنترولر
```php
$institutionId = auth()->user()->institution_id ?? auth()->id();
$stations = Station::where('institution_id', $institutionId)->get();
```

### 2. في النماذج
```php
// User Model
public function getInstitutionId()
{
    return $this->institution_id ?? $this->id;
}

public function isInstitutionOwner()
{
    return $this->role === 'org_owner' && $this->institution_id === null;
}
```

### 3. في Middleware
```php
// CheckInstitutionAccess Middleware
public function handle(Request $request, Closure $next)
{
    $user = auth()->user();
    
    if ($user->role === 'org_owner') {
        return $next($request);
    }
    
    if ($user->institution_id) {
        return $next($request);
    }
    
    abort(403, 'غير مصرح لك بالوصول لهذه البيانات');
}
```

## البيانات التجريبية

تم إنشاء بيانات تجريبية لمؤسستين:

### المؤسسة الأولى (الرياض)
- **صاحب المؤسسة**: أحمد محمد (ahmed@institution1.com)
- **السائقين**: محمد أحمد، علي محمد
- **المحطات**: محطة التحلية الأولى، محطة التحلية الثانية
- **الشاحنات**: TRK-001، TRK-002

### المؤسسة الثانية (جدة)
- **صاحب المؤسسة**: فاطمة علي (fatima@institution2.com)
- **السائقين**: خالد فاطمة، سارة فاطمة
- **المحطات**: محطة جدة المركزية
- **الشاحنات**: JED-001، JED-002

## اختبار النظام

1. سجل دخول كصاحب المؤسسة الأولى
2. ستجد فقط محطات وشاحنات وسائقين المؤسسة الأولى
3. سجل دخول كصاحب المؤسسة الثانية
4. ستجد فقط محطات وشاحنات وسائقين المؤسسة الثانية

## الأمان

- كل استعلام يتم فلترته حسب `institution_id`
- لا يمكن الوصول لبيانات مؤسسة أخرى
- التحقق من صحة البيانات قبل الحفظ
- Middleware للتحقق من الصلاحيات

## الملفات المضافة/المعدلة

### الملفات الجديدة
- `app/Http/Middleware/CheckInstitutionAccess.php`
- `database/migrations/2025_06_26_140000_add_institution_id_to_trucks_table.php`
- `database/seeders/InstitutionSeeder.php`
- `INSTITUTION_ISOLATION.md`

### الملفات المعدلة
- `app/Http/Controllers/OrganizationController.php`
- `app/Models/User.php`
- `app/Models/Truck.php`
- `app/Models/Station.php`
- `app/Http/Kernel.php`
- `routes/web.php`
- `database/seeders/DatabaseSeeder.php` 