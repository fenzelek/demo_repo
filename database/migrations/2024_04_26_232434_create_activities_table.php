<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('FlightNumber')->nullable();
            $table->string('Type');
            $table->string('From');
            $table->string('To');
            $table->dateTime('Start')->nullable();
            $table->dateTime('End')->nullable();
            $table->date('Date');
            $table->timestamps();

            // Index on 'From'
            //TODO we should check the indexes with query explain and remove the unused indexes
            $table->index(['Date', 'From']);

            // Index on 'Start' and 'End'
            $table->index(['Start', 'End']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
