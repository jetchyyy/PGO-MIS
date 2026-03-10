@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Administration</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">User Accounts</p>
            <p class="text-blue-200 text-[11px]">Create and manage system access by role.</p>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">User Accounts</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600">Total users: <strong>{{ $users->count() }}</strong></p>
            <a href="{{ route('users.create') }}" class="inline-flex w-full items-center justify-center rounded bg-[#1a2c5b] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#16306d] sm:w-auto">
                Create Account
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">Role</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">Employee</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ \App\Models\User::roleOptions()[$user->role] ?? $user->role }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user->employee?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($user->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Disabled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('users.edit', $user) }}" class="inline-flex rounded border border-gray-300 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-gray-600 hover:bg-gray-50">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No user accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
