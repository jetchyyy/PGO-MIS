<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('inventory_items', 'model')) {
                $table->string('model')->nullable()->after('description');
            }
            if (! Schema::hasColumn('inventory_items', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('model');
            }
            if (! Schema::hasColumn('inventory_items', 'accountable_name')) {
                $table->string('accountable_name')->nullable()->after('current_employee_id');
            }
            if (! Schema::hasColumn('inventory_items', 'inventory_committee_name')) {
                $table->string('inventory_committee_name')->nullable()->after('accountable_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table): void {
            if (Schema::hasColumn('inventory_items', 'inventory_committee_name')) {
                $table->dropColumn('inventory_committee_name');
            }
            if (Schema::hasColumn('inventory_items', 'accountable_name')) {
                $table->dropColumn('accountable_name');
            }
            if (Schema::hasColumn('inventory_items', 'serial_number')) {
                $table->dropColumn('serial_number');
            }
            if (Schema::hasColumn('inventory_items', 'model')) {
                $table->dropColumn('model');
            }
        });
    }
};
