<div class="sidebar">
    <div class="header-side">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="logo" />
        </div>
        <h2>لوحة تحكم السائق</h2>
    </div>
    <ul class="nav-menu flex flex-column">
        <li class="{{ request()->routeIs('driver.dashboard') ? 'active' : '' }} flex">
            <img src="{{ asset('images/dashbordIcons/cottage.png') }}" alt="cottage" />
            <a href="{{ route('driver.dashboard') }}">الرئيسية</a>
        </li>
        <li class="{{ request()->routeIs('driver.delivery_tasks') ? 'active' : '' }} flex">
            <img src="{{ asset('images/dashbordIcons/local_shipping.png') }}" alt="local_shipping" />
            <a href="{{ route('driver.delivery_tasks') }}">مهام التوصيل</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/assignment.png') }}" alt="assignment" />
            <a href="#">سجل التعبئة</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/draft.png') }}" alt="draft" />
            <a href="#">الجدول الزمني</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/diversity_3.png') }}" alt="diversity_3" />
            <a href="#">ملاحظة</a>
        </li>
        <li class="flex">
            <img src="{{ asset('images/dashbordIcons/settings.png') }}" alt="settings" />
            <a href="#">الاعدادات</a>
        </li>
        <li class="flex mt-70">
            <img src="{{ asset('images/dashbordIcons/exit_to_app.png') }}" alt="exit_to_app" />
            <a href="#">تسجيل الخروج</a>
        </li>
    </ul>
</div> 