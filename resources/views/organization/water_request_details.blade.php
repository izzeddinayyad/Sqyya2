@extends('layouts.app')

@section('title', 'تفاصيل طلب المياه')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-completed { background-color: #cce5ff; color: #004085; }
        .status-cancelled { background-color: #e2e3e5; color: #383d41; }
        .status-emergency { background-color: #f8d7da; color: #721c24; }
        .detail-info { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #495057; }
        .detail-value { color: #212529; }
        .detail-value.highlight { font-weight: 600; color: #007bff; font-size: 16px; }
    </style>
@endsection

@section('content')
<div class="container-full flex">
    <div class="overlay"></div>
    <!-- Sidebar -->
    @include('organization.components.sidebar')
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
                    <div class="user-profile" id="userProfileDropdown" style="position: relative; cursor: pointer;">
                        <img src="{{ asset('images/dashbordIcons/Group 48095965.png') }}" class="profile-image" />
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'اسم المستخدم' }}</span>
                            <span class="user-role">صاحب المؤسسة</span>
                        </div>
                        <div class="dropdown-menu" id="profileDropdownMenu" style="display: none; position: absolute; left: 0; top: 100%; background: #fff; min-width: 140px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000;">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="display: block; padding: 10px 16px; color: #333; text-decoration: none; border-radius: 8px;">تسجيل الخروج</a>
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <div class="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Water Request Details -->
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1>تفاصيل طلب المياه #{{ $request->id }}</h1>
                <a href="{{ route('organization.water-requests') }}" class="add-station-btn">
                    <i class="fas fa-arrow-right"></i>
                    العودة للطلبات
                </a>
            </div>
            <div class="detail-info">
                <div class="detail-row">
                    <span class="detail-label">رقم الطلب:</span>
                    <span class="detail-value">#{{ $request->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">المندوب:</span>
                    <span class="detail-value">{{ $request->representative->name ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الكمية المطلوبة:</span>
                    <span class="detail-value highlight">{{ number_format($request->quantity) }} لتر</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الحالة:</span>
                    <span class="detail-value">
                        @switch($request->status)
                            @case('pending')
                                <span class="status-badge status-pending">في الانتظار</span>
                                @break
                            @case('approved')
                                <span class="status-badge status-approved">مقبول</span>
                                @break
                            @case('assigned')
                                <span class="status-badge status-approved">قادم</span>
                                @break
                            @case('in_progress')
                                <span class="status-badge status-pending">جاري التنفيذ</span>
                                @break
                            @case('rejected')
                                <span class="status-badge status-rejected">مرفوض</span>
                                @break
                            @case('completed')
                                <span class="status-badge status-completed">مكتمل</span>
                                @break
                            @case('cancelled')
                                <span class="status-badge status-cancelled">ملغي</span>
                                @break
                            @default
                                <span class="status-badge">{{ $request->status }}</span>
                        @endswitch
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">تاريخ الإنشاء:</span>
                    <span class="detail-value">{{ $request->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if($request->scheduled_at)
                <div class="detail-row">
                    <span class="detail-label">التاريخ والوقت المفضل:</span>
                    <span class="detail-value">{{ $request->scheduled_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
                @if($request->emergency)
                <div class="detail-row">
                    <span class="detail-label">نوع الطلب:</span>
                    <span class="detail-value">
                        <span class="status-badge status-emergency">طلب عاجل</span>
                    </span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">العنوان:</span>
                    <span class="detail-value">{{ $request->location }}</span>
                </div>
                @if($request->latitude && $request->longitude)
                <div class="detail-row">
                    <span class="detail-label">الإحداثيات:</span>
                    <span class="detail-value">
                        {{ $request->latitude }}, {{ $request->longitude }}
                        <a href="https://maps.google.com/?q={{ $request->latitude }},{{ $request->longitude }}" 
                           target="_blank" 
                           class="edit-btn" 
                           style="margin-right: 10px;">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </span>
                </div>
                @endif
                @if($request->notes)
                <div class="detail-row">
                    <span class="detail-label">الملاحظات:</span>
                    <span class="detail-value">{{ $request->notes }}</span>
                </div>
                @endif
                @if($request->driver)
                <div class="detail-row">
                    <span class="detail-label">السائق:</span>
                    <span class="detail-value">{{ $request->driver->name }}</span>
                </div>
                @endif
                @if($request->truck)
                <div class="detail-row">
                    <span class="detail-label">الشاحنة:</span>
                    <span class="detail-value">{{ $request->truck->plate_number ?? $request->truck->id }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    var userProfile = document.getElementById('userProfileDropdown');
    var profileDropdownMenu = document.getElementById('profileDropdownMenu');
    if(userProfile && profileDropdownMenu) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdownMenu.style.display = profileDropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function() {
            profileDropdownMenu.style.display = 'none';
        });
    }
});
</script>
@endsection 