<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signatories', function (Blueprint $table) {
            $table->id();
            $table->string('role_key', 50);           // e.g. 'pgso_head', 'governor', 'property_inspector', 'coa_representative'
            $table->string('name');                     // Full name e.g. "CHARLITO G. DE LA COSTA"
            $table->string('designation');               // e.g. "OIC-Provincial General Services Office"
            $table->string('entity_name')->default('Provincial Government of Surigao Del Norte');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the default signatories
        DB::table('signatories')->insert([
            [
                'role_key' => 'pgso_head',
                'name' => 'CHARLITO G. DE LA COSTA',
                'designation' => 'OIC-Provincial General Services Office',
                'entity_name' => 'Provincial Government of Surigao Del Norte',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_key' => 'governor',
                'name' => 'ROBERT LYNDON S. BARBERS',
                'designation' => 'Provincial Governor',
                'entity_name' => 'Provincial Government of Surigao Del Norte',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_key' => 'property_inspector',
                'name' => 'MARIEFLOR M. GUIAS',
                'designation' => 'Administrative Officer - I',
                'entity_name' => 'Provincial Government of Surigao Del Norte',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_key' => 'provincial_accountant',
                'name' => 'RHEA T. PARUNGAO, CPA',
                'designation' => 'Provincial Accountant',
                'entity_name' => 'Provincial Government of Surigao Del Norte',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatories');
    }
};
