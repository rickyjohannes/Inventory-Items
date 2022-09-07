<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Member;
use App\Models\Produk;
use Illuminate\Http\Request;

use PDF;

class LaporanStokMasukController extends Controller
{
    public function index()
    {   
    	return view('laporanstokmasuk.pdf');
    }


    public function exportPDF()
{

        $penjualan = Penjualan::leftJoin('member','member.id_member','penjualan.id_member')
            ->leftJoin('penjualan_detail','penjualan_detail.id_penjualan','penjualan.id_penjualan')
            ->leftJoin('requester','requester.id_requester','penjualan.id_requester')
            ->leftJoin('produk','produk.id_produk','penjualan_detail.id_produk')
            ->select('penjualan.*', 'kode_member','nama','kode_requester','nama_produk','kode_produk','jumlah') 
            ->whereNotNull('kode_requester')
            ->get();
            


	$pdf = PDF::loadview('laporanstokmasuk.pdf',['penjualan' => $penjualan])->setPaper('A4','potrait');
	return $pdf->stream();
}
    
}
