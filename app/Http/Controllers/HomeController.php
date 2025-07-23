<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        if ($user) {
            switch ($user->role) {
                case 'org_owner':
                    return redirect()->route('organization.dashboard');
                case 'representative':
                    return redirect()->route('delegate.dashboard');
                case 'driver':
                    return redirect()->route('driver.dashboard');
            }
        }
        return view('home');
    }
}
