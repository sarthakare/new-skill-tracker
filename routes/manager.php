<?php

use App\Http\Controllers\Manager\ProgramAttendanceController;
use App\Http\Controllers\Manager\ProgramCompletionController;
use App\Http\Controllers\Manager\ProgramDashboardController;
use App\Http\Controllers\Manager\ProgramSessionController;
use App\Http\Controllers\Manager\ProgramStudentController;
use App\Http\Controllers\Manager\SyllabusAssignmentController;
use App\Http\Controllers\Manager\SyllabusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Program Manager Routes
|--------------------------------------------------------------------------
|
| Routes for program managers to access assigned programs using credentials.
|
*/

Route::prefix('manager/program')->name('manager.program.')->middleware(['web', 'program-manager-access'])->group(function () {
    Route::get('/{program}/dashboard', [ProgramDashboardController::class, 'index'])->name('dashboard');

    Route::get('/{program}/students', [ProgramStudentController::class, 'index'])->name('students.index');
    Route::post('/{program}/students', [ProgramStudentController::class, 'store'])->name('students.store');
    Route::get('/{program}/students/{student}/edit', [ProgramStudentController::class, 'edit'])->name('students.edit');
    Route::put('/{program}/students/{student}', [ProgramStudentController::class, 'update'])->name('students.update');
    Route::delete('/{program}/students/{student}', [ProgramStudentController::class, 'destroy'])->name('students.destroy');

    // Program remarks (bulk: one page to update all students' remarks)
    Route::get('/{program}/remarks', [ProgramStudentController::class, 'remarks'])->name('remarks.index');
    Route::post('/{program}/remarks', [ProgramStudentController::class, 'updateRemarks'])->name('remarks.update');

    Route::get('/{program}/sessions', [ProgramSessionController::class, 'index'])->name('sessions.index');
    Route::post('/{program}/sessions', [ProgramSessionController::class, 'store'])->name('sessions.store');

    Route::get('/{program}/sessions/{session}/attendance', [ProgramAttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('/{program}/sessions/{session}/attendance', [ProgramAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/{program}/sessions/{session}/attendance-report', [ProgramAttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/{program}/sessions/{session}/daily-report', [ProgramAttendanceController::class, 'dailyReport'])->name('daily.report');
    Route::get('/{program}/completion-report', [ProgramAttendanceController::class, 'completionReport'])->name('completion.report');

    Route::get('/{program}/syllabus', [SyllabusController::class, 'index'])->name('syllabus.index');
    Route::post('/{program}/syllabus/topics', [SyllabusController::class, 'storeTopic'])->name('syllabus.topics.store');
    Route::put('/{program}/syllabus/topics/{topic}', [SyllabusController::class, 'updateTopic'])->name('syllabus.topics.update');
    Route::delete('/{program}/syllabus/topics/{topic}', [SyllabusController::class, 'destroyTopic'])->name('syllabus.topics.destroy');
    Route::post('/{program}/syllabus/topics/{topic}/subtopics', [SyllabusController::class, 'storeSubtopic'])->name('syllabus.subtopics.store');
    Route::post('/{program}/syllabus/topics/{topic}/toggle-complete', [SyllabusController::class, 'toggleTopicComplete'])->name('syllabus.topics.toggle-complete');
    Route::post('/{program}/syllabus/topics/{topic}/schedule', [SyllabusController::class, 'updateTopicSchedule'])->name('syllabus.topics.schedule');
    Route::put('/{program}/syllabus/subtopics/{subtopic}', [SyllabusController::class, 'updateSubtopic'])->name('syllabus.subtopics.update');
    Route::delete('/{program}/syllabus/subtopics/{subtopic}', [SyllabusController::class, 'destroySubtopic'])->name('syllabus.subtopics.destroy');
    Route::post('/{program}/syllabus/subtopics/{subtopic}/toggle-complete', [SyllabusController::class, 'toggleSubtopicComplete'])->name('syllabus.subtopics.toggle-complete');
    Route::post('/{program}/syllabus/subtopics/{subtopic}/schedule', [SyllabusController::class, 'updateSubtopicSchedule'])->name('syllabus.subtopics.schedule');

    Route::get('/{program}/syllabus/subtopics/{subtopic}/assignments/create', [SyllabusAssignmentController::class, 'create'])->name('syllabus.assignments.create');
    Route::post('/{program}/syllabus/subtopics/{subtopic}/assignments', [SyllabusAssignmentController::class, 'store'])->name('syllabus.assignments.store');
    Route::get('/{program}/syllabus/assignments/{assignment}/edit', [SyllabusAssignmentController::class, 'edit'])->name('syllabus.assignments.edit');
    Route::put('/{program}/syllabus/assignments/{assignment}', [SyllabusAssignmentController::class, 'update'])->name('syllabus.assignments.update');
    Route::delete('/{program}/syllabus/assignments/{assignment}', [SyllabusAssignmentController::class, 'destroy'])->name('syllabus.assignments.destroy');

    Route::get('/{program}/completion', [ProgramCompletionController::class, 'create'])->name('completion.create');
    Route::post('/{program}/completion', [ProgramCompletionController::class, 'store'])->name('completion.store');
});
