<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_transaction_lines', function (Blueprint $table): void {
            if (! Schema::hasColumn('property_transaction_lines', 'inventory_item_id')) {
                $table->foreignId('inventory_item_id')
                    ->nullable()
                    ->after('item_id')
                    ->constrained('inventory_items')
                    ->nullOnDelete();
            }
        });

        Schema::table('transfer_lines', function (Blueprint $table): void {
            if (! Schema::hasColumn('transfer_lines', 'inventory_item_id')) {
                $table->foreignId('inventory_item_id')
                    ->nullable()
                    ->after('item_id')
                    ->constrained('inventory_items')
                    ->nullOnDelete();
            }
        });

        Schema::table('disposal_lines', function (Blueprint $table): void {
            if (! Schema::hasColumn('disposal_lines', 'inventory_item_id')) {
                $table->foreignId('inventory_item_id')
                    ->nullable()
                    ->after('item_id')
                    ->constrained('inventory_items')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_transaction_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('property_transaction_lines', 'inventory_item_id')) {
                $table->dropConstrainedForeignId('inventory_item_id');
            }
        });

        Schema::table('transfer_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('transfer_lines', 'inventory_item_id')) {
                $table->dropConstrainedForeignId('inventory_item_id');
            }
        });

        Schema::table('disposal_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('disposal_lines', 'inventory_item_id')) {
                $table->dropConstrainedForeignId('inventory_item_id');
            }
        });
    }
};
