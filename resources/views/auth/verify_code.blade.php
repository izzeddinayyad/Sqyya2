@extends('layouts.auth')

@section('title', 'التحقق من رمز الجوال')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
    <h2>التحقق من رمز الجوال</h2>
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
    <form method="POST" action="{{ url('password/sms/verify') }}">
        @csrf
        <div>
            <label for="phone">رقم الجوال</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
        </div>
        <div>
            <label for="code">رمز التحقق</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required>
        </div>
        <button type="submit">تحقق</button>
    </form>
@endsection 