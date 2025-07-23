@extends('layouts.app')

@section('title', 'إدارة الشاحنات')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .filters-bar { display: flex; gap: 16px; margin-bottom: 18px; align-items: center; }
        .filters-bar select, .filters-bar input { padding: 6px 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 1em; }
        .filters-bar label { font-size: 1em; margin-left: 6px; }
        .action-btn {
            border: none;
            background: none;
            padding: 7px;
            border-radius: 50%;
            transition: background 0.2s;
            font-size: 1.15em;
            margin: 0 2px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .edit-btn { color: #2563eb; background: #f0f7ff; }
        .edit-btn:hover { background: #dbeafe; }
        .delete-btn { color: #d32f2f; background: #fff0f0; }
        .delete-btn:hover { background: #ffebee; }
        .driver-btn { color: #0d9488; background: #e0fdfa; }
        .driver-btn:hover { background: #b9f3ec; }
        .maintenance-btn { color: #f59e42; background: #fff7e6; }
        .maintenance-btn:hover { background: #ffe4b3; }
        .actions { display: flex; gap: 2px; }
        .error-message {
            color: #d32f2f;
            font-size: 0.85em;
            min-height: 18px;
            margin-top: 2px;
            margin-bottom: 4px;
            text-align: right;
            line-height: 1.2;
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
                <h1>إدارة الشاحنات</h1>
                <button id="showTruckFormBtn" class="add-station-btn" type="button">
                    <i class="fas fa-plus"></i>
                    إضافة شاحنة جديدة
                </button>
            </div>
            <div class="stations-table-container">
                <table class="stations-table">
                    <thead>
                        <tr>
                            <th>رقم الشاحنة</th>
                            <th>نوع الشاحنة</th>
                            <th>سعة الخزان</th>
                            <th>الحالة</th>
                            <th>السائق الحالي</th>
                            <th>تاريخ آخر صيانة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trucks as $truck)
                        <tr>
                            <td>{{ $truck->truck_number }}</td>
                            <td>{{ $truck->truck_type }}</td>
                            <td>{{ number_format($truck->tank_capacity) }} لتر</td>
                            <td><span class="status {{ $truck->status }}">
                                @if($truck->status == 'active') نشطة
                                @elseif($truck->status == 'inactive') غير نشطة
                                @else صيانة
                                @endif
                            </span></td>
                            <td>{{ $truck->driver ? $truck->driver->name : '-' }}</td>
                            <td>{{ $truck->maintenance_date ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('organization.trucks.edit', $truck->id) }}" class="edit-btn action-btn" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <button class="driver-btn action-btn" title="تغيير السائق"><i class="fas fa-sync-alt"></i></button>
                                    <button class="maintenance-btn action-btn" title="سجل الصيانة"><i class="fas fa-tools"></i></button>
                                    <form method="POST" action="{{ route('organization.trucks.destroy', $truck->id) }}" class="delete-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn action-btn" title="حذف" onclick="return confirm('هل أنت متأكد من حذف الشاحنة؟');"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Add Truck Form (Hidden by default) -->
            <div id="truckForm" class="add-station-form" style="display: {{ (isset($editTruck) || $errors->any() || count(old())) ? 'block' : 'none' }};">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="form-header">
                    <h1><i class="fas fa-plus-circle"></i> {{ isset($editTruck) ? 'تعديل الشاحنة' : 'إضافة شاحنة جديدة' }}</h1>
                    <button class="back-btn"><i class="fas fa-arrow-right"></i> رجوع</button>
                </div>
                <form class="station-form" method="POST" action="{{ isset($editTruck) ? route('organization.trucks.update', $editTruck->id) : route('organization.trucks.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($editTruck))
                        @method('PUT')
                    @endif
                    <div class="form-section">
                        <h2>بيانات الشاحنة</h2>
                        <div class="info-fields">
                            <div class="input-group">
                                <label>رقم الشاحنة</label>
                                <input type="text" name="truck_number" placeholder="TRK-001" value="{{ old('truck_number', isset($editTruck) ? $editTruck->truck_number : '') }}">
                                <div class="error-message">@error('truck_number'){{ $message }}@enderror</div>
                            </div>
                            <div class="input-group">
                                <label>نوع الشاحنة</label>
                                <input type="text" name="truck_type" placeholder="كبيرة" value="{{ old('truck_type', isset($editTruck) ? $editTruck->truck_type : '') }}">
                                <div class="error-message">@error('truck_type'){{ $message }}@enderror</div>
                            </div>
                            <div class="input-group">
                                <label>سعة الخزان (لتر)</label>
                                <input type="number" name="tank_capacity" placeholder="10000" value="{{ old('tank_capacity', isset($editTruck) ? $editTruck->tank_capacity : '') }}">
                                <div class="error-message">@error('tank_capacity'){{ $message }}@enderror</div>
                            </div>
                            <div class="input-group">
                                <label>الحالة</label>
                                <select name="status">
                                    <option value="active" {{ old('status', isset($editTruck) ? $editTruck->status : '')=='active'?'selected':'' }}>نشطة</option>
                                    <option value="inactive" {{ old('status', isset($editTruck) ? $editTruck->status : '')=='inactive'?'selected':'' }}>غير نشطة</option>
                                    <option value="maintenance" {{ old('status', isset($editTruck) ? $editTruck->status : '')=='maintenance'?'selected':'' }}>صيانة</option>
                                </select>
                                <div class="error-message">@error('status'){{ $message }}@enderror</div>
                            </div>
                            <div class="input-group">
                                <label>السائق الحالي</label>
                                <select name="driver_id">
                                    <option value="">اختر السائق</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id', isset($editTruck) ? $editTruck->driver_id : '') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                <div class="error-message">@error('driver_id'){{ $message }}@enderror</div>
                            </div>
                            <div class="input-group">
                                <label>تاريخ آخر صيانة (إن وجد)</label>
                                <input type="date" name="maintenance_date" value="{{ old('maintenance_date', isset($editTruck) ? $editTruck->maintenance_date : '') }}">
                                <div class="error-message">@error('maintenance_date'){{ $message }}@enderror</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="reset" class="cancel-btn">إلغاء</button>
                        <button class="submit-btn" aria-label="{{ isset($editTruck) ? 'تحديث الشاحنة' : 'حفظ الشاحنة' }}">{{ isset($editTruck) ? 'تحديث الشاحنة' : 'حفظ الشاحنة' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  const showFormBtn = document.getElementById("showTruckFormBtn");
  const truckForm = document.getElementById("truckForm");
  const cancelBtn = document.querySelector("#truckForm .cancel-btn");
  const backBtn = document.querySelector("#truckForm .back-btn");

  // Show form automatically if there are errors or old data
  @if($errors->any() || count(old()))
    if(truckForm) truckForm.style.display = "block";
  @endif

  if (showFormBtn && truckForm) {
    showFormBtn.addEventListener("click", function() {
      truckForm.style.display = "block";
      truckForm.scrollIntoView({ behavior: "smooth" });
    });
  }
  if (cancelBtn) {
    cancelBtn.addEventListener("click", function(e) {
      e.preventDefault();
      truckForm.style.display = "none";
    });
  }
  if (backBtn) {
    backBtn.addEventListener("click", function(e) {
      e.preventDefault();
      truckForm.style.display = "none";
    });
  }
});
</script>
@endsection 