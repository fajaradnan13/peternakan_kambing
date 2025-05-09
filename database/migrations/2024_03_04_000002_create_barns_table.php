<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('location')->nullable();
            $table->integer('kapasitas')->default(0);
            $table->enum('status', ['tersedia', 'penuh'])->default('tersedia');
            $table->enum('kondisi', ['baik', 'perlu perbaikan', 'rusak'])->default('baik');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barns');
    }
}; 