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
        Schema::create('info_form_volunteer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname', 50)->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->date('birth')->nullable()->default(null);
            $table->string('phone', 11)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->string('email', 50)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->text('address')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_form_volunteer');
    }
};
