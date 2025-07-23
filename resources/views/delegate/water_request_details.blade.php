@extends('layouts.app')

@section('title', 'تفاصيل طلب المياه')

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
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-completed {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-cancelled {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .status-emergency {
            background-color: #f8d7da;
            color: #721c24;
        }
        .detail-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #212529;
        }
        .detail-value.highlight {
            font-weight: 600;
            color: #007bff;
            font-size: 16px;
        }
        .status-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        .status-actions h4 {
            margin-bottom: 15px;
            color: #495057;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
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
        
        <!-- Water Request Details -->
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1>تفاصيل طلب المياه #{{ $waterRequest->id }}</h1>
                <a href="{{ route('delegate.water-requests') }}" class="add-station-btn">
                    <i class="fas fa-arrow-right"></i>
                    العودة للطلبات
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="detail-info">
                <div class="detail-row">
                    <span class="detail-label">رقم الطلب:</span>
                    <span class="detail-value">#{{ $waterRequest->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">الكمية المطلوبة:</span>
                    <span class="detail-value highlight">{{ number_format($waterRequest->quantity) }} لتر</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">الحالة:</span>
                    <span class="detail-value">
                        @switch($waterRequest->status)
                            @case('pending')
                                <span class="status-badge status-pending">في الانتظار</span>
                                @break
                            @case('approved')
                                <span class="status-badge status-approved">مقبول</span>
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
                                <span class="status-badge">{{ $waterRequest->status }}</span>
                        @endswitch
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">تاريخ الإنشاء:</span>
                    <span class="detail-value">{{ $waterRequest->created_at->format('Y-m-d H:i') }}</span>
                </div>
                
                @if($waterRequest->scheduled_at)
                <div class="detail-row">
                    <span class="detail-label">التاريخ والوقت المفضل:</span>
                    <span class="detail-value">{{ $waterRequest->scheduled_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
                
                @if($waterRequest->emergency)
                <div class="detail-row">
                    <span class="detail-label">نوع الطلب:</span>
                    <span class="detail-value">
                        <span class="status-badge status-emergency">طلب عاجل</span>
                    </span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">العنوان:</span>
                    <span class="detail-value">{{ $waterRequest->location }}</span>
                </div>
                
                @if($waterRequest->latitude && $waterRequest->longitude)
                <div class="detail-row">
                    <span class="detail-label">الإحداثيات:</span>
                    <span class="detail-value">
                        {{ $waterRequest->latitude }}, {{ $waterRequest->longitude }}
                        <a href="https://maps.google.com/?q={{ $waterRequest->latitude }},{{ $waterRequest->longitude }}" 
                           target="_blank" 
                           class="edit-btn" 
                           style="margin-right: 10px;">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </span>
                </div>
                @endif
                
                @if($waterRequest->notes)
                <div class="detail-row">
                    <span class="detail-label">الملاحظات:</span>
                    <span class="detail-value">{{ $waterRequest->notes }}</span>
                </div>
                @endif
            </div>

            <!-- Status Update Actions -->
            @if($waterRequest->status === 'pending')
            <div class="status-actions">
                <h4>إدارة الطلب</h4>
                <div class="action-buttons">
                    <form method="POST" action="{{ route('delegate.water-requests.destroy', $waterRequest->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف الطلب؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" title="حذف الطلب">
                            <i class="fas fa-trash"></i>
                            حذف الطلب
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($waterRequest->status === 'approved')
            <div class="status-actions">
                <h4>تحديث حالة الطلب</h4>
                <div class="action-buttons">
                    <button type="button" 
                            class="edit-btn" 
                            onclick="updateStatus('completed')"
                            title="إكمال الطلب">
                        <i class="fas fa-check-double"></i>
                        إكمال الطلب
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Form -->
<form id="statusForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" id="statusInput" name="status">
</form>

<script>
function updateStatus(status) {
    const statusText = {
        'approved': 'قبول',
        'rejected': 'رفض',
        'completed': 'إكمال',
        'cancelled': 'إلغاء'
    };
    
    if (confirm(`هل أنت متأكد من ${statusText[status]} الطلب؟`)) {
        const form = document.getElementById('statusForm');
        const statusInput = document.getElementById('statusInput');
        
        form.action = `/delegate/water-requests/{{ $waterRequest->id }}/status`;
        statusInput.value = status;
        
        form.submit();
    }
}

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
});
</script>
@endsection 