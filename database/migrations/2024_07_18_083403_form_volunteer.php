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
        Schema::create('form_volunteer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('infoId')->nullable()->default(null);
            $table->text('reason')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->timestamps();

            // Index
            $table->index('infoId');

            // Foreign key constraint
            $table->foreign('infoId')->references('id')->on('info_form_volunteer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_volunteer');
    }
};
