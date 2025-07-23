<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Truck;
use App\Models\User;

class OrganizationController extends Controller
{
    public function updateStation(Request $request, $id)
    {
        $station = \App\Models\Station::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'daily_capacity' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive,maintenance',
            'city' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'coordinates' => 'nullable|string',
            'utilization' => 'nullable|numeric|min:0|max:100',
            // أضف أي حقول أخرى حسب الحاجة
        ]);

        $station->update($validated);
        return redirect()->route('organization.stations')->with('success', 'تم تحديث بيانات المحطة بنجاح!');
    }

    public function stations()
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $stations = \App\Models\Station::where('institution_id', $institutionId)->get();
        return view('organization.stations', compact('stations'));
    }

    public function editStation($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $editStation = \App\Models\Station::where('institution_id', $institutionId)->findOrFail($id);
        $stations = \App\Models\Station::where('institution_id', $institutionId)->get();
        return view('organization.stations', compact('stations', 'editStation'));
    }

    public function destroyStation($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $station = \App\Models\Station::where('institution_id', $institutionId)->findOrFail($id);
        $station->delete();
        return redirect()->route('organization.stations')->with('success', 'تم حذف المحطة بنجاح!');
    }

    public function dashboard()
    {
        return view('organization.dashboard');
    }

    public function statistics()
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        
        // Get statistics for the current institution only
        $stats = [
            'total_stations' => \App\Models\Station::where('institution_id', $institutionId)->count(),
            'active_stations' => \App\Models\Station::where('institution_id', $institutionId)->where('status', 'active')->count(),
            'total_trucks' => Truck::where('institution_id', $institutionId)->count(),
            'active_trucks' => Truck::where('institution_id', $institutionId)->where('status', 'active')->count(),
            'total_drivers' => User::where('institution_id', $institutionId)->where('role', 'driver')->count(),
            'assigned_trucks' => Truck::where('institution_id', $institutionId)->whereNotNull('driver_id')->count(),
            'unassigned_trucks' => Truck::where('institution_id', $institutionId)->whereNull('driver_id')->count(),
        ];
        
        return view('organization.statistics', compact('stats'));
    }

    public function trucks()
    {
        $user = auth()->user();
        $trucks = Truck::where('institution_id', $user->id)->with('driver')->get();
        $drivers = User::where('institution_id', $user->id)->where('role', 'driver')->get();
        return view('organization.trucks', compact('trucks', 'drivers'));
    }

    public function storeTruck(Request $request)
    {
        $validated = $request->validate([
            'truck_number' => 'required|string|max:255|unique:trucks',
            'truck_type' => 'required|string|max:255',
            'tank_capacity' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive,maintenance',
            'driver_id' => 'nullable|exists:users,id',
            'maintenance_date' => 'nullable|date',
        ]);

        $user = auth()->user();
        
        // Verify driver belongs to the same institution
        if ($request->driver_id) {
            $driver = User::where('id', $request->driver_id)
                         ->where('institution_id', $user->id)
                         ->where('role', 'driver')
                         ->first();
            if (!$driver) {
                return back()->withErrors(['driver_id' => 'السائق المحدد لا ينتمي لمؤسستك']);
            }
        }

        Truck::create(array_merge($validated, [
            'institution_id' => $user->id,
        ]));

        return redirect()->route('organization.trucks')->with('success', 'تمت إضافة الشاحنة بنجاح!');
    }

    public function editTruck($truck)
    {
        $user = auth()->user();
        $editTruck = Truck::where('institution_id', $user->id)->with('driver')->findOrFail($truck);
        $trucks = Truck::where('institution_id', $user->id)->with('driver')->get();
        $drivers = User::where('institution_id', $user->id)->where('role', 'driver')->get();
        return view('organization.trucks', compact('trucks', 'drivers', 'editTruck'));
    }

    public function updateTruck(Request $request, $truck)
    {
        $user = auth()->user();
        $truckModel = Truck::where('institution_id', $user->id)->findOrFail($truck);
        
        $validated = $request->validate([
            'truck_number' => 'required|string|max:255|unique:trucks,truck_number,' . $truck,
            'truck_type' => 'required|string|max:255',
            'tank_capacity' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive,maintenance',
            'driver_id' => 'nullable|exists:users,id',
            'maintenance_date' => 'nullable|date',
        ]);

        // Verify driver belongs to the same institution
        if ($request->driver_id) {
            $driver = User::where('id', $request->driver_id)
                         ->where('institution_id', $user->id)
                         ->where('role', 'driver')
                         ->first();
            if (!$driver) {
                return back()->withErrors(['driver_id' => 'السائق المحدد لا ينتمي لمؤسستك']);
            }
        }

        $truckModel->update($validated);

        return redirect()->route('organization.trucks')->with('success', 'تم تحديث بيانات الشاحنة بنجاح!');
    }

    public function destroyTruck($truck)
    {
        $user = auth()->user();
        $truckModel = Truck::where('institution_id', $user->id)->findOrFail($truck);
        $truckModel->delete();
        return redirect()->route('organization.trucks')->with('success', 'تم حذف الشاحنة بنجاح!');
    }

    public function storeStation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'daily_capacity' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive,maintenance',
            'city' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'coordinates' => 'nullable|string',
            'utilization' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $institutionId = auth()->user()->institution_id ?? auth()->id();
        
        $stationData = array_merge($validated, [
            'user_id' => auth()->id(),
            'institution_id' => $institutionId,
        ]);

        if ($request->hasFile('image')) {
            $stationData['image'] = $request->file('image')->store('stations', 'public');
        }

        \App\Models\Station::create($stationData);
        return redirect()->route('organization.stations')->with('success', 'تمت إضافة المحطة بنجاح!');
    }

    public function driversManagement()
    {
        $user = auth()->user();
        
        // Get drivers assigned to this institution
        $institutionDrivers = User::where('role', 'driver')
            ->where('institution_id', $user->id)
            ->with('truck')
            ->get();
        
        // Get available drivers (not assigned to any institution)
        $availableDrivers = User::where('role', 'driver')
            ->whereNull('institution_id')
            ->get();
        
        // Get available trucks for assignment
        $availableTrucks = Truck::where('institution_id', $user->id)
            ->whereNull('driver_id')
            ->get();
        
        return view('organization.drivers_management', compact('institutionDrivers', 'availableDrivers', 'availableTrucks'));
    }

    public function assignDriver($driver)
    {
        $user = auth()->user();
        $driverModel = User::where('role', 'driver')
            ->whereNull('institution_id')
            ->findOrFail($driver);
        
        $driverModel->update(['institution_id' => $user->id]);
        
        return redirect()->route('organization.drivers.management')
            ->with('success', 'تم تعيين السائق بنجاح');
    }

    public function unassignDriver($driver)
    {
        $user = auth()->user();
        $driverModel = User::where('role', 'driver')
            ->where('institution_id', $user->id)
            ->findOrFail($driver);
        
        // Unassign truck first if assigned
        if ($driverModel->truck) {
            $driverModel->truck->update(['driver_id' => null]);
        }
        
        $driverModel->update(['institution_id' => null]);
        
        return redirect()->route('organization.drivers.management')
            ->with('success', 'تم فك تعيين السائق بنجاح');
    }

    public function assignTruckToDriver(Request $request, $driver)
    {
        $user = auth()->user();
        
        $request->validate([
            'truck_id' => 'required|exists:trucks,id'
        ]);
        
        $driverModel = User::where('role', 'driver')
            ->where('institution_id', $user->id)
            ->findOrFail($driver);
        
        $truck = Truck::where('institution_id', $user->id)
            ->whereNull('driver_id')
            ->findOrFail($request->truck_id);
        
        // Unassign current driver from this truck if any
        if ($truck->driver_id) {
            $truck->update(['driver_id' => null]);
        }
        
        // Assign truck to driver
        $truck->update(['driver_id' => $driverModel->id]);
        
        return redirect()->route('organization.drivers.management')
            ->with('success', 'تم تعيين الشاحنة للسائق بنجاح');
    }

    public function unassignTruck($truck)
    {
        $user = auth()->user();
        $truckModel = Truck::where('institution_id', $user->id)
            ->findOrFail($truck);
        
        $truckModel->update(['driver_id' => null]);
        
        return redirect()->route('organization.drivers.management')
            ->with('success', 'تم فك تعيين الشاحنة بنجاح');
    }

    public function drivers()
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $drivers = User::where('institution_id', $institutionId)->where('role', 'driver')->get();
        $trucks = Truck::where('institution_id', $institutionId)->whereNull('driver_id')->get();
        return view('organization.drivers_management', compact('drivers', 'trucks'));
    }

    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $institutionId = auth()->user()->institution_id ?? auth()->id();

        User::create(array_merge($validated, [
            'role' => 'driver',
            'institution_id' => $institutionId,
            'password' => bcrypt($request->password),
        ]));

        return redirect()->route('organization.drivers')->with('success', 'تمت إضافة السائق بنجاح!');
    }

    public function editDriver($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $editDriver = User::where('institution_id', $institutionId)->where('role', 'driver')->findOrFail($id);
        $drivers = User::where('institution_id', $institutionId)->where('role', 'driver')->get();
        $trucks = Truck::where('institution_id', $institutionId)->whereNull('driver_id')->orWhere('driver_id', $editDriver->id)->get();
        return view('organization.drivers_management', compact('drivers', 'editDriver', 'trucks'));
    }

    public function updateDriver(Request $request, $id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $driver = User::where('institution_id', $institutionId)->where('role', 'driver')->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $data = $validated;
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $driver->update($data);

        return redirect()->route('organization.drivers')->with('success', 'تم تحديث بيانات السائق بنجاح!');
    }

    public function destroyDriver($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $driver = User::where('institution_id', $institutionId)->where('role', 'driver')->findOrFail($id);
        $driver->delete();
        return redirect()->route('organization.drivers')->with('success', 'تم حذف السائق بنجاح!');
    }

    public function delegates()
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        
        // Get delegates assigned to current institution
        $institutionDelegates = User::where('institution_id', $institutionId)
                        ->where('role', 'representative')
                        ->withCount(['stations', 'orders'])
                        ->get()
                        ->map(function ($delegate) {
                            // Add last activity (simulated for now)
                            $delegate->last_activity = $this->getLastActivity($delegate);
                            return $delegate;
                        });
        
        // Get available delegates (not assigned to any institution)
        $availableDelegates = User::where('role', 'representative')
                        ->whereNull('institution_id')
                        ->withCount(['stations', 'orders'])
                        ->get()
                        ->map(function ($delegate) {
                            // Add last activity (simulated for now)
                            $delegate->last_activity = $this->getLastActivity($delegate);
                            return $delegate;
                        });
        
        return view('organization.delegates', compact('institutionDelegates', 'availableDelegates'));
    }

    /**
     * Get last activity for delegate (simulated)
     */
    private function getLastActivity($delegate)
    {
        // This is a simulation - in real app, you'd track actual activity
        $activities = [
            'منذ ساعتين',
            'منذ يوم واحد',
            'منذ 3 أيام',
            'منذ أسبوع',
            'منذ شهر'
        ];
        
        return $activities[array_rand($activities)];
    }

    /**
     * Store a new delegate
     */
    public function storeDelegate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $institutionId = auth()->user()->institution_id ?? auth()->id();

        User::create(array_merge($validated, [
            'role' => 'representative',
            'institution_id' => $institutionId,
            'password' => bcrypt($request->password),
            'status' => $request->status ?? 'active',
        ]));

        return redirect()->route('organization.delegates')->with('success', 'تمت إضافة المندوب بنجاح!');
    }

    /**
     * Edit delegate
     */
    public function editDelegate($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $editDelegate = User::where('institution_id', $institutionId)
                           ->where('role', 'representative')
                           ->findOrFail($id);
        
        $delegates = User::where('institution_id', $institutionId)
                        ->where('role', 'representative')
                        ->withCount(['stations', 'orders'])
                        ->get()
                        ->map(function ($delegate) {
                            $delegate->last_activity = $this->getLastActivity($delegate);
                            return $delegate;
                        });
        
        return view('organization.delegates', compact('delegates', 'editDelegate'));
    }

    /**
     * Update delegate
     */
    public function updateDelegate(Request $request, $id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $delegate = User::where('institution_id', $institutionId)
                       ->where('role', 'representative')
                       ->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = $validated;
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $delegate->update($data);

        return redirect()->route('organization.delegates')->with('success', 'تم تحديث بيانات المندوب بنجاح!');
    }

    /**
     * Delete delegate
     */
    public function destroyDelegate($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $delegate = User::where('institution_id', $institutionId)
                       ->where('role', 'representative')
                       ->findOrFail($id);
        
        $delegate->delete();
        return redirect()->route('organization.delegates')->with('success', 'تم حذف المندوب بنجاح!');
    }

    /**
     * Toggle delegate status
     */
    public function toggleDelegateStatus($id)
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $delegate = User::where('institution_id', $institutionId)
                       ->where('role', 'representative')
                       ->findOrFail($id);
        
        $delegate->status = $delegate->status === 'active' ? 'inactive' : 'active';
        $delegate->save();
        
        $statusText = $delegate->status === 'active' ? 'تفعيل' : 'تعطيل';
        return redirect()->route('organization.delegates')->with('success', 'تم ' . $statusText . ' المندوب بنجاح!');
    }

    /**
     * Assign delegate to institution
     */
    public function assignDelegate($delegate)
    {
        $user = auth()->user();
        $delegateModel = User::where('role', 'representative')
            ->whereNull('institution_id')
            ->findOrFail($delegate);
        
        $delegateModel->update(['institution_id' => $user->id]);
        
        return redirect()->route('organization.delegates')
            ->with('success', 'تم تعيين المندوب بنجاح');
    }

    /**
     * Unassign delegate from institution
     */
    public function unassignDelegate($delegate)
    {
        $user = auth()->user();
        $delegateModel = User::where('role', 'representative')
            ->where('institution_id', $user->id)
            ->findOrFail($delegate);
        
        $delegateModel->update(['institution_id' => null]);
        
        return redirect()->route('organization.delegates')
            ->with('success', 'تم فك تعيين المندوب بنجاح');
    }

    public function assignDriverToTruck(Request $request, $truck)
    {
        $user = auth()->user();
        
        $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);
        
        $truckModel = Truck::where('institution_id', $user->id)
            ->findOrFail($truck);
        
        $driver = User::where('id', $request->driver_id)
            ->where('institution_id', $user->id)
            ->where('role', 'driver')
            ->first();
        
        if (!$driver) {
            return back()->withErrors(['driver_id' => 'السائق المحدد لا ينتمي لمؤسستك']);
        }
        
        // Unassign current driver from this truck if any
        if ($truckModel->driver_id) {
            $truckModel->update(['driver_id' => null]);
        }
        
        // Assign driver to truck
        $truckModel->update(['driver_id' => $driver->id]);
        
        return redirect()->route('organization.trucks')
            ->with('success', 'تم تعيين السائق للشاحنة بنجاح');
    }

    public function waterRequests()
    {
        $institutionId = auth()->user()->institution_id ?? auth()->id();
        $requests = \App\Models\WaterRequest::with(['representative', 'user', 'distributionPoint', 'truck', 'driver'])
            ->where(function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId)
                  ->orWhereNull('institution_id');
            })
            ->orderByDesc('created_at')
            ->get();
        $trucks = \App\Models\Truck::where('institution_id', $institutionId)
            ->where('status', 'active')
            ->whereNotNull('driver_id')
            ->get();
        return view('organization.water_requests', compact('requests', 'trucks'));
    }

    public function showWaterRequest($id)
    {
        $request = \App\Models\WaterRequest::with(['representative', 'user', 'distributionPoint', 'truck', 'driver'])
            ->findOrFail($id);
        return view('organization.water_request_details', compact('request'));
    }

    /**
     * تعيين شاحنة وسائق لطلب مياه
     */
    public function assignTruckToRequest(Request $request, $waterRequestId)
    {
        $request->validate([
            'truck_id' => 'required|exists:trucks,id',
        ]);

        $waterRequest = \App\Models\WaterRequest::findOrFail($waterRequestId);
        $truck = \App\Models\Truck::where('status', 'active')->findOrFail($request->truck_id);
        if (!$truck->driver_id) {
            return back()->withErrors(['truck_id' => 'الشاحنة المختارة ليس لديها سائق معين']);
        }
        $waterRequest->update([
            'truck_id' => $truck->id,
            'driver_id' => $truck->driver_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);
        return redirect()->route('organization.water-requests')->with('success', 'تم تعيين الشاحنة والسائق للطلب بنجاح');
    }
}
