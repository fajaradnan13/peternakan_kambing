<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goat_id')->constrained('kambings')->onDelete('cascade');
            $table->foreignId('feed_id')->constrained('feeds')->onDelete('cascade');
            $table->integer('amount');
            $table->date('feeding_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedings');
    }
}; 