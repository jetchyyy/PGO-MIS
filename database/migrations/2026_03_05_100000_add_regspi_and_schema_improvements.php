<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Registry of Semi-Expendable Property Issued (RegSPI) — COA Appendix 73
        Schema::create('regspi_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('semi_expendable_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_transaction_line_id')->constrained()->cascadeOnDelete();
            $table->string('ics_no');
            $table->string('description');
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('quantity_issued');
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('total_cost', 14, 2);
            $table->string('property_no')->nullable();
            $table->date('issue_date');
            $table->enum('classification', ['splv', 'sphv']);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // 2. Add estimated_useful_life to property_transaction_lines
        Schema::table('property_transaction_lines', function (Blueprint $table): void {
            if (!Schema::hasColumn('property_transaction_lines', 'estimated_useful_life')) {
                $table->string('estimated_useful_life')->nullable()->after('classification');
            }
        });

        // 3. Add UACS object code to property_cards and semi_expendable_cards
        Schema::table('property_cards', function (Blueprint $table): void {
            if (!Schema::hasColumn('property_cards', 'uacs_object_code')) {
                $table->string('uacs_object_code')->nullable()->after('description');
            }
        });

        Schema::table('semi_expendable_cards', function (Blueprint $table): void {
            if (!Schema::hasColumn('semi_expendable_cards', 'uacs_object_code')) {
                $table->string('uacs_object_code')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regspi_entries');

        Schema::table('property_transaction_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('property_transaction_lines', 'estimated_useful_life')) {
                $table->dropColumn('estimated_useful_life');
            }
        });

        Schema::table('property_cards', function (Blueprint $table): void {
            if (Schema::hasColumn('property_cards', 'uacs_object_code')) {
                $table->dropColumn('uacs_object_code');
            }
        });

        Schema::table('semi_expendable_cards', function (Blueprint $table): void {
            if (Schema::hasColumn('semi_expendable_cards', 'uacs_object_code')) {
                $table->dropColumn('uacs_object_code');
            }
        });
    }
};
