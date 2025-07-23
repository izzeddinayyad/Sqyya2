<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- font links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <title>تسجيل الدخول - SqyyaAlmiyah</title>
</head>
<body>
    <div class="container">
        <div class="flex login-content">
            <!-- right section -->
            <div class="image-section">
                <img src="{{ asset('images/photo_2025-04-23_22-09-33.jpg') }}" alt="login-image" />
            </div>
            <!-- form section -->
            <div class="form-section flex">
                <h2>تسجيل الدخول</h2>
                <!-- عرض رسالة الخطأ العامة بشكل بارز -->
                @if (session()->has('error'))
                    <div class="alert alert-danger" style="background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 10px 18px; border-radius: 6px; margin-bottom: 16px; font-size: 1em; text-align: right;">
                        <strong>خطأ!</strong> {{ session('error') }}
                    </div>
                @endif
                <!-- عرض جميع الأخطاء العامة -->
                @if ($errors->any())
                    <div class="error-message" style="margin-bottom: 10px; color: red;">
                        <ul style="margin: 0; padding-right: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" placeholder="example@email.com"
                            value="{{ old('email') }}" required autofocus autocomplete="email"
                            class="@error('email') is-invalid @enderror" />
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور"
                            required autocomplete="current-password" class="@error('password') is-invalid @enderror" />
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-check" style="margin: 10px 0;">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }} />
                        <label class="form-check-label" for="remember">
                            تذكرني
                        </label>
                    </div>
                    <button type="submit">تسجيل الدخول</button>
                    @if (Route::has('password.request'))
                        <div style="margin-top: 10px;">
                            <a href="{{ route('password.request') }}">هل نسيت كلمة المرور؟</a>
                        </div>
                    @endif
                </form>
                <div class="toRegisterSection">
                    <span>ليس لديك حساب ؟ <a href="{{ route('register') }}">سجل الآن</a></span>
                </div>
            </div>
        </div>
    </div>
    <!-- إذا لم يكن هناك ملف js/login.js يمكنك حذف السطر التالي -->
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
