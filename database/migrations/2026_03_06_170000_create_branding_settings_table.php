<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branding_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('app_name')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('nav_title')->nullable();
            $table->string('nav_subtitle')->nullable();
            $table->string('welcome_badge')->nullable();
            $table->string('welcome_title')->nullable();
            $table->string('welcome_subtitle')->nullable();
            $table->text('welcome_description')->nullable();
            $table->string('login_heading')->nullable();
            $table->string('login_subheading')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('footer_subtext')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('welcome_bg_path')->nullable();
            $table->string('login_bg_path')->nullable();
            $table->string('og_image_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branding_settings');
    }
};
