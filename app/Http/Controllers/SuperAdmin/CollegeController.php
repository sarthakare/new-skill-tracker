<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreCollegeWithAdminRequest;
use App\Http\Requests\SuperAdmin\UpdateCollegeRequest;
use App\Models\College;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CollegeController extends Controller
{
    /**
     * Display a listing of colleges.
     */
    public function index(): View
    {
        $colleges = College::latest()->paginate(15);

        return view('super-admin.colleges.index', compact('colleges'));
    }

    /**
     * Show the form for creating a new college.
     */
    public function create(): View
    {
        return view('super-admin.colleges.create');
    }

    /**
     * Store a newly created college with its admin in one step.
     */
    public function store(StoreCollegeWithAdminRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $plainPassword = $validated['admin_password'];

        $collegeAdmin = DB::transaction(function () use ($validated, $plainPassword) {
            $college = College::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'contact_email' => $validated['contact_email'],
                'status' => $validated['status'],
            ]);

            $collegeAdmin = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($plainPassword),
                'role' => 'COLLEGE_ADMIN',
                'college_id' => $college->id,
            ]);

            return $collegeAdmin;
        });

        return redirect()->route('super-admin.college-admins.show', $collegeAdmin)
            ->with('success', 'College and College Admin created successfully. Please save the credentials below.')
            ->with('generated_password', $plainPassword)
            ->with('college_admin_created', true);
    }

    /**
     * Display the specified college.
     */
    public function show(College $college): View
    {
        return view('super-admin.colleges.show', compact('college'));
    }

    /**
     * Show the form for editing the specified college.
     */
    public function edit(College $college): View
    {
        return view('super-admin.colleges.edit', compact('college'));
    }

    /**
     * Update the specified college.
     */
    public function update(UpdateCollegeRequest $request, College $college): RedirectResponse
    {
        $college->update($request->validated());

        return redirect()->route('super-admin.colleges.index')
            ->with('success', 'College updated successfully.');
    }

    /**
     * Remove the specified college.
     */
    public function destroy(College $college): RedirectResponse
    {
        $college->delete();

        return redirect()->route('super-admin.colleges.index')
            ->with('success', 'College deleted successfully.');
    }

    /**
     * Toggle college status (activate/deactivate).
     */
    public function toggleStatus(College $college): RedirectResponse
    {
        $college->update([
            'status' => $college->status === 'active' ? 'inactive' : 'active',
        ]);

        $status = $college->status === 'active' ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "College {$status} successfully.");
    }
}
