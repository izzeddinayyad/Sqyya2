<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>@yield('title')</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    {{-- الخطوط --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet" />
    
    {{-- ملف css خاص بكل صفحة --}}
    @yield('styles')
</head>
<body>
    <div class="container">
        <div class="flex login-content">
            {{-- الصورة الجانبية --}}
            <div class="image-section">
                <img src="{{ asset('images/photo_2025-04-23_22-09-33.jpg') }}" alt="login-image" />
            </div>

            {{-- محتوى الصفحة --}}
            <div class="form-section flex">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- سكريبتات خاصة بكل صفحة --}}
    @yield('scripts')
</body>
</html>
