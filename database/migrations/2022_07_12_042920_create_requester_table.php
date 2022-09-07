<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requester', function (Blueprint $table) {
            $table->increments('id_requester');
            $table->string('kode_requester',length:10)->nullable();
            $table->integer('id_member')->nullable();
            $table->integer('total_item');
            $table->integer('total_harga')->nullable();
            $table->tinyInteger('diskon')->nullable()
            ->default(0);
            $table->integer('bayar')->default(0);
            $table->integer('diterima')->nullable()
            ->default(0);
            $table->boolean('status')->default(0);
            $table->integer('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requesters');
    }
}
