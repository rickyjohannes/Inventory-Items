<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequesterDetail extends Model
{
    use HasFactory;

    protected $table = 'requester_detail';
    protected $primaryKey = 'id_requester_detail';
    protected $guarded = [];

    public function produk()
    {
        return $this->hasOne(Produk::class, 'id_produk', 'id_produk');
    }

    public function member()
    {
        return $this->hasOne(Produk::class, 'id_member', 'id_member');
    }

    public function requester()
    {
        return $this->hasOne(Requester::class, 'id_requester', 'id_requester');
    }

    public function penjualan_detail()
    {
        return $this->hasOne(PenjualanDetail::class, 'id_penjualan_detail', 'id_penjualan_detail');
    }
}
