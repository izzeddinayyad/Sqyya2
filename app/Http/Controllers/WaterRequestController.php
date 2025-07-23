<?php

namespace App\Http\Controllers;

use App\Models\WaterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaterRequestController extends Controller
{
    /**
     * عرض صفحة إنشاء طلب مياه جديد
     */
    public function create()
    {
        return view('delegate.create_water_request');
    }

    /**
     * حفظ طلب مياه جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:500',
            'scheduled_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        $waterRequest = WaterRequest::create([
            'user_id' => Auth::id(),
            'representative_id' => Auth::id(), // المندوب الذي أنشأ الطلب
            'emergency' => false,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'location' => $request->location,
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
        ]);

        return redirect()->route('delegate.water-requests')
            ->with('success', 'تم إرسال طلب المياه بنجاح!');
    }

    /**
     * عرض قائمة طلبات المياه للمندوب
     */
    public function index()
    {
        $waterRequests = WaterRequest::where('representative_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('delegate.water_requests', compact('waterRequests'));
    }

    /**
     * عرض تفاصيل طلب مياه
     */
    public function show(WaterRequest $waterRequest)
    {
        // التحقق من أن المندوب يملك هذا الطلب
        if ($waterRequest->representative_id !== Auth::id()) {
            abort(403);
        }

        return view('delegate.water_request_details', compact('waterRequest'));
    }

    /**
     * تحديث حالة طلب المياه
     */
    public function updateStatus(Request $request, WaterRequest $waterRequest)
    {
        // التحقق من أن المندوب يملك هذا الطلب
        if ($waterRequest->representative_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled'
        ]);

        $waterRequest->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }

    /**
     * حذف طلب مياه
     */
    public function destroy(WaterRequest $waterRequest)
    {
        // التحقق من أن المندوب يملك هذا الطلب
        if ($waterRequest->representative_id !== Auth::id()) {
            abort(403);
        }
        $waterRequest->delete();
        return redirect()->route('delegate.water-requests')->with('success', 'تم حذف الطلب بنجاح!');
    }
}
