@extends('layouts.auth')

@section('title', 'تسجيل جديد - SqyyaAlmiyah')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
@endsection

@section('content')
    <h2>إنشاء حساب جديد</h2>

    @if (session('status'))
        <div class="alert alert-success" style="background: #e8f5e9; color: #388e3c; border: 1px solid #c8e6c9; padding: 10px 18px; border-radius: 6px; margin-bottom: 16px; font-size: 1em; text-align: right;">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" style="background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 10px 18px; border-radius: 6px; margin-bottom: 16px; font-size: 1em; text-align: right;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="flex">
            <div class="flex">
                <label for="name">الإسم كامل</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="ادخل الاسم كامل" required autocomplete="name">
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="flex">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required autocomplete="email">
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="flex">
            <div class="flex">
                <label for="phone">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="059*****" required autocomplete="tel">
                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="flex">
                <label for="user_type">نوع المستخدم</label>
                <select name="user_type" id="user_type" required>
                    <option disabled selected>اختر نوع المستخدم</option>
                    <option value="org_owner" @selected(old('user_type') == 'org_owner')>صاحب المؤسسة</option>
                    <option value="representative" @selected(old('user_type') == 'representative')>مندوب</option>
                    <option value="driver" @selected(old('user_type') == 'driver')>سائق الشاحنة</option>
                </select>
                @error('user_type')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="flex">
            <div class="flex">
                <label for="address">العنوان بالتفصيل</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="ادخل العنوان" required autocomplete="street-address">
                @error('address')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="flex">
                <label for="city">المحافظة</label>
                <select name="city" id="city" required>
                    <option disabled selected>اختر المحافظة</option>
                    <option value="شمال غزة" @selected(old('city') == 'شمال غزة')>شمال غزة</option>
                    <option value="غزة" @selected(old('city') == 'غزة')>غزة</option>
                    <option value="الوسطى" @selected(old('city') == 'الوسطى')>الوسطى</option>
                    <option value="خانيونس" @selected(old('city') == 'خانيونس')>خانيونس</option>
                    <option value="رفح" @selected(old('city') == 'رفح')>رفح</option>
                </select>
                @error('city')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="flex">
            <div class="flex">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" placeholder="********" required autocomplete="new-password">
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="flex">
                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="********" required autocomplete="new-password">
            </div>
        </div>
        <button type="submit">إنشاء حساب</button>
    </form>
    <div class="toRegisterSection">
        <span>لديك حساب؟ <a href="{{ route('login') }}">سجل الدخول الآن</a></span>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/register.js') }}"></script>
@endsection
