<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->foreignId('property_transaction_line_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('office_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fund_cluster_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('current_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('inventory_code')->unique();
            $table->uuid('qr_token')->unique();
            $table->string('description');
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->string('classification', 20)->nullable();
            $table->string('property_no')->nullable();
            $table->date('date_acquired')->nullable();
            $table->enum('status', ['in_stock', 'issued', 'disposed'])->default('in_stock');
            $table->date('issued_at')->nullable();
            $table->date('disposed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->enum('movement_type', ['received', 'issued', 'transferred', 'disposed', 'adjusted']);
            $table->nullableMorphs('reference');
            $table->foreignId('from_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('to_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('acted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('movement_date');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('inventory_items');
    }
};
