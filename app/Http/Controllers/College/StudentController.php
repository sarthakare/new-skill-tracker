<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * List student accounts registered for this college.
     */
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;

        $students = User::query()
            ->where('college_id', $collegeId)
            ->where('role', 'STUDENT')
            ->with('department')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('college.students.index', compact('students'));
    }
}
