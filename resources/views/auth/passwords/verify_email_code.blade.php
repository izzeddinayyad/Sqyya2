@extends('layouts.auth')

@section('title', 'التحقق من كود البريد الإلكتروني')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
    <h2>التحقق من كود البريد الإلكتروني</h2>
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
    <form method="POST" action="{{ url('password/email/verify') }}">
        @csrf
        <div>
            <label for="email">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </div>
        <div>
            <label for="code">كود التحقق</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required>
        </div>
        <button type="submit">تحقق</button>
    </form>
@endsection 