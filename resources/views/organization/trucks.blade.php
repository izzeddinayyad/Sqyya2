@extends('layouts.app')

@section('title', 'إدارة الشاحنات')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
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
        
        <!-- Trucks Dashboard -->
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1><i class="fas fa-truck"></i> إدارة الشاحنات</h1>
                <button id="showFormBtn" class="add-station-btn" type="button">
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
                            <th>السائق المعين</th>
                            <th>تاريخ الصيانة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trucks as $truck)
                        <tr>
                            <td>{{ $truck->truck_number }}</td>
                            <td>{{ $truck->truck_type }}</td>
                            <td>{{ number_format($truck->tank_capacity) }} لتر</td>
                            <td>{{ $truck->driver->name ?? 'غير معين' }}</td>
                            <td>{{ $truck->maintenance_date ? \Carbon\Carbon::parse($truck->maintenance_date)->format('Y-m-d') : '-' }}</td>
                            <td>
                                <span class="status {{ $truck->status == 'active' ? 'active' : ($truck->status == 'inactive' ? 'inactive' : 'maintenance') }}">
                                    @if($truck->status == 'active')
                                        نشطة
                                    @elseif($truck->status == 'inactive')
                                        غير نشطة
                                    @else
                                        صيانة
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="edit-btn" data-edit-url="{{ route('organization.trucks.edit', $truck) }}"><i class="fas fa-edit"></i></button>
                                    @if($truck->driver)
                                        <form method="POST" action="{{ route('organization.trucks.unassign', $truck->id) }}" class="unassign-form" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="unassign-btn" title="فك تعيين السائق" onclick="return confirm('هل أنت متأكد من فك تعيين السائق من هذه الشاحنة؟');">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="assign-btn" title="تعيين سائق" data-truck-id="{{ $truck->id }}">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                    <form method="POST" action="{{ route('organization.trucks.destroy', $truck) }}" class="delete-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف الشاحنة؟');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Add Truck Form (Hidden by default) -->
        @php
            $isEdit = isset($editTruck);
        @endphp
        <div id="truckForm" class="add-station-form" style="display: {{ $errors->any() || old() || $isEdit ? 'block' : 'none' }};">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    هناك أخطاء في البيانات المدخلة. يرجى تصحيحها بالأسفل.
                    <ul style="margin: 8px 0 0 0; padding-right: 18px;">
                        @foreach($errors->all() as $error)
                            <li style="font-size:0.97em;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="form-header">
                <h1><i class="fas fa-plus-circle"></i> {{ $isEdit ? 'تعديل شاحنة' : 'إضافة شاحنة جديدة' }}</h1>
                <button class="back-btn"><i class="fas fa-arrow-right"></i> رجوع</button>
            </div>
            
            <form class="station-form" method="POST" action="{{ $isEdit ? route('organization.trucks.update', $editTruck) : route('organization.trucks.store') }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                
                <!-- Truck Info Section -->
                <div class="form-section">
                    <h2>معلومات الشاحنة</h2>
                    <div class="info-fields">
                        <div class="input-group">
                            <label>رقم الشاحنة</label>
                            <input type="text" name="truck_number" placeholder="TRK-001" value="{{ old('truck_number', $isEdit ? $editTruck->truck_number : '') }}">
                            <div class="error-message">@error('truck_number'){{ $message }}@enderror</div>
                        </div>
                        <div class="input-group">
                            <label>نوع الشاحنة</label>
                            <input type="text" name="truck_type" placeholder="شاحنة نقل مياه" value="{{ old('truck_type', $isEdit ? $editTruck->truck_type : '') }}">
                            <div class="error-message">@error('truck_type'){{ $message }}@enderror</div>
                        </div>
                        <div class="input-group">
                            <label>سعة الخزان (لتر)</label>
                            <input type="number" name="tank_capacity" placeholder="5000" value="{{ old('tank_capacity', $isEdit ? $editTruck->tank_capacity : '') }}">
                            <div class="error-message">@error('tank_capacity'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
                
                <!-- Driver Assignment Section -->
                <div class="form-section">
                    <h2>تعيين السائق</h2>
                    <div class="info-fields">
                        <div class="input-group">
                            <label>السائق المعين</label>
                            <select name="driver_id">
                                <option value="">اختر السائق</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ (old('driver_id', $isEdit ? $editTruck->driver_id : '') == $driver->id) ? 'selected' : '' }}>
                                        {{ $driver->name }} ({{ $driver->phone }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-message">@error('driver_id'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Section -->
                <div class="form-section">
                    <h2>حالة الشاحنة</h2>
                    <div class="info-fields">
                        <div class="input-group">
                            <label>الحالة</label>
                            <select name="status">
                                <option value="active" {{ (old('status', $isEdit ? $editTruck->status : '') == 'active') ? 'selected' : '' }}>نشطة</option>
                                <option value="inactive" {{ (old('status', $isEdit ? $editTruck->status : '') == 'inactive') ? 'selected' : '' }}>غير نشطة</option>
                                <option value="maintenance" {{ (old('status', $isEdit ? $editTruck->status : '') == 'maintenance') ? 'selected' : '' }}>صيانة</option>
                            </select>
                            <div class="error-message">@error('status'){{ $message }}@enderror</div>
                        </div>
                        <div class="input-group">
                            <label>تاريخ الصيانة القادمة</label>
                            <input type="date" name="maintenance_date" value="{{ old('maintenance_date', $isEdit ? $editTruck->maintenance_date : '') }}">
                            <div class="error-message">@error('maintenance_date'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="reset" class="cancel-btn">إلغاء</button>
                    <button class="submit-btn" aria-label="{{ $isEdit ? 'تحديث الشاحنة' : 'حفظ الشاحنة' }}">{{ $isEdit ? 'تحديث الشاحنة' : 'حفظ الشاحنة' }}</button>
                </div>
            </form>
        </div>

        <!-- Assign Driver Modal -->
        <div id="assignDriverModal" class="modal">
            <div class="modal-content">
                <span class="close-button">&times;</span>
                <h2>تعيين سائق للشاحنة</h2>
                <form id="assignDriverForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="truck_id" id="modalTruckId">
                    <div class="input-group">
                        <label for="driver_id">اختر السائق</label>
                        <select name="driver_id" id="driver_id">
                            <option value="">اختر السائق</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->phone }})</option>
                            @endforeach
                        </select>
                        <div class="error-message">@error('driver_id'){{ $message }}@enderror</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn">إلغاء</button>
                        <button type="submit" class="submit-btn">تعيين</button>
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
    const showFormBtn = document.getElementById("showFormBtn");
    const truckForm = document.getElementById("truckForm");
    const cancelBtn = document.querySelector("#truckForm .cancel-btn");
    const backBtn = document.querySelector("#truckForm .back-btn");

    // Show form automatically if there are errors or old data
    @if($errors->any() || old())
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

    // Edit Truck logic
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location = this.getAttribute('data-edit-url');
        });
    });

    // Assign Driver Modal Logic
    const assignDriverModal = document.getElementById('assignDriverModal');
    const closeButton = assignDriverModal.querySelector('.close-button');
    const modalCancelButton = assignDriverModal.querySelector('.cancel-btn');
    const assignDriverForm = document.getElementById('assignDriverForm');
    const modalTruckIdInput = document.getElementById('modalTruckId');

    document.querySelectorAll('.assign-btn[data-truck-id]').forEach(button => {
        button.addEventListener('click', function() {
            const truckId = this.dataset.truckId;
            modalTruckIdInput.value = truckId;
            // Build URL properly
            const baseUrl = '{{ route("organization.trucks.assignDriver", ["truck" => "TRUCK_ID"]) }}';
            assignDriverForm.action = baseUrl.replace('TRUCK_ID', truckId);
            assignDriverModal.style.display = 'flex';
        });
    });

    closeButton.addEventListener('click', function() {
        assignDriverModal.style.display = 'none';
    });

    modalCancelButton.addEventListener('click', function() {
        assignDriverModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == assignDriverModal) {
            assignDriverModal.style.display = 'none';
        }
    });
});
</script>
@endsection 