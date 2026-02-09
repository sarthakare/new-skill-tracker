<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - College Event Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 15px;
        }
        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .role-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            background: white;
        }
        .role-option:hover {
            border-color: #0d6efd;
            background: #f0f7ff;
        }
        .role-option.active {
            border-color: #0d6efd;
            background: #e7f1ff;
        }
        .role-option input[type="radio"] {
            display: none;
        }
        .role-option i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                            <h2 class="mt-3 mb-1">College Event Platform</h2>
                            <p class="text-muted">Login to continue</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('college.login.post') }}" id="loginForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Login As <span class="text-danger">*</span></label>
                                <div class="role-selector">
                                    <label class="role-option {{ old('role', $defaultRole ?? 'college') === 'college' ? 'active' : '' }}" id="collegeOption">
                                        <input type="radio" name="role" value="college" {{ old('role', $defaultRole ?? 'college') === 'college' ? 'checked' : '' }} required>
                                        <i class="bi bi-building text-success"></i>
                                        <strong>College Admin</strong>
                                    </label>
                                    <label class="role-option {{ old('role', $defaultRole ?? 'college') === 'super' ? 'active' : '' }}" id="superOption">
                                        <input type="radio" name="role" value="super" {{ old('role', $defaultRole ?? 'college') === 'super' ? 'checked' : '' }} required>
                                        <i class="bi bi-shield-check text-primary"></i>
                                        <strong>Super Admin</strong>
                                    </label>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update form action based on selected role
        const roleInputs = document.querySelectorAll('input[name="role"]');
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        roleInputs.forEach(input => {
            input.addEventListener('change', function() {
                const selectedRole = this.value;
                const actionUrl = selectedRole === 'super' 
                    ? '{{ route("super-admin.login.post") }}' 
                    : '{{ route("college.login.post") }}';
                
                loginForm.action = actionUrl;
                
                // Update button color based on role
                if (selectedRole === 'super') {
                    submitBtn.className = 'btn btn-primary w-100 py-2';
                } else {
                    submitBtn.className = 'btn btn-success w-100 py-2';
                }
                
                // Update active state
                document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
                if (selectedRole === 'super') {
                    document.getElementById('superOption').classList.add('active');
                } else {
                    document.getElementById('collegeOption').classList.add('active');
                }
            });
        });
        
        // Set initial form action
        const initialRole = document.querySelector('input[name="role"]:checked').value;
        if (initialRole === 'super') {
            loginForm.action = '{{ route("super-admin.login.post") }}';
            submitBtn.className = 'btn btn-primary w-100 py-2';
        } else {
            loginForm.action = '{{ route("college.login.post") }}';
            submitBtn.className = 'btn btn-success w-100 py-2';
        }
        
        // Update active state on page load
        if (initialRole === 'super') {
            document.getElementById('superOption').classList.add('active');
        } else {
            document.getElementById('collegeOption').classList.add('active');
        }
    </script>
</body>
</html>
