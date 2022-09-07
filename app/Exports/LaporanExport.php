<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $produk =  Produk::leftJoin('kategori','kategori.id_kategori','produk.id_kategori')
         ->select('kode_produk','nama_produk','nama_kategori','merk','ukuran','stok')
         ->get();

        return $produk;   
    }
}
