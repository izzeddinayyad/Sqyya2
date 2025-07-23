<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driverId = auth()->id();
        $tasks = \App\Models\WaterRequest::with(['distributionPoint'])
            ->where('driver_id', $driverId)
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->orderByDesc('updated_at')
            ->get();
        $tasks_assigned = \App\Models\WaterRequest::with(['distributionPoint'])
            ->where('driver_id', $driverId)
            ->where('status', 'assigned')
            ->orderByDesc('assigned_at')
            ->get();
        return view('driver.dashboard', compact('tasks', 'tasks_assigned'));
    }

    public function deliveryTasks()
    {
        $driverId = auth()->id();
        $tasks = \App\Models\WaterRequest::with(['distributionPoint'])
            ->where('driver_id', $driverId)
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->orderByDesc('updated_at')
            ->get();
        return view('driver.delivery_tasks', compact('tasks'));
    }

    public function startTask($taskId)
    {
        $task = \App\Models\WaterRequest::where('id', $taskId)
            ->where('driver_id', auth()->id())
            ->where('status', 'assigned')
            ->firstOrFail();
        $task->update(['status' => 'in_progress']);
        return redirect()->route('driver.delivery_tasks')->with('success', 'تم بدء التوصيل بنجاح.');
    }

    public function completeTask($taskId)
    {
        $task = \App\Models\WaterRequest::where('id', $taskId)
            ->where('driver_id', auth()->id())
            ->where('status', 'in_progress')
            ->firstOrFail();
        $task->update(['status' => 'completed']);
        return redirect()->route('driver.delivery_tasks')->with('success', 'تم إنهاء المهمة بنجاح.');
    }
} 