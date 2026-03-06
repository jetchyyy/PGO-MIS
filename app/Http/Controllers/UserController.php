<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('employee')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name', 'designation']);
        $roleOptions = $this->assignableRoleOptions(request()->user());
        $employeeRequiredRoles = $this->employeeRequiredRoles();

        return view('users.create', compact('employees', 'roleOptions', 'employeeRequiredRoles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUserPayload($request);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'employee_id' => $validated['employee_id'] ?? null,
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'User account created successfully.');
    }

    public function edit(User $user): View
    {
        $this->abortIfProtectedUserTargeted(request()->user(), $user);

        $employees = Employee::orderBy('name')->get(['id', 'name', 'designation']);
        $roleOptions = $this->assignableRoleOptions(request()->user(), $user);
        $employeeRequiredRoles = $this->employeeRequiredRoles();

        return view('users.edit', compact('user', 'employees', 'roleOptions', 'employeeRequiredRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->abortIfProtectedUserTargeted($request->user(), $user);

        $validated = $this->validateUserPayload($request, $user);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'employee_id' => $validated['employee_id'] ?? null,
        ]);

        return redirect()
            ->route('users.edit', $user)
            ->with('status', 'User account details updated.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $this->abortIfProtectedUserTargeted($request->user(), $user);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('users.edit', $user)
            ->with('status', 'User password has been reset.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        $this->abortIfProtectedUserTargeted($request->user(), $user);

        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.edit', $user)
                ->withErrors(['is_active' => 'You cannot disable your own account.']);
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $message = $user->is_active ? 'User account enabled.' : 'User account disabled.';

        return redirect()
            ->route('users.edit', $user)
            ->with('status', $message);
    }

    private function validateUserPayload(Request $request, ?User $user = null): array
    {
        $roleOptions = array_keys($this->assignableRoleOptions($request->user(), $user));
        $employeeRequiredRoles = $this->employeeRequiredRoles();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => ['required', 'string', Rule::in($roleOptions)],
            'employee_id' => [
                Rule::requiredIf(fn () => in_array($request->string('role')->toString(), $employeeRequiredRoles, true)),
                'nullable',
                'integer',
                'exists:employees,id',
            ],
        ];

        if ($user === null) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        return $request->validate($rules);
    }

    private function employeeRequiredRoles(): array
    {
        return [
            User::ROLE_PROPERTY_STAFF,
            User::ROLE_ACCOUNTABLE_OFFICER,
            User::ROLE_APPROVING_OFFICIAL,
        ];
    }

    private function assignableRoleOptions(User $actor, ?User $target = null): array
    {
        $roles = User::roleOptions();

        if ($actor->hasRole(User::ROLE_SUPER_ADMIN)) {
            return $roles;
        }

        unset($roles[User::ROLE_SUPER_ADMIN], $roles[User::ROLE_SYSTEM_ADMIN]);

        if ($target !== null && isset(User::roleOptions()[$target->role])) {
            $roles[$target->role] = User::roleOptions()[$target->role];
        }

        return $roles;
    }

    private function abortIfProtectedUserTargeted(User $actor, User $target): void
    {
        if (!$actor->hasRole(User::ROLE_SUPER_ADMIN) && $target->hasRole(User::ROLE_SUPER_ADMIN, User::ROLE_SYSTEM_ADMIN)) {
            abort(403, 'Only super admin can manage system and super admin accounts.');
        }
    }
}
