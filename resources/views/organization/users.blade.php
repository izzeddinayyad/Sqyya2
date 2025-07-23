@extends('layouts.app')

@section('title', 'إدارة السائقين')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .action-btn { border: none; background: none; padding: 7px; border-radius: 50%; transition: background 0.2s; font-size: 1.15em; margin: 0 2px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
        .edit-btn { color: #2563eb; background: #f0f7ff; }
        .edit-btn:hover { background: #dbeafe; }
        .assign-btn { color: #0d9488; background: #e0fdfa; }
        .assign-btn:hover { background: #b9f3ec; }
        .unassign-btn { color: #d32f2f; background: #fff0f0; }
        .unassign-btn:hover { background: #ffebee; }
        .actions { display: flex; gap: 2px; }
        .truck-select {
            padding: 6px 16px;
            border-radius: 7px;
            border: 1.5px solid #bdbdbd;
            font-size: 1.08em;
            margin-left: 8px;
            min-width: 110px;
            transition: border-color 0.2s;
            vertical-align: middle;
        }
        .truck-select:focus {
            border-color: #2563eb;
            outline: none;
            background: #f0f7ff;
        }
    </style>
@endsection

@section('content')
<div class="container-full flex">
    <div class="overlay"></div>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="header-side">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="logo" />
            </div>
            <h2>لوحة تحكم صاحب المؤسسة</h2>
        </div>
        <ul class="nav-menu flex flex-column">
            <li class="flex"><img src="{{ asset('images/dashbordIcons/cottage.png') }}" /><a href="{{ route('organization.dashboard') }}">الرئيسية</a></li>
            <li class="flex"><img src="{{ asset('images/dashbordIcons/emergency_heat_2.png') }}" /><a href="{{ route('organization.stations') }}">محطات التحلية</a></li>
            <li class="flex"><img src="{{ asset('images/dashbordIcons/local_shipping.png') }}" /><a href="{{ route('organization.trucks') }}">إدارة الشاحنات</a></li>
            <li class="flex active"><img src="{{ asset('images/dashbordIcons/diversity_3.png') }}" /><a href="{{ route('organization.users') }}">إدارة السائقين</a></li>
            <li class="flex"><img src="{{ asset('images/dashbordIcons/equalizer.png') }}" /><a href="{{ route('organization.statistics') }}">إحصائيات</a></li>
            <li class="flex"><img src="{{ asset('images/dashbordIcons/location_on.png') }}" /><a href="#shelters">مراكز الايواء</a></li>
            <li class="flex"><img src="{{ asset('images/dashbordIcons/draft.png') }}" /><a href="#reports">التقارير</a></li>
            <li class="flex"><img src="{{ asset('images/dashbordIcons/settings.png') }}" /><a href="#settings">الإعدادات</a></li>
            <li class="flex mt-70"><img src="{{ asset('images/dashbordIcons/exit_to_app.png') }}" /><a href="#">تسجيل الخروج</a></li>
        </ul>
    </div>
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
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1>إدارة السائقين</h1>
            </div>
            <div class="stations-table-container">
                <table class="stations-table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>المدينة</th>
                            <th>الشاحنة المعينة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $driver)
                        <tr>
                            <td>{{ $driver->name }}</td>
                            <td>{{ $driver->phone }}</td>
                            <td>{{ $driver->city }}</td>
                            <td>{{ $driver->truck ? $driver->truck->truck_number : 'لا يوجد' }}</td>
                            <td>
                                @if($driver->truck)
                                    <span class="status active">نشط</span>
                                @else
                                    <span class="status inactive">متاح</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="edit-btn action-btn" title="تعديل"><i class="fas fa-edit"></i></button>
                                    @if($driver->truck)
                                        <form method="POST" action="{{ route('organization.users.unassignTruck', $driver->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="unassign-btn action-btn" title="إلغاء التعيين"><i class="fas fa-unlink"></i></button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('organization.users.assignTruck', $driver->id) }}" style="display:inline;">
                                            @csrf
                                            <select name="truck_id" class="truck-select">
                                                <option value="">اختر شاحنة</option>
                                                @foreach($trucks->where('driver_id', null) as $truck)
                                                    <option value="{{ $truck->id }}">{{ $truck->truck_number }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="assign-btn action-btn" title="تعيين لشاحنة"><i class="fas fa-truck"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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