@extends('layouts.auth')

@section('title', 'إعادة تعيين كلمة المرور')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
    <h2>إعادة تعيين كلمة المرور عبر البريد الإلكتروني</h2>
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
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label for="email">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </div>
        <button type="submit">إرسال رابط إعادة التعيين</button>
    </form>
@endsection 