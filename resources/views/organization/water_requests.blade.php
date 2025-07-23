@extends('layouts.app')

@section('title', 'طلبات المياه')

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
    @include('organization.components.sidebar')
    <div class="main-content">
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1>طلبات المياه</h1>
            </div>
            <div class="stations-table-container">
                <table class="stations-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>المندوب</th>
                            <th>الكمية المطلوبة</th>
                            <th>نوع الطلب</th>
                            <th>الموقع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td>WR-{{ 10000 + $req->id }}</td>
                            <td>{{ $req->representative->name ?? '-' }}</td>
                            <td>{{ number_format($req->quantity) }} لتر</td>
                            <td>{{ $req->type ?? ($req->point_id ? 'توزيع نقطة' : 'صهريج كامل') }}</td>
                            <td>{{ $req->location ?? ($req->distributionPoint->name ?? '-') }}</td>
                            <td>
                                <span class="status {{ $req->status == 'new' ? 'active' : ($req->status == 'in_progress' ? 'maintenance' : ($req->status == 'completed' ? 'inactive' : '') ) }}">
                                    @if($req->status == 'new') جديد
                                    @elseif($req->status == 'assigned') تم التعيين
                                    @elseif($req->status == 'in_progress') قيد التنفيذ
                                    @elseif($req->status == 'completed') مكتمل
                                    @else {{ $req->status }}
                                    @endif
                                </span>
                            </td>
                            <td>{{ $req->created_at->format('d-m-Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('organization.water-requests.show', $req->id) }}" class="edit-btn" title="عرض"><i class="fas fa-eye"></i></a>
                                    @if(!$req->driver_id || !$req->truck_id)
                                        <button class="edit-btn assign-truck-btn" data-request-id="{{ $req->id }}" title="تعيين شاحنة"><i class="fas fa-truck"></i></button>
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
<!-- Modal for assigning truck -->
<div id="assignTruckModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeAssignTruckModal">&times;</span>
        <h3>تعيين شاحنة للطلب</h3>
        <form id="assignTruckForm" method="POST" action="">
            @csrf
            <input type="hidden" name="request_id" id="modalRequestId">
            <label for="truck_id">اختر الشاحنة المتوفرة:</label>
            <select name="truck_id" id="modalTruckId" required>
                <option value="">-- اختر الشاحنة --</option>
                @foreach($trucks as $truck)
                    <option value="{{ $truck->id }}">{{ $truck->truck_number }} ({{ $truck->driver?->name ?? 'بدون سائق' }})</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">تعيين</button>
        </form>
    </div>
</div>
@section('scripts')
<script>
document.querySelectorAll('.assign-truck-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const requestId = this.getAttribute('data-request-id');
        document.getElementById('modalRequestId').value = requestId;
        document.getElementById('assignTruckForm').action = `/organization/water-requests/${requestId}/assign-truck`;
        document.getElementById('assignTruckModal').style.display = 'block';
    });
});
document.getElementById('closeAssignTruckModal').onclick = function() {
    document.getElementById('assignTruckModal').style.display = 'none';
};
window.onclick = function(event) {
    if (event.target == document.getElementById('assignTruckModal')) {
        document.getElementById('assignTruckModal').style.display = 'none';
    }
};
</script>
@endsection 