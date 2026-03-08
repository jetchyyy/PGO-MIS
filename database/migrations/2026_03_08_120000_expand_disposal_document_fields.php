<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disposals', function (Blueprint $table): void {
            if (! Schema::hasColumn('disposals', 'item_disposal_condition')) {
                $table->enum('item_disposal_condition', ['unserviceable', 'no_longer_needed', 'obsolete', 'others'])
                    ->nullable()
                    ->after('disposal_type_other');
            }
            if (! Schema::hasColumn('disposals', 'item_disposal_condition_other')) {
                $table->string('item_disposal_condition_other')->nullable()->after('item_disposal_condition');
            }
            if (! Schema::hasColumn('disposals', 'disposal_method')) {
                $table->enum('disposal_method', ['public_auction', 'destruction', 'throwing', 'others'])
                    ->nullable()
                    ->after('appraised_value');
            }
            if (! Schema::hasColumn('disposals', 'disposal_method_other')) {
                $table->string('disposal_method_other')->nullable()->after('disposal_method');
            }
        });

        Schema::table('disposal_lines', function (Blueprint $table): void {
            if (! Schema::hasColumn('disposal_lines', 'unit')) {
                $table->string('unit')->nullable()->after('quantity');
            }
            if (! Schema::hasColumn('disposal_lines', 'appraised_value')) {
                $table->decimal('appraised_value', 14, 2)->nullable()->after('total_cost');
            }
            if (! Schema::hasColumn('disposal_lines', 'remarks')) {
                $table->string('remarks')->nullable()->after('carrying_amount');
            }
        });

        DB::table('disposals')
            ->whereNull('item_disposal_condition')
            ->update([
                'item_disposal_condition' => DB::raw("
                    CASE
                        WHEN disposal_type IN ('transfer', 'others') THEN 'others'
                        ELSE 'unserviceable'
                    END
                "),
                'item_disposal_condition_other' => DB::raw("
                    CASE
                        WHEN disposal_type = 'transfer' THEN 'Transfer'
                        WHEN disposal_type = 'others' THEN COALESCE(disposal_type_other, 'Others')
                        ELSE NULL
                    END
                "),
                'disposal_method' => DB::raw("
                    CASE
                        WHEN disposal_type = 'sale' THEN 'public_auction'
                        WHEN disposal_type = 'destruction' THEN 'destruction'
                        ELSE 'others'
                    END
                "),
                'disposal_method_other' => DB::raw("
                    CASE
                        WHEN disposal_type = 'transfer' THEN 'Transfer'
                        WHEN disposal_type = 'others' THEN COALESCE(disposal_type_other, 'Others')
                        ELSE NULL
                    END
                "),
            ]);

        DB::table('disposal_lines')
            ->whereNull('unit')
            ->update(['unit' => 'pc']);

        DB::table('disposal_lines')
            ->whereNull('appraised_value')
            ->update(['appraised_value' => DB::raw('carrying_amount')]);
    }

    public function down(): void
    {
        Schema::table('disposal_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('disposal_lines', 'remarks')) {
                $table->dropColumn('remarks');
            }
            if (Schema::hasColumn('disposal_lines', 'appraised_value')) {
                $table->dropColumn('appraised_value');
            }
            if (Schema::hasColumn('disposal_lines', 'unit')) {
                $table->dropColumn('unit');
            }
        });

        Schema::table('disposals', function (Blueprint $table): void {
            if (Schema::hasColumn('disposals', 'disposal_method_other')) {
                $table->dropColumn('disposal_method_other');
            }
            if (Schema::hasColumn('disposals', 'disposal_method')) {
                $table->dropColumn('disposal_method');
            }
            if (Schema::hasColumn('disposals', 'item_disposal_condition_other')) {
                $table->dropColumn('item_disposal_condition_other');
            }
            if (Schema::hasColumn('disposals', 'item_disposal_condition')) {
                $table->dropColumn('item_disposal_condition');
            }
        });
    }
};
