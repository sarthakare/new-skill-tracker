<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the Super Admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_colleges' => College::count(),
            'active_colleges' => College::where('status', 'active')->count(),
            'inactive_colleges' => College::where('status', 'inactive')->count(),
            'total_college_admins' => User::where('role', 'COLLEGE_ADMIN')->count(),
        ];

        return view('super-admin.dashboard', compact('stats'));
    }
}
