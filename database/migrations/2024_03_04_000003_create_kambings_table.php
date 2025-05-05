<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kambings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kambing')->unique();
            $table->string('nama_kambing');
            $table->foreignId('jenis_id')->constrained('jenis')->onDelete('cascade');
            $table->enum('jenis_kelamin', ['Jantan', 'Betina']);
            $table->date('tanggal_beli');
            $table->integer('umur');
            $table->decimal('harga_beli', 12, 2);
            $table->string('warna');
            $table->foreignId('barn_id')->constrained('barns')->onDelete('cascade');
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kambings');
    }
}; 