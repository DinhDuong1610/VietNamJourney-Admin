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
        Schema::create('chatgroup_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->nullable()->default(null);
            $table->integer('user_from')->nullable()->default(null);
            $table->text('content')->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->string('image', 255)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->timestamps();

            // Indexes
            $table->index('campaign_id');
            $table->index('user_from');

            // Foreign key constraints
            $table->foreign('campaign_id')->references('id')->on('campaign')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_from')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatgroup_message');
    }
};
