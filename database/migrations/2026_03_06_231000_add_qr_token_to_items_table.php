<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table): void {
            if (! Schema::hasColumn('items', 'qr_token')) {
                $table->uuid('qr_token')->nullable()->unique()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table): void {
            if (Schema::hasColumn('items', 'qr_token')) {
                $table->dropUnique(['qr_token']);
                $table->dropColumn('qr_token');
            }
        });
    }
};
