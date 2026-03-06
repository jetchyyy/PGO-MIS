<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_SYSTEM_ADMIN = 'system_admin';
    public const ROLE_PROPERTY_STAFF = 'property_staff';
    public const ROLE_ACCOUNTABLE_OFFICER = 'accountable_officer';
    public const ROLE_APPROVING_OFFICIAL = 'approving_official';
    public const ROLE_AUDIT_VIEWER = 'audit_viewer';
    public const ROLE_LABELS = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_SYSTEM_ADMIN => 'System Admin',
        self::ROLE_PROPERTY_STAFF => 'Property Staff',
        self::ROLE_ACCOUNTABLE_OFFICER => 'Accountable Officer',
        self::ROLE_APPROVING_OFFICIAL => 'Approving Official',
        self::ROLE_AUDIT_VIEWER => 'Audit Viewer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public static function roleOptions(): array
    {
        return self::ROLE_LABELS;
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'acted_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
