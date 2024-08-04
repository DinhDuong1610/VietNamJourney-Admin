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
        Schema::create('post', function (Blueprint $table) {
            $table->increments('Post_ID');
            $table->integer('User_ID')->nullable()->default(null);
            $table->string('Content', 200)->nullable()->default(null)->collation('utf8_german2_ci');
            $table->string('Image', 50)->nullable()->default(null)->collation('latin1_swedish_ci');
            $table->integer('campaign_id')->nullable()->default(null);
            $table->integer('status')->nullable()->default(null);
            $table->timestamps();

            // Indexes
            $table->index('User_ID');

            // Foreign key constraint
            $table->foreign('User_ID')->references('id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('campaignId')->references('id')->on('campaign')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
