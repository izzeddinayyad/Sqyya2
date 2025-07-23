@extends('layouts.app')

@section('title', 'طلبات المياه')

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
        
        <!-- Water Requests Dashboard -->
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1>طلبات المياه</h1>
                <a href="{{ route('delegate.water-requests.create') }}" class="add-station-btn">
                    <i class="fas fa-plus"></i>
                    طلب جديد
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="stations-table-container">
                @if($waterRequests->count() > 0)
                    <table class="stations-table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الكمية</th>
                                <th>الموقع</th>
                                <th>التاريخ المفضل</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterRequests as $request)
                                <tr>
                                    <td><strong>#{{ $request->id }}</strong></td>
                                    <td>{{ number_format($request->quantity) }} لتر</td>
                                    <td>{{ Str::limit($request->location, 40) }}</td>
                                    <td>
                                        @if($request->scheduled_at)
                                            {{ $request->scheduled_at->format('Y-m-d H:i') }}
                                        @else
                                            <span style="color: #6c757d;">في أقرب وقت</span>
                                        @endif
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('delegate.water-requests.show', $request) }}" 
                                               class="edit-btn" 
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($request->status === 'pending')
                                                <form method="POST" action="{{ route('delegate.water-requests.destroy', $request->id) }}" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف الطلب؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-btn" title="حذف الطلب">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($request->status === 'approved')
                                                <button type="button" 
                                                        class="edit-btn" 
                                                        onclick="updateStatus({{ $request->id }}, 'completed')"
                                                        title="إكمال الطلب">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div style="display: flex; justify-content: center; margin-top: 30px;">
                        {{ $waterRequests->links() }}
                    </div>
                @else
                    <div style="text-align: center; padding: 60px 20px; color: #6c757d;">
                        <i class="fas fa-tint fa-3x mb-3"></i>
                        <h5>لا توجد طلبات مياه</h5>
                        <p>لم يتم إنشاء أي طلبات مياه بعد</p>
                        <a href="{{ route('delegate.water-requests.create') }}" class="add-station-btn" style="margin-top: 20px; display: inline-block;">
                            <i class="fas fa-plus"></i>
                            إنشاء طلب جديد
                        </a>
                    </div>
                @endif
            </div>
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
function updateStatus(requestId, status) {
    const statusText = {
        'approved': 'قبول',
        'rejected': 'رفض',
        'completed': 'إكمال',
        'cancelled': 'إلغاء'
    };
    
    if (confirm(`هل أنت متأكد من ${statusText[status]} الطلب؟`)) {
        const form = document.getElementById('statusForm');
        const statusInput = document.getElementById('statusInput');
        
        form.action = `/delegate/water-requests/${requestId}/status`;
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