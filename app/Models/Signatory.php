<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    protected $fillable = [
        'role_key', 'name', 'designation', 'entity_name', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active signatory for a given role key.
     */
    public static function forRole(string $roleKey): ?self
    {
        return static::where('role_key', $roleKey)->where('is_active', true)->first();
    }

    /**
     * Get all active signatories keyed by role_key.
     */
    public static function activeMap(): array
    {
        return static::where('is_active', true)
            ->get()
            ->keyBy('role_key')
            ->toArray();
    }

    public const ROLE_PGSO_HEAD = 'pgso_head';
    public const ROLE_GOVERNOR = 'governor';
    public const ROLE_PROPERTY_INSPECTOR = 'property_inspector';
    public const ROLE_PROVINCIAL_ACCOUNTANT = 'provincial_accountant';
    public const ROLE_COA_REPRESENTATIVE = 'coa_representative';

    public const ROLES = [
        self::ROLE_PGSO_HEAD => 'PGSO Head / OIC',
        self::ROLE_GOVERNOR => 'Governor / Head of Agency',
        self::ROLE_PROPERTY_INSPECTOR => 'Property Inspector',
        self::ROLE_PROVINCIAL_ACCOUNTANT => 'Provincial Accountant',
        self::ROLE_COA_REPRESENTATIVE => 'COA Representative',
    ];
}
