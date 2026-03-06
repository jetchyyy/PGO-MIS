@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Administration</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Edit User Account</p>
            <p class="text-blue-200 text-[11px]">Update role, reset password, and manage account status.</p>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('users.index') }}" class="hover:text-[#1a2c5b]">User Accounts</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">{{ $user->name }}</span>
        </div>
    </div>

    <div class="w-full space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h1 class="text-sm font-bold uppercase tracking-widest text-gray-700">Account Details</h1>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5 px-6 py-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                           class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Role</label>
                    <select id="role" name="role" required
                            class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                        <option value="">Select role...</option>
                        @foreach($roleOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
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
                            <option value="{{ $employee->id }}" @selected((string) old('employee_id', $user->employee_id) === (string) $employee->id)>
                                {{ $employee->name }}{{ $employee->designation ? ' - '.$employee->designation : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-5">
                    <a href="{{ route('users.index') }}" class="rounded border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-600 hover:bg-gray-50">
                        Back
                    </a>
                    <button type="submit" class="rounded bg-[#1a2c5b] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#16306d]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <div class="mx-auto max-w-3xl rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Reset Password</h2>
            </div>

            <form method="POST" action="{{ route('users.reset-password', $user) }}" class="space-y-5 px-6 py-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-600">New Password</label>
                        <input id="password" name="password" type="password" required
                               class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest text-gray-600">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#1a2c5b] focus:ring-[#1a2c5b]">
                    </div>
                </div>

                <div class="flex items-center justify-end border-t border-gray-100 pt-5">
                    <button type="submit" class="rounded bg-blue-700 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-blue-800">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>

        <div class="mx-auto max-w-3xl rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Account Status</h2>
            </div>

            <div class="space-y-4 px-6 py-6">
                <p class="text-sm text-gray-600">
                    Current status:
                    @if($user->is_active)
                        <span class="font-semibold text-green-700">Active</span>
                    @else
                        <span class="font-semibold text-red-700">Disabled</span>
                    @endif
                </p>

                <form method="POST" action="{{ route('users.toggle-status', $user) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="rounded px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white {{ $user->is_active ? 'bg-red-700 hover:bg-red-800' : 'bg-green-700 hover:bg-green-800' }}">
                        {{ $user->is_active ? 'Disable Account' : 'Enable Account' }}
                    </button>
                </form>
            </div>
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
