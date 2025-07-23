@extends('layouts.app')

@section('title', 'إدارة المندوبين')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .delegates-sections {
            margin-bottom: 20px;
        }
        
        .delegates-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .section-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-header h3 {
            margin: 0;
            color: #333;
            font-size: 1.2em;
        }
        
        .section-count {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        .section-info {
            padding: 10px 20px;
            background: #e9ecef;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9em;
            color: #495057;
        }
        
        .delegates-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .delegates-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: right;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9em;
        }
        
        .delegates-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            text-align: right;
            font-size: 0.9em;
        }
        
        .delegates-table tr:hover {
            background: #f8f9fa;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        .status.active {
            background: #d4edda;
            color: #155724;
        }
        
        .status.inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status.assigned {
            background: #d4edda;
            color: #155724;
        }
        
        .status.available {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .action-btn {
            border: none;
            background: none;
            padding: 6px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }
        
        .assign-btn {
            color: #28a745;
        }
        
        .assign-btn:hover {
            background: #e8f5e8;
        }
        
        .unassign-btn {
            color: #dc3545;
        }
        
        .unassign-btn:hover {
            background: #ffebee;
        }
        
        .view-btn {
            color: #007bff;
        }
        
        .view-btn:hover {
            background: #e3f2fd;
        }
        
        .edit-btn {
            color: #28a745;
        }
        
        .edit-btn:hover {
            background: #e8f5e8;
        }
        
        .disable-btn {
            color: #ffc107;
        }
        
        .disable-btn:hover {
            background: #fff8e1;
        }
        
        .delete-btn {
            color: #dc3545;
        }
        
        .delete-btn:hover {
            background: #ffebee;
        }
        
        .stats-cell {
            text-align: center;
            font-weight: 600;
        }
        
        .last-activity {
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .no-delegates {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-delegates i {
            font-size: 3em;
            margin-bottom: 15px;
            opacity: 0.5;
        }
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
                    <div class="user-profile">
                        <img src="{{ asset('images/dashbordIcons/Group 48095965.png') }}" class="profile-image" />
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'اسم المستخدم' }}</span>
                            <span class="user-role">صاحب المؤسسة</span>
                        </div>
                    </div>
                    <div class="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1><i class="fas fa-users"></i> إدارة المندوبين</h1>
            </div>
            
            <div class="delegates-sections">
                <!-- Current Institution Delegates -->
                <div class="delegates-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user-check"></i> مندوبو المؤسسة</h3>
                        <span class="section-count">{{ $institutionDelegates->count() }}</span>
                    </div>
                    <div class="section-info">
                        المندوبون المرتبطون بمؤسستك حالياً
                    </div>
                    
                    @if($institutionDelegates->count() > 0)
                    <div class="stations-table-container">
                        <table class="stations-table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الجوال</th>
                                    <th>المدينة</th>
                                    <th>عدد المحطات</th>
                                    <th>عدد الطلبات</th>
                                    <th>آخر نشاط</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($institutionDelegates as $delegate)
                                <tr>
                                    <td>{{ $delegate->name }}</td>
                                    <td>{{ $delegate->phone ?? '-' }}</td>
                                    <td>{{ $delegate->city ?? '-' }}</td>
                                    <td class="stats-cell">{{ $delegate->stations_count ?? 0 }}</td>
                                    <td class="stats-cell">{{ $delegate->orders_count ?? 0 }}</td>
                                    <td class="last-activity">{{ $delegate->last_activity ?? 'غير محدد' }}</td>
                                    <td>
                                        <span class="status {{ $delegate->status ?? 'active' }}">
                                            @if(($delegate->status ?? 'active') === 'active')
                                                نشط ✅
                                            @else
                                                غير نشط ❌
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="view-btn action-btn" title="عرض التفاصيل" onclick="viewDelegate({{ $delegate->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="edit-btn action-btn" title="تعديل" onclick="editDelegate({{ $delegate->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if(($delegate->status ?? 'active') === 'active')
                                                <button class="disable-btn action-btn" title="تعطيل" onclick="toggleDelegateStatus({{ $delegate->id }}, 'inactive')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @else
                                                <button class="disable-btn action-btn" title="تفعيل" onclick="toggleDelegateStatus({{ $delegate->id }}, 'active')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <form method="POST" action="{{ route('organization.delegates.unassign', $delegate->id) }}" class="unassign-form" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="unassign-btn action-btn" title="فك التعيين من المؤسسة" onclick="return confirm('هل أنت متأكد من فك تعيين هذا المندوب من المؤسسة؟');">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-delegates">
                        <i class="fas fa-users"></i>
                        <h3>لا يوجد مندوبون مرتبطون</h3>
                        <p>يمكنك تعيين مندوبين من القائمة المتاحة</p>
                    </div>
                    @endif
                </div>
                
                <!-- Available Delegates -->
                <div class="delegates-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user-plus"></i> المندوبون المتاحون</h3>
                        <span class="section-count">{{ $availableDelegates->count() }}</span>
                    </div>
                    <div class="section-info">
                        مندوبون متاحون للتعيين من جميع المؤسسات
                    </div>
                    
                    @if($availableDelegates->count() > 0)
                    <div class="stations-table-container">
                        <table class="stations-table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الجوال</th>
                                    <th>المدينة</th>
                                    <th>عدد المحطات</th>
                                    <th>عدد الطلبات</th>
                                    <th>آخر نشاط</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableDelegates as $delegate)
                                <tr>
                                    <td>{{ $delegate->name }}</td>
                                    <td>{{ $delegate->phone ?? '-' }}</td>
                                    <td>{{ $delegate->city ?? '-' }}</td>
                                    <td class="stats-cell">{{ $delegate->stations_count ?? 0 }}</td>
                                    <td class="stats-cell">{{ $delegate->orders_count ?? 0 }}</td>
                                    <td class="last-activity">{{ $delegate->last_activity ?? 'غير محدد' }}</td>
                                    <td>{{ $delegate->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="actions">
                                            <button class="view-btn action-btn" title="عرض التفاصيل" onclick="viewDelegate({{ $delegate->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <form method="POST" action="{{ route('organization.delegates.assign', $delegate->id) }}" class="assign-form" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="assign-btn action-btn" title="تعيين للمؤسسة" onclick="return confirm('هل أنت متأكد من تعيين هذا المندوب لمؤسستك؟');">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-delegates">
                        <i class="fas fa-user-slash"></i>
                        <h3>لا يوجد مندوبون متاحون</h3>
                        <p>جميع المندوبين مرتبطون بمؤسسات</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // View delegate details
    window.viewDelegate = function(delegateId) {
        // Implement view functionality
        alert('عرض تفاصيل المندوب رقم: ' + delegateId);
    };
    
    // Edit delegate
    window.editDelegate = function(delegateId) {
        // Implement edit functionality
        alert('تعديل المندوب رقم: ' + delegateId);
    };
    
    // Toggle delegate status
    window.toggleDelegateStatus = function(delegateId, status) {
        if (confirm('هل أنت متأكد من تغيير حالة المندوب؟')) {
            // Implement status toggle functionality
            alert('تغيير حالة المندوب رقم: ' + delegateId + ' إلى: ' + status);
        }
    };
});
</script>
@endsection 