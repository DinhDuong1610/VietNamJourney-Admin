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
        Schema::create('campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')->nullable()->default(null);
            $table->text('name')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->string('province', 50)->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->string('district', 50)->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->text('location')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->date('dateStart')->nullable()->default(null);
            $table->date('dateEnd')->nullable()->default(null);
            $table->bigInteger('totalMoney')->nullable()->default(null);
            $table->bigInteger('moneyByVNJN')->nullable()->default(null);
            $table->text('timeline')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->text('infoContact')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->text('infoOrganization')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->text('image')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->text('description')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->text('plan')->nullable()->default(null)->collation('utf8mb4_general_ci');
            $table->integer('status')->nullable()->default(0);
            $table->timestamps(); // This will automatically add created_at and updated_at columns
        
            $table->foreign('userId')->references('id')->on('user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign');
    }
};
