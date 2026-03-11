<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_returns', function (Blueprint $table): void {
            $table->id();
            $table->string('entity_name');
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('designation')->nullable();
            $table->string('station')->nullable();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('return_date');
            $table->string('return_reason')->nullable();
            $table->string('control_no')->unique();
            $table->enum('document_type', ['PRS', 'RRSP']);
            $table->enum('status', ['draft', 'submitted', 'approved', 'issued', 'returned'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('property_return_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('property_return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('property_transaction_line_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_acquired')->nullable();
            $table->text('particulars');
            $table->string('property_no')->nullable();
            $table->unsignedInteger('quantity');
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('total_cost', 14, 2);
            $table->string('condition')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::table('disposals', function (Blueprint $table): void {
            if (! Schema::hasColumn('disposals', 'property_return_id')) {
                $table->foreignId('property_return_id')
                    ->nullable()
                    ->after('fund_cluster_id')
                    ->constrained('property_returns')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('disposals', function (Blueprint $table): void {
            if (Schema::hasColumn('disposals', 'property_return_id')) {
                $table->dropConstrainedForeignId('property_return_id');
            }
        });

        Schema::dropIfExists('property_return_lines');
        Schema::dropIfExists('property_returns');
    }
};
