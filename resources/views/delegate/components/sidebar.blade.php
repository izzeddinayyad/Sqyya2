<div class="sidebar">
    <div class="header-side">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="logo" />
        </div>
        <h2>لوحة تحكم المندوب</h2>
    </div>
    <ul class="nav-menu flex flex-column">
        <li class="{{ request()->routeIs('delegate.dashboard') ? 'active' : '' }} flex">
            <img src="{{ asset('images/dashbordIcons/cottage.png') }}" alt="cottage" />
            <a href="{{ route('delegate.dashboard') }}">الرئيسية</a>
        </li>
        <li class="{{ request()->routeIs('delegate.water-requests*') ? 'active' : '' }} flex">
            <img src="{{ asset('images/dashbordIcons/assignment.png') }}" alt="assignment" />
            <a href="{{ route('delegate.water-requests') }}">طلبات المياه</a>
            <span class="notification-box flex justify-content-center">3</span>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/assignment.png') }}" alt="assignment" />
            <a href="#">سجل التسليمات</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/local_shipping.png') }}" alt="local_shipping" />
            <a href="#">الشاحنات الواردة</a>
            <span class="notification-box flex justify-content-center">3</span>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/draft.png') }}" alt="draft" />
            <a href="#">التقارير</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/diversity_3.png') }}" alt="diversity_3" />
            <a href="#">المستفيدون</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/settings.png') }}" alt="settings" />
            <a href="#">الاعدادات</a>
        </li>
        <li class="flex mt-70">
            <img src="{{ asset('images/dashbordIcons/exit_to_app.png') }}" alt="exit_to_app" />
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل الخروج</a>
        </li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div> 