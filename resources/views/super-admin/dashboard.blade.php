@extends('super-admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Colleges</h6>
                        <h2 class="mb-0">{{ $stats['total_colleges'] }}</h2>
                    </div>
                    <i class="bi bi-building" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Active Colleges</h6>
                        <h2 class="mb-0">{{ $stats['active_colleges'] }}</h2>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Inactive Colleges</h6>
                        <h2 class="mb-0">{{ $stats['inactive_colleges'] }}</h2>
                    </div>
                    <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">College Admin Accounts</h6>
                        <h2 class="mb-0">{{ $stats['total_college_admins'] }}</h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('super-admin.colleges.create') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle"></i> Create College with Admin
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('super-admin.college-admins.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-people"></i> View College Admins
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
