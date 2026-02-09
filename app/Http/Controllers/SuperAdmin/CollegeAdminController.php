<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class CollegeAdminController extends Controller
{
    /**
     * Display a listing of college admins.
     */
    public function index(): View
    {
        $collegeAdmins = User::where('role', 'COLLEGE_ADMIN')
            ->with('college')
            ->latest()
            ->paginate(15);

        return view('super-admin.college-admins.index', compact('collegeAdmins'));
    }

    /**
     * Display the specified college admin credentials.
     */
    public function show(User $collegeAdmin): View
    {
        $collegeAdmin->load('college');
        
        // Get the generated password from session if available
        $generatedPassword = session('generated_password');

        return view('super-admin.college-admins.show', compact('collegeAdmin', 'generatedPassword'));
    }
}
