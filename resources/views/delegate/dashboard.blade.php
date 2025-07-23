@extends('layouts.app')

@section('title', 'لوحة تحكم المندوب')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/delegateDashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/org_owner_dashbord.css') }}" />
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
                    <!-- بداية user-profile مع قائمة منسدلة -->
                    <div class="user-profile" id="userProfileDropdown" style="position: relative; cursor: pointer;">
                        <img src="{{ asset('images/dashbordIcons/Group 48095965.png') }}" class="profile-image" />
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'اسم المستخدم' }}</span>
                            <span class="user-role">مندوب</span>
                        </div>
                        <div class="dropdown-menu" id="profileDropdownMenu" style="display: none; position: absolute; left: 0; top: 100%; background: #fff; min-width: 140px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000;">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="display: block; padding: 10px 16px; color: #333; text-decoration: none; border-radius: 8px;">تسجيل الخروج</a>
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <!-- نهاية user-profile مع قائمة منسدلة -->
                    <div class="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Section -->
        <div id="dashboard">
            <h2 class="dashboard-title">لوحة التحكم - المندوب</h2>
            <div class="cards">
                <div class="card">
                    <img src="{{ asset('images/dashbordIcons/tint.png') }}" />
                    <span>طلبات المياه اليوم</span>
                    <span>{{ \App\Models\WaterRequest::where('representative_id', Auth::id())->whereDate('created_at', today())->count() }}</span>
                    <div>
                        <i class="fa fa-arrow-up"></i>
                        <span>طلبات جديدة</span>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('images/dashbordIcons/local_shipping (1).png') }}" />
                    <span>الشاحنات الواردة</span>
                    <span>3</span>
                    <div>
                        <i class="fa fa-arrow-up"></i>
                        <span>شاحنات جديدة</span>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('images/dashbordIcons/location_on (1).png') }}" />
                    <span>المراكز المغطاة</span>
                    <span>5/7</span>
                    <div>
                        <i class="fa fa-arrow-down"></i>
                        <span>مراكز متبقية</span>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('images/dashbordIcons/Group 48095944.png') }}" />
                    <span>المستفيدون</span>
                    <span>250</span>
                    <div>
                        <i class="fa fa-arrow-up"></i>
                        <span>مستفيدين جدد</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Water Requests Table -->
        <div class="card stats-table">
            <h3>طلبات المياه الأخيرة</h3>
            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>الكمية</th>
                        <th>الموقع</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\WaterRequest::where('representative_id', Auth::id())->latest()->take(5)->get() as $request)
                    <tr>
                        <td><strong>#{{ $request->id }}</strong></td>
                        <td>{{ number_format($request->quantity) }} لتر</td>
                        <td>{{ Str::limit($request->location, 30) }}</td>
                        <td>{{ $request->created_at->format('Y-m-d') }}</td>
                        <td>
                            @switch($request->status)
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
                                @default
                                    <span class="status-badge">{{ $request->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('delegate.water-requests.show', $request) }}" class="btn btn-sm btn-primary">تفاصيل</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-footer">
                <a href="{{ route('delegate.water-requests') }}" class="btn btn-primary">عرض جميع الطلبات</a>
                <a href="{{ route('delegate.water-requests.create') }}" class="btn btn-success">طلب جديد</a>
            </div>
        </div>
        
        <!-- Daily Trucks Table -->
        <div class="card stats-table">
            <h3>الشاحنات المتوقعة اليوم</h3>
            <table>
                <thead>
                    <tr>
                        <th>رقم الشحنة</th>
                        <th>السائق</th>
                        <th>الوقت المتوقع</th>
                        <th>الكمية</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#WTR-2021-015</td>
                        <td>محمد خليل كنباوي</td>
                        <td>09:30 صباحًا</td>
                        <td>8,000 لتر</td>
                        <td><span class="status-badge status-active">نشطة</span></td>
                        <td><button class="btn btn-sm btn-primary">تفاصيل</button></td>
                    </tr>
                    <tr>
                        <td>#WTR-2021-016</td>
                        <td>خالد كشميـري</td>
                        <td>11:00 صباحًا</td>
                        <td>6,000 لتر</td>
                        <td><span class="status-badge status-active">نشطة</span></td>
                        <td><button class="btn btn-sm btn-primary">تفاصيل</button></td>
                    </tr>
                    <tr>
                        <td>#WTR-2021-017</td>
                        <td>خضر كرويته</td>
                        <td>02:30 مساءً</td>
                        <td>5,000 لتر</td>
                        <td><span class="status-badge status-pending">في الطريق</span></td>
                        <td><button class="btn btn-sm btn-primary">تفاصيل</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
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

.status-active {
    background-color: #d1ecf1;
    color: #0c5460;
}

.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9em;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 0.8em;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endsection

@section('scripts')
@parent
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

    var userProfile = document.getElementById('userProfileDropdown');
    var dropdownMenu = document.getElementById('profileDropdownMenu');
    if(userProfile && dropdownMenu) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function() {
            dropdownMenu.style.display = 'none';
        });
    }
});
</script>
@endsection 