@extends('layouts.auth')

@section('title', 'تعيين كلمة مرور جديدة')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
    <h2>تعيين كلمة مرور جديدة</h2>
    @if ($errors->any())
        <div class="error-message" style="margin-bottom: 10px; color: red;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('password.email.reset.submit') }}">
        @csrf
        <div>
            <label for="password">كلمة المرور الجديدة</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
        </div>
        <div>
            <label for="password_confirmation">تأكيد كلمة المرور</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button type="submit">تعيين كلمة المرور</button>
    </form>
@endsection 