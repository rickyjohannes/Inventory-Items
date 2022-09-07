<?php

namespace App\Http\Controllers;

use App\Models\Requester;
use App\Models\RequesterDetail;
use App\Models\Member;
use App\Models\Produk;
use Illuminate\Http\Request;

use PDF;

class LaporanRequesterController extends Controller
{
    public function index()
    {   
    	return view('laporanrequester.pdf');
    }


    public function exportPDF()
{

        $requester = Requester::leftJoin('member','member.id_member','requester.id_member')
            ->leftJoin('requester_detail','requester_detail.id_requester','requester.id_requester')
            ->leftJoin('produk','produk.id_produk','requester_detail.id_produk')
            ->select('requester.*', 'kode_member','nama_produk','kode_produk','jumlah') 
            ->whereNotNull('kode_requester')
            ->get();
            


	$pdf = PDF::loadview('laporanrequester.pdf',['requester' => $requester])->setPaper('A4','potrait');
	return $pdf->stream();
}
    
}
