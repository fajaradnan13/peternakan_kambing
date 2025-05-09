<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['Peralatan', 'Perlengkapan Kandang']);
            $table->date('tgl_beli');
            $table->string('satuan');
            $table->decimal('harga', 10, 2);
            $table->integer('jumlah_beli');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}; 