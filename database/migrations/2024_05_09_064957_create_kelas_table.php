<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matkul_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('kode_kelas');
            $table->string('prodi');
            $table->integer('kapasitas');
            $table->integer('jmlh_mhs');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('matkul_id')->references('id')->on('matkul')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosen')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahunajarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelas');
    }
}
