@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Administration</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Create User Account</p>
            <p class="text-blue-200 text-[11px]">Assign role-based access for a new user.</p>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('users.index') }}" class="hover:text-[#1a2c5b]">User Accounts</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Create</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h1 class="text-sm font-bold uppercase tracking-widest text-gray-700">New Account Details</h1>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-5 px-6 py-6">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Role</label>
                    <select id="role" name="role" required
                            class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                        <option value="">Select role...</option>
                        @foreach($roleOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="employee_id" class="block text-xs font-bold uppercase tracking-widest text-gray-600">
                        Employee Record
                        <span class="text-gray-400 font-medium">(required for property staff, accountable officer, approving official)</span>
                    </label>
                    <select id="employee_id" name="employee_id"
                            class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                        <option value="">None</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected((string) old('employee_id') === (string) $employee->id)>
                                {{ $employee->name }}{{ $employee->designation ? ' - '.$employee->designation : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Password</label>
                        <input id="password" name="password" type="password" required
                               class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-5">
                    <a href="{{ route('users.index') }}" class="rounded border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-600 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded bg-[#1a2c5b] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#16306d]">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (() => {
        const roleEl = document.getElementById('role');
        const employeeEl = document.getElementById('employee_id');
        const requiredRoles = @json($employeeRequiredRoles);

        const updateRequirement = () => {
            employeeEl.required = requiredRoles.includes(roleEl.value);
        };

        roleEl.addEventListener('change', updateRequirement);
        updateRequirement();
    })();
</script>
@endsection
