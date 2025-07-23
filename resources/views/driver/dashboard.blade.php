@extends('layouts.app')

@section('title', 'لوحة تحكم السائق')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/delegateDashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/truck_ownerDashboard.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/org_owner_dashbord.css') }}" />
@endsection

@section('content')
<div class="container-full flex">
  <!-- side bar section -->
  @include('driver.components.sidebar')
  <!-- the main content section -->
  <div class="main-content">
    <div class="dashboard-header">
      <div class="header-content">
        <div class="search-container">
          <input type="text" placeholder="ابحث هنا..." />
          <i class="fas fa-search search-icon"></i>
        </div>
        <div class="header-right">
          <div class="notification-badge" id="notificationBell" style="position: relative; cursor: pointer;">
            <i class="fas fa-bell notification-icon"></i>
            <span class="notification-count">{{ isset($tasks_assigned) && $tasks_assigned->count() > 0 ? $tasks_assigned->count() : '' }}</span>
            <div class="dropdown-menu" id="notificationDropdownMenu" style="display: none; position: absolute; left: 0; top: 120%; background: #fff; min-width: 220px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000; text-align: right;">
                @if(isset($tasks_assigned) && $tasks_assigned->count() > 0)
                    @foreach($tasks_assigned as $task)
                        <a href="{{ route('driver.delivery_tasks') }}#task-{{ $task->id }}" style="display: block; padding: 10px 16px; color: #333; text-decoration: none; border-bottom: 1px solid #eee;">
                            🚚 طلب رقم #{{ $task->id }}<br>
                            <small>الكمية: {{ number_format($task->quantity) }} لتر</small>
                        </a>
                    @endforeach
                @else
                    <div style="padding: 12px 16px; color: #888;">لا توجد مهام جديدة</div>
                @endif
            </div>
          </div>
          <div class="user-profile" id="userProfileDropdown" style="position: relative; cursor: pointer;">
            <img src="{{ asset('images/dashbordIcons/Group 48095965.png') }}" class="profile-image" />
            <div class="user-info">
              <span class="user-name">{{ Auth::user()->name ?? 'اسم المستخدم' }}</span>
              <span class="user-role">سائق الشاحنة المياه</span>
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
    <!-- dashboard content -->
    <div class="dashboard-tables flex flex-column container space-between mt-70">
      <h2>لوحة التحكم - السائق</h2>
      <!-- driver information -->
      <div class="shelter">
        <table>
          <caption>
            <h3>معلومات الشاحنة</h3>
          </caption>
          <tr>
            <th>رقم الشاحنة الشاحنة</th>
            <th>المحطة التابعة</th>
            <th>سعة الخزان</th>
            <th>المحافظة</th>
            <th>آخر صيانة</th>
            <th>المسافة المقطوعة</th>
            <th>حالة المركز</th>
          </tr>
          <tbody>
            <tr>
              <td>#TRK-7890</td>
              <td>محطة التحلية الشمالية</td>
              <td>10:30 صباحًا</td>
              <td>8,000 لتر</td>
              <td>15/06/2023</td>
              <td>1,250 كم</td>
              <td class="active"><span>نشطة</span></td>
            </tr>
            <tr>
              <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
            </tr>
            <tr class="border-bt-0">
              <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--truck info -->
      <div class="flex  truck-info">
        <div class="water-info flex flex-column">
          <div class="flex title-info">
            <a href="#"><i class="fa-solid fa-charging-station"></i></a>
            <h2>جدول التعبئة</h2>
          </div>
          <div class="content-info flex flex-wrap">
                <div class="flex">
                    <div class="flex space-between">
                        <a  href="#"><i class="fa-solid fa-water"></i></a>
                        <div class="flex space-between">
                          <div class="flex flex-wrap">
                            <span>تعبئة الخزان</span>
                            <span>محطة التحلية الشمالية</span>
                        </div>
                        <div class="distance-ability flex space-between">
                            <span>1,250 كم</span>
                            <span class="ready">جاهز للعمل</span>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex space-between">
                        <a  href="#"><i class="fa-solid fa-water"></i></a>
                        <div class="flex space-between">
                          <div class="flex flex-wrap">
                            <span>تعبئة الخزان</span>
                            <span>محطة التحلية الشمالية</span>
                        </div>
                        <div class="distance-ability flex space-between">
                            <span>1,250 كم</span>
                            <span class="ready">جاهز للعمل</span>
                        </div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    // القائمة المنسدلة للملف الشخصي
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
    // القائمة المنسدلة للإشعارات
    var notificationBell = document.getElementById('notificationBell');
    var notificationDropdownMenu = document.getElementById('notificationDropdownMenu');
    if(notificationBell && notificationDropdownMenu) {
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdownMenu.style.display = notificationDropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function() {
            notificationDropdownMenu.style.display = 'none';
        });
    }
});
</script>
@endsection 