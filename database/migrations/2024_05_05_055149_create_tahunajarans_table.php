<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTahunajaransTable extends Migration
{
    public function up()
    {
        Schema::create('tahunajarans', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahunajarans');
    }
}
