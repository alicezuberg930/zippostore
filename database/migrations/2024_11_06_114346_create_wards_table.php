<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->string("code")->primary();
            $table->string("name");
            $table->string("name_en");
            $table->string("full_name");
            $table->string("full_name_en");
            $table->string("code_name");
            $table->foreignId("administrative_unit_id")->constrained('administrative_units');
            $table->string("district_code")->index();
            $table->foreign('district_code')->references('code')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
