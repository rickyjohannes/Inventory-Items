<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Requester;
use App\Models\RequesterDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class TransaksiStokDetailController extends Controller
{
    public function index()
    {
        $id_penjualan = session('id_penjualan');
        $id_requester = session('id_requester');
        $produk = Produk::orderBy('nama_produk')->get();
        $member = Member::orderBy('nama')->get();
        $requester = Requester::leftJoin('member','member.id_member','requester.id_member')
        ->select('requester.*','nama')
        ->find(session('id_requester'));

       

        $diskon = Setting::first()->diskon ?? 0;

            $penjualan = Penjualan::find($id_penjualan);
            $requesters = Penjualan::find($id_requester);
            $memberSelected = $penjualan->member ?? new Member();
        
        // Cek apakah ada transaksi yang sedang berjalan
     
            return view('transaksistokdetail.index', compact('produk', 'member','requester','requesters', 'diskon', 'id_penjualan','id_requester', 'penjualan', 'memberSelected'));
        
    }

    public function getData($id)
     {
       $detail = RequesterDetail::with('produk','penjualan_detail')
            ->where('id_requester', $id)
            ->get();

        $data = array();

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['jumlah']      = $item->jumlah;
            $row['received']      = $item->received;

            $data[] = $row;
        }
        $data[] = [
            'kode_produk' => '',
            'nama_produk'    => '',
            'harga_jual'     => '',
            'jumlah'         => '',
            'diskon'         => '',
            'subtotal'       => '',
            'received'       => '',
       
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['kode_produk', 'jumlah'])
            ->make(true);
    }
    
    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan',$id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. '. format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->jumlah .'">';
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_penjualan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $requesters = RequesterDetail::where('id_requester', $request->id_requester)->first();
        if (! $requesters) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_requester = $requesters->id_requester;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = 0;
        $detail->subtotal = $produk->harga_jual;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
   {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah;
        $detail->update();
        
    }
    
    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
