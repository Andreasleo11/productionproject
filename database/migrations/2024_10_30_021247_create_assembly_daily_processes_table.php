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
        Schema::create('assembly_daily_processes', function (Blueprint $table) {
            $table->id();
            $table->date('plan_date');
            $table->string('line');
            $table->string('item_code');
            $table->string('item_description');
            $table->integer('quantity');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assembly_daily_processes');
    }
};
