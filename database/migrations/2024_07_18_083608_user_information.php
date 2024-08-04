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
        Schema::create('user_information', function (Blueprint $table) {
            $table->increments('User_ID');
            $table->integer('UserLogin_ID')->nullable()->default(null);
            $table->string('Username', 50)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->string('Email', 50)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->string('Name', 50)->nullable()->default(null)->collation('utf8_german2_ci');
            $table->text('URL')->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->string('Role', 50)->nullable()->default(null)->collation('utf8_general_ci');
            $table->string('LiveAt', 50)->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->string('Image', 50)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->tinyInteger('check')->nullable()->default(0);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('UserLogin_ID')->references('id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};
