@extends('layouts.app')

@section('title', 'إحصائيات الأداء')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/org_owner_dashbord.css') }}" /> {{-- استخدام نفس ستايل لوحة التحكم الرئيسية --}}
    <style>
        .overview-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .overview-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            text-align: center;
        }
        .overview-card h3 { margin-top: 0; color: #555; font-size: 1.1em; }
        .overview-card p { font-size: 2.2em; font-weight: bold; color: #333; margin-bottom: 5px; }
        .overview-card span { font-size: 0.9em; color: #888; }

        .chart-section, .map-section, .data-table-section {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .chart-section h2, .map-section h2, .data-table-section h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            font-size: 1.3em;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        #dailyDistributionChart, #stationsMap {
            height: 400px; /* ارتفاع ثابت للخرائط والرسوم البيانية */
            width: 100%;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #eee;
            padding: 12px 15px;
            text-align: right;
        }
        .data-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #555;
        }
        .data-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .data-table tbody tr:hover { background-color: #f1f1f1; }
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
        <li class="flex"><img src="{{ asset('images/dashbordIcons/diversity_3.png') }}" /><a href="{{ route('organization.users') }}">إدارة السائقين</a></li>
        <li class="flex active"><img src="{{ asset('images/dashbordIcons/equalizer.png') }}" /><a href="{{ route('organization.statistics') }}">إحصائيات</a></li>
        <li class="flex"><img src="{{ asset('images/dashbordIcons/location_on.png') }}" /><a href="#shelters">مراكز الايواء</a></li>
        <li class="flex"><img src="{{ asset('images/dashbordIcons/draft.png') }}" /><a href="#reports">التقارير</a></li>
        <li class="flex"><img src="{{ asset('images/dashbordIcons/settings.png') }}" /><a href="#settings">الإعدادات</a></li>
        <li class="flex mt-70"><img src="{{ asset('images/dashbordIcons/exit_to_app.png') }}" /><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل الخروج</a></li>
      </ul>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
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
      <!-- Statistics Content -->
      <div class="statistics-dashboard">
        <h2 class="dashboard-title">إحصائيات الأداء</h2>

        <!-- 1. Overview Cards -->
        <div class="overview-cards">
          <div class="overview-card">
            <h3>عدد المحطات</h3>
            <p>12</p>
            <span>محطة</span>
          </div>
          <div class="overview-card">
            <h3>عدد الشاحنات</h3>
            <p>8</p>
            <span>شاحنات</span>
          </div>
          <div class="overview-card">
            <h3>عدد السائقين</h3>
            <p>7</p>
            <span>سائقين</span>
          </div>
          <div class="overview-card">
            <h3>عمليات التوزيع اليوم</h3>
            <p>32</p>
            <span>عملية</span>
          </div>
          <div class="overview-card">
            <h3>متوسط نسبة تشغيل الشاحنات</h3>
            <p>78%</p>
            <span>النسبة المئوية</span>
          </div>
          <div class="overview-card">
            <h3>المحطات النشطة</h3>
            <p>10</p>
            <span>من 12</span>
          </div>
        </div>

        <!-- 2. Daily/Weekly Distribution Chart -->
        <div class="chart-section">
          <h2>التوزيع اليومي/الأسبوعي</h2>
          <canvas id="dailyDistributionChart"></canvas>
        </div>

        <!-- 3. Map showing Station Locations -->
        <div class="map-section">
          <h2>أماكن المحطات</h2>
          <div id="stationsMap"></div>
        </div>

        <!-- 4. Truck Statistics -->
        <div class="data-table-section">
          <h2>إحصائيات تشغيل الشاحنات</h2>
          <table class="data-table">
            <thead>
              <tr>
                <th>الشاحنة</th>
                <th>عدد الرحلات</th>
                <th>المسافة المقطوعة</th>
                <th>السائق</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>TRK-001</td>
                <td>12</td>
                <td>120 كم</td>
                <td>محمد أحمد</td>
              </tr>
              <tr>
                <td>TRK-002</td>
                <td>6</td>
                <td>45 كم</td>
                <td>علي محمود</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- 5. Notifications / Top Drivers -->
        <div class="data-table-section">
          <h2>تنبيهات وأفضل السائقين</h2>
          <h3>تنبيهات سريعة</h3>
          <ul>
            <li>شاحنة TRK-003 بحاجة لصيانة عاجلة.</li>
            <li>محطة الشمالية بها انخفاض في التوزيع بنسبة 15%.</li>
            <li>السائق خالد لم يقم بأي رحلة منذ 3 أيام.</li>
          </ul>

          <h3>أعلى السائقين أداءً</h3>
          <table class="data-table">
            <thead>
              <tr>
                <th>السائق</th>
                <th>عدد الرحلات</th>
                <th>نسبة الالتزام</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>محمد أحمد</td>
                <td>15</td>
                <td>100%</td>
              </tr>
              <tr>
                <td>علي محمود</td>
                <td>10</td>
                <td>90%</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Chart.js for Daily Distribution
        const ctx = document.getElementById('dailyDistributionChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
                    datasets: [{
                        label: 'عدد التوزيعات اليومية',
                        data: [12, 19, 3, 5, 2, 3, 7],
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Leaflet Map for Station Locations
        const mapElement = document.getElementById('stationsMap');
        if (mapElement) {
            const map = L.map('stationsMap').setView([24.7136, 46.6753], 10); // Riyadh coordinates
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Example static station markers
            L.marker([24.7136, 46.6753]).addTo(map).bindPopup('محطة الرياض الرئيسية').openPopup();
            L.marker([24.639, 46.711]).addTo(map).bindPopup('محطة جنوب الرياض');
            L.marker([24.75, 46.61]).addTo(map).bindPopup('محطة غرب الرياض');
        }
    </script>
@endsection 