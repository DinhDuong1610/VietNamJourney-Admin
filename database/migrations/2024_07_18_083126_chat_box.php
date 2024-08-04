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
        Schema::create('chat_box', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_1')->nullable()->default(null);
            $table->integer('user_2')->nullable()->default(null);
            $table->timestamps();

            // Indexes
            $table->index('user_1');
            $table->index('user_2');

            // Foreign key constraints
            $table->foreign('user_1')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_2')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_box');
    }
};
