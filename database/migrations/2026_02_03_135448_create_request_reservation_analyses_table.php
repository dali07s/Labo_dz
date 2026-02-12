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
    public function up()
    {
        Schema::create('request_reservation_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('analyse_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['request_reservation_id', 'analyse_id'], 'req_res_analyses_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_reservation_analyses');
    }
};
