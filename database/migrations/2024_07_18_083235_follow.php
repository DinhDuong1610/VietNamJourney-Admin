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
        Schema::create('follow', function (Blueprint $table) {
            $table->increments('Follow_ID');
            $table->integer('Follower_ID')->nullable()->default(null);
            $table->integer('Following_ID')->nullable()->default(null);
            $table->timestamps();

            // Indexes
            $table->index('Follower_ID');
            $table->index('Following_ID');

            // Foreign key constraints
            $table->foreign('Follower_ID')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('Following_ID')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow');
    }
};
