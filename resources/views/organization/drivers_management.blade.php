@extends('layouts.app')

@section('title', 'إدارة السائقين')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .drivers-sections {
            margin-bottom: 20px;
        }
        
        .drivers-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .section-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-header h3 {
            margin: 0;
            color: #333;
            font-size: 1.2em;
        }
        
        .section-count {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        .section-info {
            padding: 10px 20px;
            background: #e9ecef;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9em;
            color: #495057;
        }
        
        .drivers-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .drivers-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: right;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9em;
        }
        
        .drivers-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            text-align: right;
            font-size: 0.9em;
        }
        
        .drivers-table tr:hover {
            background: #f8f9fa;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        .status.assigned {
            background: #d4edda;
            color: #155724;
        }
        
        .status.available {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .action-btn {
            border: none;
            background: none;
            padding: 6px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }
        
        .assign-btn {
            color: #28a745;
        }
        
        .assign-btn:hover {
            background: #e8f5e8;
        }
        
        .unassign-btn {
            color: #dc3545;
        }
        
        .unassign-btn:hover {
            background: #ffebee;
        }
        
        .view-btn {
            color: #007bff;
        }
        
        .view-btn:hover {
            background: #e3f2fd;
        }
        
        .no-drivers {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .no-drivers i {
            font-size: 3em;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
            text-align: right;
        }
        
        .close-button {
            color: #aaa;
            float: left;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .input-group select,
        .input-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .modal-footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .modal-footer .cancel-btn {
            background-color: #ccc;
            color: #333;
        }
        
        .modal-footer .submit-btn {
            background-color: #007bff;
            color: white;
        }
        
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
                <h1><i class="fas fa-users"></i> إدارة السائقين</h1>
            </div>
            
            <div class="drivers-sections">
                <!-- Current Institution Drivers -->
                <div class="drivers-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user-check"></i> سائقو المؤسسة</h3>
                        <span class="section-count">{{ $institutionDrivers->count() }}</span>
                    </div>
                    <div class="section-info">
                        السائقون المرتبطون بمؤسستك حالياً
                    </div>
                    
                    @if($institutionDrivers->count() > 0)
                    <div class="stations-table-container">
                        <table class="stations-table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الجوال</th>
                                    <th>المدينة</th>
                                    <th>الشاحنة المعينة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($institutionDrivers as $driver)
                                <tr>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->phone ?? '-' }}</td>
                                    <td>{{ $driver->city ?? '-' }}</td>
                                    <td>{{ $driver->truck->truck_number ?? 'لا يوجد' }}</td>
                                    <td>
                                        <div class="actions">
                                            <button class="view-btn action-btn" title="عرض التفاصيل" onclick="viewDriver({{ $driver->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($driver->truck)
                                                <form method="POST" action="{{ route('organization.trucks.unassign', $driver->truck->id) }}" class="unassign-form" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="unassign-btn action-btn" title="فك تعيين الشاحنة" onclick="return confirm('هل أنت متأكد من فك تعيين الشاحنة من هذا السائق؟');">
                                                        <i class="fas fa-unlink"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="assign-btn action-btn" title="تعيين شاحنة" data-driver-id="{{ $driver->id }}">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                            @endif
                                            <form method="POST" action="{{ route('organization.drivers.unassign', $driver->id) }}" class="unassign-form" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="unassign-btn action-btn" title="فك التعيين من المؤسسة" onclick="return confirm('هل أنت متأكد من فك تعيين هذا السائق من المؤسسة؟');">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-drivers">
                        <i class="fas fa-users"></i>
                        <h3>لا يوجد سائقون مرتبطون</h3>
                        <p>يمكنك تعيين سائقين من القائمة المتاحة</p>
                    </div>
                    @endif
                </div>
                
                <!-- Available Drivers -->
                <div class="drivers-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user-plus"></i> السائقون المتاحون</h3>
                        <span class="section-count">{{ $availableDrivers->count() }}</span>
                    </div>
                    <div class="section-info">
                        سائقون متاحون للتعيين من جميع المؤسسات
                    </div>
                    
                    @if($availableDrivers->count() > 0)
                    <div class="stations-table-container">
                        <table class="stations-table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الجوال</th>
                                    <th>المدينة</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableDrivers as $driver)
                                <tr>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->phone ?? '-' }}</td>
                                    <td>{{ $driver->city ?? '-' }}</td>
                                    <td>{{ $driver->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="actions">
                                            <button class="view-btn action-btn" title="عرض التفاصيل" onclick="viewDriver({{ $driver->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <form method="POST" action="{{ route('organization.drivers.assign', $driver->id) }}" class="assign-form" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="assign-btn action-btn" title="تعيين للمؤسسة" onclick="return confirm('هل أنت متأكد من تعيين هذا السائق لمؤسستك؟');">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-drivers">
                        <i class="fas fa-user-slash"></i>
                        <h3>لا يوجد سائقون متاحون</h3>
                        <p>جميع السائقين مرتبطون بمؤسسات</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Assign Truck Modal -->
            <div id="assignTruckModal" class="modal">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <h2>تعيين شاحنة للسائق</h2>
                    <form id="assignTruckForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="driver_id" id="modalDriverId">
                        <div class="input-group">
                            <label for="truck_id">اختر الشاحنة</label>
                            <select name="truck_id" id="truck_id">
                                <option value="">لا يوجد شاحنات متاحة</option>
                                @foreach($availableTrucks as $truck)
                                    <option value="{{ $truck->id }}">{{ $truck->truck_number }} ({{ $truck->truck_type }})</option>
                                @endforeach
                            </select>
                            <div class="error-message">@error('truck_id'){{ $message }}@enderror</div>
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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // View driver details
    window.viewDriver = function(driverId) {
        // Implement view functionality
        alert('عرض تفاصيل السائق رقم: ' + driverId);
    };
    
    // Assign Truck Modal Logic
    const assignTruckModal = document.getElementById('assignTruckModal');
    const closeButton = assignTruckModal.querySelector('.close-button');
    const modalCancelButton = assignTruckModal.querySelector('.cancel-btn');
    const assignTruckForm = document.getElementById('assignTruckForm');
    const modalDriverIdInput = document.getElementById('modalDriverId');

    document.querySelectorAll('.assign-btn[data-driver-id]').forEach(button => {
        button.addEventListener('click', function() {
            const driverId = this.dataset.driverId;
            modalDriverIdInput.value = driverId;
            // Build URL properly
            const baseUrl = '{{ route("organization.drivers.assignTruck", ["driver" => "DRIVER_ID"]) }}';
            assignTruckForm.action = baseUrl.replace('DRIVER_ID', driverId);
            assignTruckModal.style.display = 'flex';
        });
    });

    closeButton.addEventListener('click', function() {
        assignTruckModal.style.display = 'none';
    });

    modalCancelButton.addEventListener('click', function() {
        assignTruckModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == assignTruckModal) {
            assignTruckModal.style.display = 'none';
        }
    });
});
</script>
@endsection 