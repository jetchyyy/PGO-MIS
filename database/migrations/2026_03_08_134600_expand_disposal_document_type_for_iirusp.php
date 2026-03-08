<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        match ($driver) {
            'sqlite' => $this->updateSqliteTable(),
            'mysql', 'mariadb' => $this->updateMySqlEnum(['IIRUP', 'IIRUSP', 'RRSEP']),
            default => null,
        };
    }

    public function down(): void
    {
        DB::table('disposals')
            ->where('document_type', 'IIRUSP')
            ->update(['document_type' => 'IIRUP']);

        $driver = Schema::getConnection()->getDriverName();

        match ($driver) {
            'sqlite' => $this->updateSqliteTable(includeIirusp: false),
            'mysql', 'mariadb' => $this->updateMySqlEnum(['IIRUP', 'RRSEP']),
            default => null,
        };
    }

    private function updateMySqlEnum(array $values): void
    {
        $enumList = implode("', '", $values);

        DB::statement(
            "ALTER TABLE disposals MODIFY document_type ENUM('{$enumList}') NOT NULL DEFAULT 'IIRUP'"
        );
    }

    private function updateSqliteTable(bool $includeIirusp = true): void
    {
        $documentTypes = $includeIirusp ? ['IIRUP', 'IIRUSP', 'RRSEP'] : ['IIRUP', 'RRSEP'];
        $documentTypeDefault = 'IIRUP';

        Schema::disableForeignKeyConstraints();

        Schema::create('disposals_tmp', function (Blueprint $table) use ($documentTypes, $documentTypeDefault): void {
            $table->id();
            $table->string('entity_name');
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('designation')->nullable();
            $table->string('station')->nullable();
            $table->foreignId('fund_cluster_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('disposal_date');
            $table->enum('disposal_type', ['sale', 'transfer', 'destruction', 'others']);
            $table->string('disposal_type_other')->nullable();
            $table->enum('item_disposal_condition', ['unserviceable', 'no_longer_needed', 'obsolete', 'others'])
                ->default('unserviceable');
            $table->string('item_disposal_condition_other')->nullable();
            $table->string('or_no')->nullable();
            $table->decimal('sale_amount', 14, 2)->nullable();
            $table->decimal('appraised_value', 14, 2)->nullable();
            $table->enum('disposal_method', ['public_auction', 'destruction', 'throwing', 'others'])
                ->default('public_auction');
            $table->string('disposal_method_other')->nullable();
            $table->enum('document_type', $documentTypes)->default($documentTypeDefault);
            $table->string('control_no')->unique();
            $table->enum('status', ['draft', 'submitted', 'approved', 'issued', 'returned'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        DB::table('disposals_tmp')->insertUsing(
            [
                'id',
                'entity_name',
                'employee_id',
                'designation',
                'station',
                'fund_cluster_id',
                'disposal_date',
                'disposal_type',
                'disposal_type_other',
                'item_disposal_condition',
                'item_disposal_condition_other',
                'or_no',
                'sale_amount',
                'appraised_value',
                'disposal_method',
                'disposal_method_other',
                'document_type',
                'control_no',
                'status',
                'created_by',
                'submitted_at',
                'approved_at',
                'created_at',
                'updated_at',
            ],
            DB::table('disposals')->select(
                'id',
                'entity_name',
                'employee_id',
                'designation',
                'station',
                'fund_cluster_id',
                'disposal_date',
                'disposal_type',
                'disposal_type_other',
                'item_disposal_condition',
                'item_disposal_condition_other',
                'or_no',
                'sale_amount',
                'appraised_value',
                'disposal_method',
                'disposal_method_other',
                'document_type',
                'control_no',
                'status',
                'created_by',
                'submitted_at',
                'approved_at',
                'created_at',
                'updated_at',
            )
        );

        Schema::drop('disposals');
        Schema::rename('disposals_tmp', 'disposals');

        Schema::enableForeignKeyConstraints();
    }
};
