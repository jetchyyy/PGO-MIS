<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disposals', function (Blueprint $table): void {
            if (!Schema::hasColumn('disposals', 'prerequisite_form_type')) {
                $table->string('prerequisite_form_type', 20)->nullable()->after('document_type');
            }

            if (!Schema::hasColumn('disposals', 'prerequisite_form_no')) {
                $table->string('prerequisite_form_no')->nullable()->after('prerequisite_form_type');
            }

            if (!Schema::hasColumn('disposals', 'prerequisite_form_date')) {
                $table->date('prerequisite_form_date')->nullable()->after('prerequisite_form_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('disposals', function (Blueprint $table): void {
            if (Schema::hasColumn('disposals', 'prerequisite_form_date')) {
                $table->dropColumn('prerequisite_form_date');
            }

            if (Schema::hasColumn('disposals', 'prerequisite_form_no')) {
                $table->dropColumn('prerequisite_form_no');
            }

            if (Schema::hasColumn('disposals', 'prerequisite_form_type')) {
                $table->dropColumn('prerequisite_form_type');
            }
        });
    }
};
