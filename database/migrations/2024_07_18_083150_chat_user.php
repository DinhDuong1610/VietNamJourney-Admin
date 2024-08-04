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
        Schema::create('chat_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_from')->nullable()->default(null);
            $table->integer('user_to')->nullable()->default(null);
            $table->text('content')->nullable()->default(null)->collation('utf8_general_ci');
            $table->string('image', 255)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->timestamps();

            // Indexes
            $table->index('user_from');
            $table->index('user_to');

            // Foreign key constraints
            $table->foreign('user_from')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_to')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_user');
    }
};
