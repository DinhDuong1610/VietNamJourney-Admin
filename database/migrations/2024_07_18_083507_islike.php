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
        Schema::create('islike', function (Blueprint $table) {
            $table->increments('IsLike_ID');
            $table->integer('User_ID')->nullable()->default(null);
            $table->integer('Post_ID')->nullable()->default(null);
            $table->timestamps();

            // Indexes
            $table->index('User_ID');
            $table->index('Post_ID');

            // Foreign key constraints
            $table->foreign('User_ID')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('Post_ID')->references('Post_ID')->on('post')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('islike');
    }
};
