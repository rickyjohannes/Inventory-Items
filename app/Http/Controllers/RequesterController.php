<?php

namespace App\Http\Controllers;

use App\Models\Requester;
use App\Models\RequesterDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;
use Twilio\Rest\Client;


class RequesterController extends Controller
{
    public function index()
    {
        return view('requester.index');
    }

    public function data()
    {
//        $requester = Requester::orderBy('kode_requester', 'asc')->get();
        $requester = Requester::orderBy('kode_requester','asc') 
        ->whereNotNull('kode_requester')
        ->get();
        $requester = Requester::with('member')->orderBy('id_requester', 'desc')
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
                if (auth()->user()->level == 1)
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('requester.show', $requester->id_requester) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('requester.destroy', $requester->id_requester) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    <button onclick="notaKecil(`'. route('trx.nota_kecil2', $requester->id_requester) .'`)" class="btn btn-info btn-xs btn-flat"><i class="fa fa-barcode"></i> Cetak Nota</button>
                </div>
                ';
                else
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('requester.show', $requester->id_requester) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="notaKecil(`'. route('trx.nota_kecil2', $requester->id_requester) .'`)" class="btn btn-info btn-xs btn-flat"><i class="fa fa-barcode"></i> Cetak Nota</button>
                    </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_requester','kode_member','nama'])
            ->make(true);
    }

    public function create()
    {

        $requester = new Requester();
        $requester->id_member = null;
        $requester->total_item = 0;
        $requester->total_harga = 0;
        $requester->diskon = 0;
        $requester->bayar = 0;
        $requester->diterima = 0;
        $requester->id_user = auth()->id();
        $requester->save();

        session(['id_requester' => $requester->id_requester]);
        return redirect()->route('trx.index');
        
    }

    public function store(Request $request)
    {
 //       $requester = Requester::latest()->first() ?? new Requester();
        
        //$requester = Requester::create($request->all());
  //      $requester = Requester::findOrFail($request->id_requester);
        $requester = Requester::latest()->first() ?? new Requester();
        $request['kode_requester'] = 'R'. tambah_nol_didepan((int)$requester->id_requester, 4 );
   //     $request['kode_permintaan'] = 'P'. tambah_nol_didepan((int)$form_permintaan->id_permintaan +1, 6);
        $requester->kode_requester = $request->kode_requester;
        $requester->id_member = $request->id_member;
        $requester->total_item = $request->total_item;
        $requester->total_harga = $request->total;
        $requester->diskon = $request->diskon;
        $requester->bayar = $request->bayar;
        $requester->diterima = $request->diterima;
        $requester->update();
/*
        $detail = RequesterDetail::where('id_requester', $requester->id_requester)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
     //       $produk->stok -= $item->jumlah;
            $produk->update();
        }
*/

        $telepon = '+6281362222160';
        $this->whatsappNotification($telepon);
        return redirect()->route('trx.selesai');
    }

    public function show($id)
    {
        $detail = RequesterDetail::with('produk')->where('id_requester', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('select_all', function ($detail) {
                return '
                    <input type="checkbox" name="id_requester_detail[]" value="'. $detail->id_requester_detail .'">
                ';
            })
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
            ->addColumn('received', function ($detail) {
                return format_uang($detail->received);
            })
            ->addColumn('aksi', function ($detail) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('trx.update', $detail->id_requester_detail) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $requester = Requester::find($id);
        $detail    = RequesterDetail::where('id_requester', $requester->id_requester)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
      /*      if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }
*/
            $item->delete();
        }

        $requester->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('requester.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $requester = Requester::find(session('id_requester'));
        if (! $requester) {
            abort(404);
        }
        $detail = RequesterDetail::with('produk')
            ->where('id_requester', session('id_requester'))
            ->get();
        
        return view('requester.nota_kecil', compact('setting', 'requester', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $requester = Requester::find(session('id_requester'));
        if (! $requester) {
            abort(404);
        }
        $detail = RequesterDetail::with('produk')
            ->where('id_requester', session('id_requester'))
            ->get();

        $pdf = PDF::loadView('requester.nota_besar', compact('setting', 'requester', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
    
    public function notaKecil2($id)
    {

         $setting = Setting::first();
        $requester = Requester::find($id);
        if (! $requester) {
            abort(404);
        }
        $detail = RequesterDetail::with('produk')
            ->where('id_requester', $requester->id_requester)
            ->get();

        return view('requester.nota_kecil', compact('setting', 'requester', 'detail'));
    }

    private function whatsappNotification(string $recipient)
    {
        $sid    = getenv("TWILIO_AUTH_SID");
        $token  = getenv("TWILIO_AUTH_TOKEN");
        $wa_from= getenv("TWILIO_WHATSAPP_FROM");
        $twilio = new Client($sid, $token);
        
        $body = "Hello, A New Requester Added!!!";

        return $twilio->messages->create("whatsapp:$recipient",["from" => "whatsapp:$wa_from", "body" => $body]);
    }

}
