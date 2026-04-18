<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student sign up - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans antialiased">
    <div class="hidden md:flex md:w-1/2 bg-primary flex-col justify-center px-12 lg:px-20 py-16">
        <div class="max-w-md">
            <div class="mb-6 rounded-xl bg-white/95 p-3 shadow-sm inline-block max-w-full">
                @include('partials.host-institution-logo', ['class' => 'h-11 sm:h-12 w-auto max-w-full object-contain object-left'])
            </div>
            <div class="flex items-center gap-3 text-white/90 mb-6">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </span>
                <span class="text-xl font-semibold tracking-tight">Skill Tracker</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">Create your student account</h1>
            <p class="mt-4 text-white/80 text-lg">Choose your college and department, then complete your details.</p>
        </div>
    </div>
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10 md:p-12">
        <div class="w-full max-w-md">
            <div class="md:hidden mb-6 rounded-xl bg-white p-3 shadow-sm border border-slate-200/80 inline-block max-w-full">
                @include('partials.host-institution-logo', ['class' => 'h-10 w-auto max-w-full object-contain object-left'])
            </div>
            <div class="bg-white rounded-card shadow-card border border-slate-200/80 p-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Register</h2>
                    <p class="text-slate-500 mt-1"><span class="text-red-500">*</span> Required fields</p>
                </div>
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('student.register.post') }}" class="space-y-4" id="student-register-form">
                    @csrf
                    <div>
                        <label for="college_id" class="block text-sm font-medium text-slate-700 mb-1">College <span class="text-red-500" aria-hidden="true">*</span></label>
                        <select id="college_id" name="college_id" required
                                class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('college_id') border-red-500 @enderror">
                            <option value="" disabled {{ old('college_id') ? '' : 'selected' }}>Select your college</option>
                            @foreach ($colleges as $college)
                                <option value="{{ $college->id }}" {{ (string) old('college_id') === (string) $college->id ? 'selected' : '' }}>
                                    {{ $college->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('college_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500" aria-hidden="true">*</span></label>
                        <select id="department_id" name="department_id"
                                class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('department_id') border-red-500 @enderror">
                            <option value="">Select college first</option>
                        </select>
                        <p id="department-help" class="mt-1 text-xs text-slate-500 hidden">Your college admin adds available departments.</p>
                        @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full name <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="roll_number" class="block text-sm font-medium text-slate-700 mb-1">Roll number <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input type="text" id="roll_number" name="roll_number" value="{{ old('roll_number') }}" required autocomplete="off"
                               placeholder="As on your university ID"
                               class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('roll_number') border-red-500 @enderror">
                        @error('roll_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                    </div>
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-slate-700 mb-1">Mobile number <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" inputmode="tel"
                               placeholder="Optional — 10-digit or with country code"
                               class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror">
                        @error('mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500" aria-hidden="true">*</span></label>
                        <x-password-input id="password" name="password" required autocomplete="new-password" class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm password <span class="text-red-500" aria-hidden="true">*</span></label>
                        <x-password-input id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                    </div>
                    <button type="submit" class="w-full mt-2 py-3 px-4 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Create account
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-600">
                    Already have an account?
                    <a href="{{ route('student.login') }}" class="text-primary font-medium hover:underline">Sign in</a>
                </p>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var collegeSelect = document.getElementById('college_id');
            var departmentSelect = document.getElementById('department_id');
            var departmentHelp = document.getElementById('department-help');
            var departmentsUrl = @json(route('student.register.departments'));
            var oldCollegeId = @json(old('college_id'));
            var oldDepartmentId = @json(old('department_id'));

            function escapeHtml(text) {
                var div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function setDepartmentRequired(enabled) {
                if (enabled) {
                    departmentSelect.setAttribute('required', 'required');
                } else {
                    departmentSelect.removeAttribute('required');
                }
            }

            async function loadDepartments(collegeId, preselectDepartmentId) {
                departmentSelect.innerHTML = '<option value="">Loading…</option>';
                departmentSelect.disabled = true;
                setDepartmentRequired(false);
                departmentHelp.classList.add('hidden');
                if (!collegeId) {
                    departmentSelect.innerHTML = '<option value="">Select college first</option>';
                    return;
                }
                try {
                    var res = await fetch(departmentsUrl + '?college_id=' + encodeURIComponent(collegeId), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) throw new Error('load failed');
                    var list = await res.json();
                    if (!list.length) {
                        departmentSelect.innerHTML = '<option value="">No departments available</option>';
                        departmentSelect.disabled = true;
                        departmentHelp.classList.remove('hidden');
                        return;
                    }
                    var opts = '<option value="">Select department</option>';
                    list.forEach(function (d) {
                        var sel = preselectDepartmentId && String(d.id) === String(preselectDepartmentId) ? ' selected' : '';
                        opts += '<option value="' + d.id + '"' + sel + '>' + escapeHtml(d.name) + '</option>';
                    });
                    departmentSelect.innerHTML = opts;
                    departmentSelect.disabled = false;
                    setDepartmentRequired(true);
                } catch (e) {
                    departmentSelect.innerHTML = '<option value="">Could not load departments</option>';
                    departmentSelect.disabled = true;
                }
            }

            collegeSelect.addEventListener('change', function () {
                loadDepartments(this.value, null);
            });

            if (oldCollegeId) {
                collegeSelect.value = oldCollegeId;
                loadDepartments(oldCollegeId, oldDepartmentId);
            } else {
                departmentSelect.disabled = true;
                setDepartmentRequired(false);
            }
        })();
    </script>
</body>
</html>
