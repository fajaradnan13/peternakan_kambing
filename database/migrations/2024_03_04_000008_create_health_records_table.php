<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goat_id')->constrained('kambings')->onDelete('cascade');
            $table->date('checkup_date');
            $table->enum('kondisi_kesehatan', ['Sehat', 'Sakit'])->default('Sehat');
            $table->boolean('kehamilan')->default(false);
            $table->string('condition');
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_records');
    }
}; 