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
        Schema::table('cabangs', function (Blueprint $table) {
            $table->integer("jumlah_personel_cns")->default(0);
            $table->integer("formasi_cns")->default(0);
            $table->integer("frms_cns")->default(0);

            $table->integer("jumlah_personel_ess")->default(0);
            $table->integer("formasi_ess")->default(0);
            $table->integer("frms_ess")->default(0);

            $table->integer("jumlah_personel_staffumum")->default(0);
            $table->integer("formasi_staffumum")->default(0);
            $table->integer("frms_staffumum")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabangs', function (Blueprint $table) {
            $table->dropColumn("jumlah_personel_cns");
            $table->dropColumn("formasi_cns");
            $table->dropColumn("frms_cns");

            $table->dropColumn("jumlah_personel_ess");
            $table->dropColumn("formasi_ess");
            $table->dropColumn("frms_ess");

            $table->dropColumn("jumlah_personel_staffumum");
            $table->dropColumn("formasi_staffumum");
            $table->dropColumn("frms_staffumum");
        });
    }
};
