<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequesterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requester_detail', function (Blueprint $table) {
            $table->increments('id_requester_detail');
            $table->integer('id_requester')->nullable()
            ->default(NULL);
            $table->integer('id_produk');
            $table->integer('harga_jual');
            $table->integer('jumlah');
            $table->tinyInteger('diskon')->nullable()
            ->default(0);
            $table->integer('subtotal');
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
        Schema::dropIfExists('requester_details');
    }
}
