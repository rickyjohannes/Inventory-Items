<?php

namespace App\Exports;

use App\Models\Requester;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RequesterExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            "Tanggal","Kode Requester","Kode Member","Nama","Total Item"
        ];
    }

    public function collection()
    {
        $requester = Requester::leftJoin('member','member.id_member','requester.id_member')
            ->leftJoin('requester_detail','requester_detail.id_requester','requester.id_requester')
            ->leftJoin('produk','produk.id_produk','requester_detail.id_produk')
            ->select(DB::raw('DATE_FORMAT(requester.created_at, "%d-%m-%Y") as formated_dob'),'kode_requester','member.kode_member','member.nama','total_item','produk.kode_produk','produk.nama_produk','requester_detail.jumlah')
            ->whereNotNull('kode_requester')
            ->get();;
 //           $requester = Requester::leftJoin('member','member.id_member','requester.id_member')
 //           ->leftJoin('requester_detail','requester_detail.id_requester','requester.id_requester')
 //           ->leftJoin('produk','produk.id_produk','requester_detail.id_produk')
 //           ->select('requester.*', 'kode_member','nama_produk','kode_produk','jumlah') 
 //           ->whereNotNull('kode_requester')
 //           ->get();
        
            return $requester;
    }
}