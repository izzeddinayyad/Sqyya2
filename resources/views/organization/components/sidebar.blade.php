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
    <li class="flex"><img src="{{ asset('images/dashbordIcons/diversity_3.png') }}" /><a href="{{ route('organization.drivers.management') }}">إدارة السائقين</a></li>
    <li class="flex"><img src="{{ asset('images/dashbordIcons/equalizer.png') }}" /><a href="{{ route('organization.statistics') }}">إحصائيات</a></li>
    <li class="flex"><img src="{{ asset('images/dashbordIcons/location_on.png') }}" /><a href="#">مراكز الايواء</a></li>
    <li class="flex"><img src="{{ asset('images/dashbordIcons/diversity_3.png') }}" /><a href="{{ route('organization.delegates') }}">المندوبون</a></li>
    <li class="flex"><img src="{{ asset('images/dashbordIcons/draft.png') }}" /><a href="{{ route('organization.orders') }}">التقارير</a></li>
    <li class="flex"><img src="{{ asset('images/dashbordIcons/assignment.png') }}" /><a href="{{ route('organization.water-requests') }}">طلبات المياه</a></li>
    <li class="flex"><img src="{{ asset('images/dashbordIcons/settings.png') }}" /><a href="{{ route('organization.settings') }}">الإعدادات</a></li>
    <li class="flex mt-70"><img src="{{ asset('images/dashbordIcons/exit_to_app.png') }}" /><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل الخروج</a></li>
  </ul>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>
</div> 