<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('accountable_officer')->after('password');
            }
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->foreignId('employee_id')->nullable()->after('role');
            }
        });

        Schema::create('offices', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('fund_clusters', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('station')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
        });

        Schema::create('property_transactions', function (Blueprint $table): void {
            $table->id();
            $table->string('entity_name');
            $table->foreignId('office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('transaction_date');
            $table->string('reference_no')->nullable();
            $table->string('control_no')->unique();
            $table->enum('document_type', ['PAR', 'ICS-SPLV', 'ICS-SPHV']);
            $table->enum('asset_type', ['ppe', 'semi_expendable']);
            $table->enum('status', ['draft', 'submitted', 'approved', 'issued', 'returned'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('property_transaction_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('property_transaction_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('unit');
            $table->text('description');
            $table->string('property_no')->nullable();
            $table->date('date_acquired')->nullable();
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('total_cost', 14, 2);
            $table->enum('classification', ['ppe', 'splv', 'sphv']);
            $table->string('remarks')->nullable();
            $table->enum('item_status', ['active', 'transferred', 'disposed', 'returned'])->default('active');
            $table->timestamps();
        });

        Schema::create('property_cards', function (Blueprint $table): void {
            $table->id();
            $table->string('card_no')->unique();
            $table->string('property_no')->nullable();
            $table->text('description');
            $table->foreignId('office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('balance_qty')->default(0);
            $table->decimal('balance_amount', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('property_card_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('property_card_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('source');
            $table->date('entry_date');
            $table->string('reference_no');
            $table->unsignedInteger('qty_in')->default(0);
            $table->unsignedInteger('qty_out')->default(0);
            $table->unsignedInteger('running_balance_qty');
            $table->decimal('amount_in', 14, 2)->default(0);
            $table->decimal('amount_out', 14, 2)->default(0);
            $table->decimal('running_balance_amount', 14, 2);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('semi_expendable_cards', function (Blueprint $table): void {
            $table->id();
            $table->string('card_no')->unique();
            $table->string('property_no')->nullable();
            $table->text('description');
            $table->foreignId('office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('balance_qty')->default(0);
            $table->decimal('balance_amount', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('semi_expendable_card_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('semi_expendable_card_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('source');
            $table->date('entry_date');
            $table->string('reference_no');
            $table->unsignedInteger('qty_in')->default(0);
            $table->unsignedInteger('qty_out')->default(0);
            $table->unsignedInteger('running_balance_qty');
            $table->decimal('amount_in', 14, 2)->default(0);
            $table->decimal('amount_out', 14, 2)->default(0);
            $table->decimal('running_balance_amount', 14, 2);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('accountability_headers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('reference_no');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamps();
        });

        Schema::create('accountability_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('accountability_header_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('source_line');
            $table->unsignedInteger('quantity');
            $table->string('unit');
            $table->text('description');
            $table->string('property_no')->nullable();
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('amount', 14, 2);
            $table->enum('status', ['active', 'transferred', 'disposed', 'returned'])->default('active');
            $table->timestamps();
        });

        Schema::create('transfers', function (Blueprint $table): void {
            $table->id();
            $table->string('entity_name');
            $table->foreignId('from_employee_id')->constrained('employees')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('to_employee_id')->constrained('employees')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('transfer_type', ['donation', 'reassignment_recall', 'relocate', 'retirement_resignation', 'others']);
            $table->string('transfer_type_other')->nullable();
            $table->date('transfer_date');
            $table->enum('document_type', ['PTR', 'ITR'])->default('PTR');
            $table->string('control_no')->unique();
            $table->enum('status', ['draft', 'submitted', 'approved', 'issued', 'returned'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('transfer_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('transfer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_transaction_line_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_acquired')->nullable();
            $table->string('reference_no');
            $table->unsignedInteger('quantity');
            $table->string('unit');
            $table->text('description');
            $table->decimal('amount', 14, 2);
            $table->string('condition')->default('Functional');
            $table->timestamps();
        });

        Schema::create('disposals', function (Blueprint $table): void {
            $table->id();
            $table->string('entity_name');
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('designation')->nullable();
            $table->string('station')->nullable();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('disposal_date');
            $table->enum('disposal_type', ['sale', 'transfer', 'destruction', 'others']);
            $table->string('disposal_type_other')->nullable();
            $table->string('or_no')->nullable();
            $table->decimal('sale_amount', 14, 2)->nullable();
            $table->decimal('appraised_value', 14, 2)->nullable();
            $table->enum('document_type', ['IIRUP', 'RRSEP'])->default('IIRUP');
            $table->string('control_no')->unique();
            $table->enum('status', ['draft', 'submitted', 'approved', 'issued', 'returned'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('disposal_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('disposal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_transaction_line_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_acquired')->nullable();
            $table->text('particulars');
            $table->string('property_no')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('total_cost', 14, 2);
            $table->decimal('accumulated_depreciation', 14, 2)->default(0);
            $table->decimal('carrying_amount', 14, 2);
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('attachable');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('approvable');
            $table->enum('status', ['pending', 'approved', 'returned'])->default('pending');
            $table->foreignId('acted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event');
            $table->nullableMorphs('subject');
            $table->json('context')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('print_logs', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('printable');
            $table->string('template_name');
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('printed_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('printed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('disposal_lines');
        Schema::dropIfExists('disposals');
        Schema::dropIfExists('transfer_lines');
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('accountability_lines');
        Schema::dropIfExists('accountability_headers');
        Schema::dropIfExists('semi_expendable_card_entries');
        Schema::dropIfExists('semi_expendable_cards');
        Schema::dropIfExists('property_card_entries');
        Schema::dropIfExists('property_cards');
        Schema::dropIfExists('property_transaction_lines');
        Schema::dropIfExists('property_transactions');
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'employee_id')) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
        Schema::dropIfExists('employees');
        Schema::dropIfExists('fund_clusters');
        Schema::dropIfExists('offices');
    }
};
