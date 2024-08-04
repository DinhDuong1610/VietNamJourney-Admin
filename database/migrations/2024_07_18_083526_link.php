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
        Schema::create('link', function (Blueprint $table) {
            $table->increments('Link_ID');
            $table->integer('User_ID')->nullable()->default(null);
            $table->char('Social', 50)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->text('Link')->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->timestamps();

            // Index
            $table->index('User_ID');

            // Foreign key constraint
            $table->foreign('User_ID')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link');
    }
};
