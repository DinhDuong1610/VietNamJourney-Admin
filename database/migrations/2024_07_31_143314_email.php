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
        Schema::create('email', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('userId')->nullable();
            $table->unsignedInteger('isAdmin')->nullable();
            $table->text('title')->nullable()->collation('utf8mb4_general_ci');
            $table->text('content')->nullable()->collation('utf8mb4_general_ci');
            $table->integer('status')->nullable()->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('userId')->references('id')->on('user')->onUpdate('cascade')->onDelete('restrict');

            $table->index('userId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email');
    }
};
