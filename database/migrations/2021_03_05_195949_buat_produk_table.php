<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->integer('id_kategori');
            $table->string('nama_produk')->unique();
            $table->string('merk')->nullable();
            $table->string('ukuran');
            $table->integer('harga_beli')->nullable()
            ->default(0);
            $table->tinyInteger('diskon')->nullable()
            ->default(0);
            $table->integer('harga_jual')->nullable()
            ->default(0);
            $table->integer('stok')->default(0);
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
        Schema::dropIfExists('produk');
    }
}
