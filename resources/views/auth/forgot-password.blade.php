@extends('layouts.auth')

@section('title', 'إعادة تعيين كلمة المرور - SqyyaAlmiyah')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
    <h2>إعادة تعيين كلمة المرور</h2>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-message" style="margin-bottom: 10px; color: red;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="reset-method-tabs" style="margin-bottom: 20px;">
        <button type="button" id="email-tab" class="tab-btn active">عبر البريد الإلكتروني</button>
        <button type="button" id="phone-tab" class="tab-btn">عبر رقم الجوال</button>
    </div>

    <!-- إعادة تعيين عبر البريد الإلكتروني -->
    <form id="email-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label for="email">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </div>
        <button type="submit">إرسال رابط إعادة التعيين</button>
    </form>

    <!-- إعادة تعيين عبر رقم الجوال -->
    <form id="phone-form" method="POST" action="{{ route('password.sms.send') }}" style="display: none;">
        @csrf
        <div>
            <label for="phone">رقم الجوال</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="05XXXXXXXX" required autocomplete="tel">
        </div>
        <button type="submit">إرسال رمز التحقق</button>
    </form>
@endsection

@section('scripts')
<script>
    // تبديل بين طريقتي إعادة التعيين
    document.getElementById('email-tab').onclick = function() {
        this.classList.add('active');
        document.getElementById('phone-tab').classList.remove('active');
        document.getElementById('email-form').style.display = '';
        document.getElementById('phone-form').style.display = 'none';
    };
    document.getElementById('phone-tab').onclick = function() {
        this.classList.add('active');
        document.getElementById('email-tab').classList.remove('active');
        document.getElementById('email-form').style.display = 'none';
        document.getElementById('phone-form').style.display = '';
    };
</script>
@endsection 