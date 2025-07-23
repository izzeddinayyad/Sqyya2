@extends('layouts.app')

@section('title', 'محطات التحلية')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
    <style>
        .error-message {
            color: #d32f2f;
            font-size: 0.85em;
            min-height: 18px;
            margin-top: 2px;
            margin-bottom: 4px;
            text-align: right;
            line-height: 1.2;
        }
        .alert {
            padding: 10px 18px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 1em;
            text-align: right;
        }
        .alert-success {
            background: #e8f5e9;
            color: #388e3c;
            border: 1px solid #c8e6c9;
        }
        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
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
      <!-- Stations Dashboard -->
      <div class="stations-dashboard">
        <div class="stations-header">
          <h1>محطات التحلية</h1>
          <button id="showFormBtn" class="add-station-btn" type="button">
            <i class="fas fa-plus"></i>
            إضافة محطة جديدة
          </button>
        </div>
        <div class="stations-table-container">
          <table class="stations-table">
            <thead>
              <tr>
                <th>اسم المحطة</th>
                <th>الموقع</th>
                <th>السعة اليومية</th>
                <th>السائقون</th>
                <th>حالة التشغيل</th>
                <th>الإجراءات</th>
              </tr>
            </thead>
            <tbody>
@foreach($stations as $station)
              <tr>
                <td>{{ $station->name }}</td>
                <td>{{ $station->location }}</td>
                <td>{{ $station->daily_capacity }}</td>
                <td>{{ $station->drivers }}</td>
                <td>
                  <span class="status {{ $station->status == 'active' ? 'active' : ($station->status == 'inactive' ? 'inactive' : 'maintenance') }}">
                      @if($station->status == 'active')
                          نشطة
                      @elseif($station->status == 'inactive')
                          غير نشطة
                      @else
                          صيانة
                      @endif
                  </span>
                </td>
                <td>
                  <div class="actions">
                    <button class="edit-btn" data-edit-url="{{ route('organization.stations.edit', $station) }}"><i class="fas fa-edit"></i></button>
                    <form method="POST" action="{{ route('organization.stations.destroy', $station) }}" class="delete-form" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="delete-btn"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
@endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- Add Station Form (Hidden by default) -->
      @php
          $isEdit = isset($editStation);
      @endphp
      <div id="stationForm" class="add-station-form" style="display: {{ $errors->any() || old() || $isEdit ? 'block' : 'none' }};">
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
          <h1><i class="fas fa-plus-circle"></i> {{ $isEdit ? 'تعديل محطة تحلية' : 'إضافة محطة تحلية جديدة' }}</h1>
          <button class="back-btn"><i class="fas fa-arrow-right"></i> رجوع</button>
        </div>
        <form class="station-form" method="POST" action="{{ $isEdit ? route('organization.stations.update', $editStation) : route('organization.stations.store') }}" enctype="multipart/form-data">
          @csrf
          @if($isEdit)
            @method('PUT')
          @endif
          <!-- Location Section -->
          <div class="form-section">
            <h2>الموقع الجغرافي</h2>
            <div class="map-container" id="stationMap"></div>
            <div class="coordinates">
              <div class="input-group">
                <label>إحداثيات الموقع الجغرافي</label>
                <input type="text" id="coordinates" name="coordinates" value="{{ old('coordinates', $isEdit ? $editStation->coordinates : '') }}" readonly required>
                <input type="hidden" id="location" name="location" value="{{ old('location', $isEdit ? $editStation->location : '') }}">
                <div class="error-message">@error('coordinates'){{ $message }}@enderror</div>
              </div>
            </div>
          </div>
          <!-- Capacity Section -->
          <div class="form-section">
            <h2>السعة اليومية</h2>
            <div class="capacity-fields">
              <div class="input-group">
                <label>السعة (لتر)</label>
                <input type="number" name="daily_capacity" placeholder="9994" value="{{ old('daily_capacity', $isEdit ? $editStation->daily_capacity : '') }}">
                <div class="error-message">@error('daily_capacity'){{ $message }}@enderror</div>
              </div>
              <div class="input-group">
                <label>نسبة التشغيل</label>
                <input type="number" name="utilization" placeholder="99" max="100" value="{{ old('utilization', $isEdit ? $editStation->utilization : '') }}">
                <span class="percent-sign">%</span>
                <div class="error-message">@error('utilization'){{ $message }}@enderror</div>
              </div>
            </div>
          </div>
          <!-- Station Info Section -->
          <div class="form-section">
            <h2>معلومات المحطة</h2>
            <div class="info-fields">
              <div class="input-group">
                <label>اسم المحطة</label>
                <input type="text" name="name" placeholder="محطة التحلية الشمالية" value="{{ old('name', $isEdit ? $editStation->name : '') }}">
                <div class="error-message">@error('name'){{ $message }}@enderror</div>
              </div>
              <div class="input-group">
                <label>المحافظة</label>
                <select name="city">
                  <option value="">اختر المحافظة</option>
                  <option value="الرياض" {{ old('city', $isEdit ? $editStation->city : '')=='الرياض'?'selected':'' }}>الرياض</option>
                  <option value="مكة المكرمة" {{ old('city', $isEdit ? $editStation->city : '')=='مكة المكرمة'?'selected':'' }}>مكة المكرمة</option>
                  <option value="المدينة المنورة" {{ old('city', $isEdit ? $editStation->city : '')=='المدينة المنورة'?'selected':'' }}>المدينة المنورة</option>
                  <option value="الشرقية" {{ old('city', $isEdit ? $editStation->city : '')=='الشرقية'?'selected':'' }}>الشرقية</option>
                </select>
                <div class="error-message">@error('city'){{ $message }}@enderror</div>
              </div>
              <div class="input-group">
                  <label>حالة التشغيل</label>
                  <select name="status">
                      <option value="active" {{ (old('status', $isEdit ? $editStation->status : '') == 'active') ? 'selected' : '' }}>نشطة</option>
                      <option value="inactive" {{ (old('status', $isEdit ? $editStation->status : '') == 'inactive') ? 'selected' : '' }}>غير نشطة</option>
                      <option value="maintenance" {{ (old('status', $isEdit ? $editStation->status : '') == 'maintenance') ? 'selected' : '' }}>صيانة</option>
                  </select>
                  <div class="error-message">@error('status'){{ $message }}@enderror</div>
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
                @if($isEdit && $editStation->image)
                  <div style="margin: 5px 0; font-size: 0.95em; color: #555;">الصورة الحالية: <a href="{{ asset('storage/'.$editStation->image) }}" target="_blank">عرض</a></div>
                @endif
                <div class="error-message">@error('image'){{ $message }}@enderror</div>
              </div>
              <div class="file-status">لم يتم اختيار ملف</div>
            </div>
          </div>
          <!-- Form Actions -->
          <div class="form-actions">
            <button type="reset" class="cancel-btn">إلغاء</button>
            <button class="submit-btn" aria-label="{{ $isEdit ? 'تحديث المحطة' : 'حفظ المحطة' }}">{{ $isEdit ? 'تحديث المحطة' : 'حفظ المحطة' }}</button>
          </div>
        </form>
      </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Sidebar toggle functionality
      const showFormBtn = document.getElementById("showFormBtn");
      const stationForm = document.getElementById("stationForm");
      const cancelBtn = document.querySelector(".cancel-btn");
      const backBtn = document.querySelector(".back-btn");
      // إظهار الفورم تلقائياً إذا كان هناك أخطاء أو بيانات قديمة
      @if($errors->any() || count(old()) || isset($editStation))
        if(stationForm) stationForm.style.display = "block";
      @endif
      if (showFormBtn && stationForm) {
        showFormBtn.addEventListener("click", function() {
          stationForm.style.display = "block";
          stationForm.scrollIntoView({ behavior: "smooth" });
        });
      }
      if (cancelBtn) {
        cancelBtn.addEventListener("click", function(e) {
          e.preventDefault();
          stationForm.style.display = "none";
        });
      }
      if (backBtn) {
        backBtn.addEventListener("click", function(e) {
          e.preventDefault();
          stationForm.style.display = "none";
        });
      }
      // Initialize map if element exists
      const mapElement = document.getElementById("stationMap");
      if (mapElement) {
        const map = L.map("stationMap").setView([24.7136, 46.6753], 12);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 18,
        }).addTo(map);
        let marker = L.marker([24.7136, 46.6753], {draggable:true}).addTo(map);
        function updateCoords(lat, lng) {
          document.getElementById('coordinates').value = lat.toFixed(6) + ', ' + lng.toFixed(6);
          document.getElementById('location').value = lat.toFixed(6) + ', ' + lng.toFixed(6);
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
      // زر التعديل يفتح فورم التعديل
      document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          window.location = btn.getAttribute('data-edit-url');
        });
      });
      // زر الحذف يظهر popup تأكيد
      document.querySelectorAll('.delete-form .delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if(confirm('Are you sure you want to delete this station?')) {
            btn.closest('form').submit();
          }
        });
      });
    });
    </script>
@endsection 