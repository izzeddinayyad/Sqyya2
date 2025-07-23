@extends('layouts.app')

@section('title', 'لوحة تحكم المؤسسة')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/org_owner_dashbord.css') }}" />
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
      <!-- Dashboard Section -->
      <div id="dashboard">
        <h2 class="dashboard-title">لوحة التحكم الرئيسية</h2>
        <div class="cards">
          <div class="card"><img src="{{ asset('images/dashbordIcons/Group 48095931.png') }}" /><span>أجمالي المياه الموزعة</span><span>24,500 لتر</span><div><i class="fa fa-arrow-down"></i><span>12% عن الأسبوع الماضي</span></div></div>
          <div class="card"><img src="{{ asset('images/dashbordIcons/local_shipping (1).png') }}" /><span>عدد الشاحنات اليوم</span><span>18</span><div><i class="fa fa-arrow-up"></i><span>3 شاحنات جديدة</span></div></div>
          <div class="card"><img src="{{ asset('images/dashbordIcons/location_on (1).png') }}" /><span>مراكز الايواء المغطاه</span><span>10/7</span><div><i class="fa fa-arrow-down"></i><span>3 مراكز ايواء متبقية</span></div></div>
          <div class="card"><img src="{{ asset('images/dashbordIcons/Group 48095944.png') }}" /><span>عدد المستفيدين</span><span>1,250</span><div><i class="fa fa-arrow-up"></i><span>50 مستفيد جديد</span></div></div>
        </div>
      </div>
      <!-- Statistics Section -->
      <div id="statistics" class="card stats-table">
        <h3>إحصائيات الاستهلاك</h3>
        <table>
          <thead><tr><th>المنطقة</th><th>الكمية</th><th>النسبة</th></tr></thead>
          <tbody>
            <tr><td>المنطقة الشمالية</td><td>1,200</td><td>25%</td></tr>
            <tr><td>المنطقة الجنوبية</td><td>1,200</td><td>20%</td></tr>
            <tr><td>المنطقة الشرقية</td><td>1,500</td><td>31%</td></tr>
            <tr><td>المنطقة الغربية</td><td>1,150</td><td>24%</td></tr>
          </tbody>
        </table>
        <div class="legend">
          <p><span class="color-box red"></span> استهلاك منخفض (أقل من 800 م³)</p>
          <p><span class="color-box orange"></span> استهلاك متوسط (800 - 1100 م³)</p>
          <p><span class="color-box green"></span> استهلاك مرتفع (أكثر من 1100 م³)</p>
        </div>
      </div>
      <!-- Map Section -->
      <div id="shelters" class="card">
        <h3>توزيع المياه حسب المنطقة - الأسبوع الحالي</h3>
        <div id="map" style="width: 100%; height: 300px; border-radius: 12px;"></div>
      </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

      // Leaflet map
      const map = L.map('map').setView([31.5, 34.466], 12);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
      }).addTo(map);

      const zones = [
        { name: 'المنطقة الشمالية', coords: [31.56, 34.48], color: 'red' },
        { name: 'المنطقة الجنوبية', coords: [31.45, 34.42], color: 'orange' },
        { name: 'المنطقة الشرقية', coords: [31.51, 34.5], color: 'red' },
        { name: 'المنطقة الغربية', coords: [31.49, 34.44], color: 'orange' }
      ];

      zones.forEach(zone => {
        L.polygon(
          [
            [zone.coords[0], zone.coords[1]],
            [zone.coords[0] + 0.01, zone.coords[1] - 0.01],
            [zone.coords[0] - 0.01, zone.coords[1] - 0.01],
          ],
          { color: zone.color, fillOpacity: 0.6 }
        ).addTo(map).bindPopup(zone.name);
      });
    });
    </script>
@endsection 