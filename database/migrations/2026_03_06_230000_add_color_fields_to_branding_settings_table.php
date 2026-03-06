<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branding_settings', function (Blueprint $table): void {
            $table->string('primary_color', 7)->nullable()->after('favicon_path');
            $table->string('secondary_color', 7)->nullable()->after('primary_color');
            $table->string('accent_color', 7)->nullable()->after('secondary_color');
            $table->string('button_color', 7)->nullable()->after('accent_color');
            $table->string('button_text_color', 7)->nullable()->after('button_color');
        });
    }

    public function down(): void
    {
        Schema::table('branding_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'primary_color',
                'secondary_color',
                'accent_color',
                'button_color',
                'button_text_color',
            ]);
        });
    }
};
