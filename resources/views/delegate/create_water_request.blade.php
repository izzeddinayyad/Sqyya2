@extends('layouts.app')

@section('title', 'إنشاء طلب مياه جديد')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .error-message {
            color: #d32f2f;
            font-size: 0.85em;
            min-height: 18px;
            margin-top: 2px;
            margin-bottom: 4px;
            text-align: right;
            line-height: 1.2;
        }
        .alert {
            padding: 10px 18px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 1em;
            text-align: right;
        }
        .alert-success {
            background: #e8f5e9;
            color: #388e3c;
            border: 1px solid #c8e6c9;
        }
        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
    </style>
@endsection

@section('content')
<div class="container-full flex">
    <div class="overlay"></div>
    @include('delegate.components.sidebar')
    <!-- Main content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="search-container">
                    <input type="text" placeholder="ابحث هنا..." />
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="header-right">
                    <div class="notification-badge">
                        <i class="fas fa-bell notification-icon"></i>
                        <span class="notification-count">5</span>
                    </div>
                    <div class="user-profile">
                        <img src="{{ asset('images/dashbordIcons/Group 48095965.png') }}" class="profile-image" />
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'اسم المستخدم' }}</span>
                            <span class="user-role">مندوب</span>
                        </div>
                    </div>
                    <div class="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Water Request Form -->
        <div class="add-station-form" style="display: block;">
            <!-- Alerts -->
            @if($errors->any())
                <div class="alert alert-danger">
                    هناك أخطاء في البيانات المدخلة. يرجى تصحيحها بالأسفل.
                    <ul style="margin: 8px 0 0 0; padding-right: 18px;">
                        @foreach($errors->all() as $error)
                            <li style="font-size:0.97em;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="form-header">
                <h1><i class="fas fa-plus-circle"></i> إنشاء طلب مياه جديد</h1>
                <a href="{{ route('delegate.water-requests') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>
            </div>
            
            <form class="station-form" method="POST" action="{{ route('delegate.water-requests.store') }}">
                @csrf
                
                <!-- Request Details Section -->
                <div class="form-section">
                    <h2>تفاصيل الطلب</h2>
                    <div class="capacity-fields">
                        <div class="input-group">
                            <label>الكمية المطلوبة (لتر)</label>
                            <input type="number" 
                                   name="quantity" 
                                   placeholder="5000" 
                                   value="{{ old('quantity') }}"
                                   min="100"
                                   step="100"
                                   required>
                            <div class="error-message">@error('quantity'){{ $message }}@enderror</div>
                        </div>
                        <div class="input-group">
                            <label>التاريخ والوقت المفضل</label>
                            <input type="datetime-local" 
                                   name="scheduled_at" 
                                   value="{{ old('scheduled_at') }}">
                            <div class="error-message">@error('scheduled_at'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
                
                <!-- Location Section -->
                <div class="form-section">
                    <h2>الموقع</h2>
                    <div class="info-fields">
                        <div class="input-group">
                            <label>العنوان التفصيلي</label>
                            <textarea name="location" 
                                      rows="3" 
                                      placeholder="أدخل العنوان التفصيلي للموقع..."
                                      required>{{ old('location') }}</textarea>
                            <div class="error-message">@error('location'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info Section -->
                <div class="form-section">
                    <h2>معلومات إضافية</h2>
                    <div class="info-fields">
                        <div class="input-group">
                            <label>ملاحظات إضافية</label>
                            <textarea name="notes" 
                                      rows="4" 
                                      placeholder="أي معلومات إضافية أو تعليمات خاصة...">{{ old('notes') }}</textarea>
                            <div class="error-message">@error('notes'){{ $message }}@enderror</div>
                        </div>
                        <div class="input-group">
                            <label>
                                <input type="checkbox" 
                                       name="emergency" 
                                       value="1"
                                       {{ old('emergency') ? 'checked' : '' }}
                                       style="margin-left: 10px;">
                                طلب عاجل
                            </label>
                            <div class="error-message">@error('emergency'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('delegate.water-requests') }}" class="cancel-btn">إلغاء</a>
                    <button type="submit" class="submit-btn">إرسال الطلب</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle
    const toggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.overlay');

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    });

    // Set minimum datetime for scheduled_at
    const scheduledAtInput = document.querySelector('input[name="scheduled_at"]');
    if (scheduledAtInput) {
        const now = new Date();
        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        scheduledAtInput.min = localDateTime;
    }
});
</script>
@endsection 