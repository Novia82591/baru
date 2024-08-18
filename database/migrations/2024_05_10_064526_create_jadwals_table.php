<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalsTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('ruang_id');
            $table->string('hari');
            $table->string('jam');
            $table->string('prodi');
            $table->string('semester');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('ruang_id')->references('id')->on('ruang')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahunajarans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
}
