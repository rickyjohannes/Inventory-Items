<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Requester;
use App\Models\RequesterDetail;
use PDF;

class TransaksiStokController extends Controller
{
    public function index()
    {
        $requester = Requester::leftJoin('member','member.id_member','requester.id_member')
            ->select('requester.*','nama')
            ->where('status','==',0)
            ->whereNotNull('kode_requester')
            ->orderBy('id_requester','asc')
            ->get();
        return view('transaksistok.index', compact('requester'));
    }

    public function data()
    {
    //        $requester = Requester::orderBy('kode_requester', 'asc')->get();
    $requester = Requester::with('member')->orderBy('id_requester', 'desc')
    ->where('status','==',0)
    ->whereNotNull('kode_requester')
    ->get();

    return datatables()
        ->of($requester)
        ->addIndexColumn()
        ->addColumn('total_item', function ($requester) {
            return format_uang($requester->total_item);
        })
        ->addColumn('total_harga', function ($requester) {
            return 'Rp. '. format_uang($requester->total_harga);
        })
        ->addColumn('bayar', function ($requester) {
            return 'Rp. '. format_uang($requester->bayar);
        })
        ->addColumn('tanggal', function ($requester) {
            return tanggal_indonesia($requester->created_at, false);
        })
        ->addColumn('kode_requester', function ($requester) {
            if ( $requester->status == 1) {
                return '<span class="label label-success">'. $requester->kode_requester .'</span>';
            } else {
                return '<span class="label label-danger">'. $requester->kode_requester .'</span>';
            }
        })
        ->addColumn('kode_member', function ($requester) {
            $member = $requester->member->kode_member ?? '';
            return $member;
        })
        ->addColumn('nama', function ($requester) {
            $member = $requester->member->nama ?? '';
            return $member;
        })
        ->editColumn('diskon', function ($requester) {
            return $requester->diskon . '%';
        })
        ->editColumn('kasir', function ($requester) {
            return $requester->user->name ?? '';
        })
        ->addColumn('aksi', function ($requester) {
            return '
            <div class="btn-group">
                <button onclick="showDetail(`'. route('requester.show', $requester->id_requester) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
            </div>
            ';
        })
        ->rawColumns(['aksi', 'kode_requester','kode_member','nama'])
        ->make(true);
    }

    public function create($id)
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->id_requester = $id;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        session(['id_requester' => $penjualan->id_requester]);
        return redirect()->route('transaksistokdetail.index');
    }

    public function store(Request $request)
    {
      //  $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan = Penjualan::latest()->first() ?? new Penjualan();
        $penjualan->id_member = $request->id_member;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
            

       $requesters = RequesterDetail::select('received')->where('id_requester', '=', $item->id_requester)
       ->where('id_produk', '=', $item->id_produk)->first()->received;
        $requesters +=  $item->jumlah;
       RequesterDetail::where('id_requester', '=', $item->id_requester)->where('id_produk', '=', $item->id_produk)->update(['received'=>$requesters]);


        }
        $requester = Requester::find($penjualan->id_requester);
        $total = $requester->total_item;
        $details = Penjualan::where('id_requester',$penjualan->id_requester)->sum('total_item');

            if ($details == $total ) {
            $requester->status ='1';
            $requester->update();
            }
            else{
                $requester->status ='0';
                 $requester->update();
            }
            
       

        
        return redirect()->route('transaksistok.selesai');
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('transaksistok.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('transaksistok.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('transaksistok.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
