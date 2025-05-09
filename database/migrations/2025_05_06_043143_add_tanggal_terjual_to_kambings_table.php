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
        Schema::table('kambings', function (Blueprint $table) {
            if (!Schema::hasColumn('kambings', 'berat')) {
                $table->decimal('berat', 8, 2)->nullable()->after('umur');
            }
            if (!Schema::hasColumn('kambings', 'tanggal_terjual')) {
                $table->date('tanggal_terjual')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kambings', function (Blueprint $table) {
            if (Schema::hasColumn('kambings', 'berat')) {
                $table->dropColumn('berat');
            }
            if (Schema::hasColumn('kambings', 'tanggal_terjual')) {
                $table->dropColumn('tanggal_terjual');
            }
        });
    }
};
