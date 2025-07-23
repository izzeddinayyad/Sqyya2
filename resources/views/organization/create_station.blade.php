@extends('layouts.app')
@section('title', 'إضافة محطة جديدة')
@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
@endsection
@section('content')
<div class="container mt-5">
    <div class="add-station-form show">
        <div class="form-header">
            <h1><i class="fas fa-plus-circle"></i> إضافة محطة تحلية جديدة</h1>
            <a href="{{ route('organization.stations') }}" class="back-btn"><i class="fas fa-arrow-right"></i> رجوع</a>
        </div>
        <form class="station-form" method="POST" action="{{ route('organization.stations.create') }}" enctype="multipart/form-data">
            @csrf
            <!-- Location Section -->
            <div class="form-section">
                <h2>الموقع الجغرافي</h2>
                <div class="map-container" id="stationMap"></div>
                <div class="coordinates">
                    <div class="input-group">
                        <label>إحداثيات الموقع الجغرافي</label>
                        <input type="text" id="coordinates" name="coordinates" value="" readonly required>
                    </div>
                </div>
            </div>
            <!-- Capacity Section -->
            <div class="form-section">
                <h2>السعة اليومية</h2>
                <div class="capacity-fields">
                    <div class="input-group">
                        <label>السعة (لتر)</label>
                        <input type="number" name="daily_capacity" placeholder="9994">
                    </div>
                    <div class="input-group">
                        <label>نسبة التشغيل</label>
                        <input type="number" name="utilization" placeholder="99" max="100">
                        <span class="percent-sign">%</span>
                    </div>
                </div>
            </div>
            <!-- Station Info Section -->
            <div class="form-section">
                <h2>معلومات المحطة</h2>
                <div class="info-fields">
                    <div class="input-group">
                        <label>اسم المحطة</label>
                        <input type="text" name="name" placeholder="محطة التحلية الشمالية">
                    </div>
                    <div class="input-group">
                        <label>المحافظة</label>
                        <select name="city">
                            <option>الرياض</option>
                            <option>مكة المكرمة</option>
                            <option>المدينة المنورة</option>
                            <option>الشرقية</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Image Upload Section -->
            <div class="form-section">
                <h2>صورة المحطة</h2>
                <div class="image-upload">
                    <div class="upload-container">
                        <i class="fas fa-camera"></i>
                        <p>اختر ملف</p>
                        <input type="file" id="stationImage" name="image" accept="image/*">
                    </div>
                    <div class="file-status">لم يتم اختيار ملف</div>
                </div>
            </div>
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="reset" class="cancel-btn">إلغاء</button>
                <button class="submit-btn" aria-label="حفظ المحطة">حفظ المحطة</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize map
        const mapElement = document.getElementById("stationMap");
        if (mapElement) {
            const map = L.map("stationMap").setView([24.7136, 46.6753], 12);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 18,
            }).addTo(map);
            let marker = L.marker([24.7136, 46.6753], {draggable:true}).addTo(map);
            function updateCoords(lat, lng) {
                document.getElementById('coordinates').value = lat.toFixed(6) + ', ' + lng.toFixed(6);
            }
            updateCoords(24.7136, 46.6753);
            marker.on('dragend', function(e) {
                const {lat, lng} = marker.getLatLng();
                updateCoords(lat, lng);
            });
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoords(e.latlng.lat, e.latlng.lng);
            });
        }
        // File upload display
        const fileInput = document.getElementById("stationImage");
        const fileStatus = document.querySelector(".file-status");
        if (fileInput && fileStatus) {
            fileInput.addEventListener("change", function() {
                if(this.files.length > 0) {
                    fileStatus.textContent = this.files[0].name;
                } else {
                    fileStatus.textContent = 'لم يتم اختيار ملف';
                }
            });
        }
        // Cancel button
        const cancelBtn = document.querySelector('.cancel-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location = "{{ route('organization.stations') }}";
            });
        }
    });
    </script>
@endsection 