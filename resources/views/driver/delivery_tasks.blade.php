@extends('layouts.app')

@section('title', 'مهام التوصيل')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/stationsDashboard.css') }}" />
@endsection

@section('content')
<div class="container-full flex">
    @include('driver.components.sidebar')
    <div class="main-content">
        <div class="stations-dashboard">
            <div class="stations-header">
                <h1>مهام التوصيل</h1>
            </div>
            <div class="stations-table-container">
                <table class="stations-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>المحطة</th>
                            <th>المدينة</th>
                            <th>كمية المياه</th>
                            <th>حالة الطلب</th>
                            <th>تاريخ التعيين</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>WR-{{ 10000 + $task->id }}</td>
                            <td>{{ $task->distributionPoint->name ?? '-' }}</td>
                            <td>{{ $task->location ?? '-' }}</td>
                            <td>{{ number_format($task->quantity) }} لتر</td>
                            <td>
                                <span class="status {{ $task->status == 'assigned' ? 'active' : ($task->status == 'in_progress' ? 'maintenance' : ($task->status == 'completed' ? 'inactive' : '') ) }}">
                                    @if($task->status == 'assigned') بإنتظار التوصيل
                                    @elseif($task->status == 'in_progress') جاري التنفيذ
                                    @elseif($task->status == 'completed') مكتمل
                                    @else {{ $task->status }}
                                    @endif
                                </span>
                            </td>
                            <td>{{ $task->updated_at->format('d-m-Y') }}</td>
                            <td>
                                <div class="actions">
                                    @if($task->status == 'assigned')
                                        <form method="POST" action="{{ route('driver.tasks.start', $task->id) }}" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="edit-btn" title="بدء التوصيل"><i class="fas fa-play"></i></button>
                                        </form>
                                    @elseif($task->status == 'in_progress')
                                        <form method="POST" action="{{ route('driver.tasks.complete', $task->id) }}" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="edit-btn" title="إنهاء المهمة"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">لا توجد مهام توصيل حالياً.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 