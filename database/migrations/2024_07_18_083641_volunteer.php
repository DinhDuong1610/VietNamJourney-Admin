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
        Schema::create('volunteer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')->nullable()->default(null);
            $table->integer('campaignId')->nullable()->default(null);
            $table->integer('status')->nullable()->default(null);
            $table->integer('formId')->nullable()->default(null);
            $table->timestamps();

            // Indexes
            $table->index('userId');
            $table->index('campaignId');
            $table->index('formId');

            // Foreign key constraints
            $table->foreign('userId')->references('Id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('campaignId')->references('id')->on('campaign')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('formId')->references('id')->on('form_volunteer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer');
    }
};
