<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add item_id FK to property_transaction_lines
        Schema::table('property_transaction_lines', function (Blueprint $table): void {
            if (!Schema::hasColumn('property_transaction_lines', 'item_id')) {
                $table->foreignId('item_id')->nullable()->after('property_transaction_id')
                    ->constrained('items')->nullOnDelete();
            }
        });

        // Add item_id FK to transfer_lines
        Schema::table('transfer_lines', function (Blueprint $table): void {
            if (!Schema::hasColumn('transfer_lines', 'item_id')) {
                $table->foreignId('item_id')->nullable()->after('transfer_id')
                    ->constrained('items')->nullOnDelete();
            }
        });

        // Add item_id FK to disposal_lines
        Schema::table('disposal_lines', function (Blueprint $table): void {
            if (!Schema::hasColumn('disposal_lines', 'item_id')) {
                $table->foreignId('item_id')->nullable()->after('disposal_id')
                    ->constrained('items')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_transaction_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('property_transaction_lines', 'item_id')) {
                $table->dropConstrainedForeignId('item_id');
            }
        });

        Schema::table('transfer_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('transfer_lines', 'item_id')) {
                $table->dropConstrainedForeignId('item_id');
            }
        });

        Schema::table('disposal_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('disposal_lines', 'item_id')) {
                $table->dropConstrainedForeignId('item_id');
            }
        });
    }
};
