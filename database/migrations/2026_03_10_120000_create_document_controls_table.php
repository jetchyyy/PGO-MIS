<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_controls', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('documentable');
            $table->string('template_name');
            $table->string('document_code');
            $table->string('document_title');
            $table->string('control_no')->unique();
            $table->date('generated_on');
            $table->timestamps();

            $table->unique(['documentable_type', 'documentable_id', 'template_name'], 'document_controls_unique_template');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_controls');
    }
};
