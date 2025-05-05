<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kambing');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis');
    }
}; 