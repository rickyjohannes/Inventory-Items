<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Requester;
use App\Models\Pengeluaran;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::count();
        $stokBaju = Produk::where('id_kategori' , '1')
        ->sum('stok');
        $stokCelana = Produk::where('id_kategori' , '2')
        ->sum('stok');
        $stokSepatu = Produk::where('id_kategori' , '3')
        ->sum('stok');
        $stokTopi = Produk::where('id_kategori' , '4')
        ->sum('stok');
        $produk = Produk::count();
        $supplier = Supplier::count();
        $member = Member::count();
        $permintaan = Produk::count();
        $requesters = Requester::where('status','=','0') ->whereNotNull('kode_requester')-> select('kode_requester') ->get() ->count();
        $requester  = Requester::count();
        
        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();
        $data_requester = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');
            $requester = Requester::where('created_at', 'LIKE', "%$tanggal_awal%")->whereNotNull('kode_requester')->count('id_requester');
            $data_requester[] += $requester;

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $pendapatan;
            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        if (auth()->user()->level == 1) {
            //return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
            return view('admin.dashboard', compact('kategori','stokBaju','stokCelana','stokSepatu','stokTopi', 'produk', 'requesters','requester','supplier', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan','data_requester'));
        } else {
            return view('kasir.dashboard');
        }
    }
}
