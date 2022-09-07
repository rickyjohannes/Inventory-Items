<?php

namespace App\Http\Controllers;


use PDF;
use App\Models\Produk;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produk = Produk::all()->pluck('nama_produk', 'id_produk');

        return view('permintaan.index', compact('produk'));
    }

    public function data()
    {
        $permintaan = Permintaan::leftJoin('produk', 'produk.id_produk', 'permintaan.id_produk')
            ->select('permintaan.*', 'nama_produk', 'kode_produk', 'nama_produk')
            ->orderBy('id_permintaan', 'asc')
            ->get();

        return datatables()
            ->of($permintaan)
            ->addIndexColumn()
            ->addColumn('select_all', function ($permintaan) {
                return '
                    <input type="checkbox" name="id_permintaan[]" value="'. $permintaan->id_permintaan .'">
                ';
            })
            ->addColumn('id_permintaan', function ($permintaan) {
                return '<span class="label label-success">'. $permintaan->id_permintaan .'</span>';
            })

            ->addColumn('aksi', function ($permintaan) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('permintaan.update', $permintaan->id_permintaan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id_permintaan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'id_permintaan', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$member = Member::latest()->first() ?? new Member();
        //$kode_member = (int) $member->kode_member +1;
        
        //$permintaan = Permintaan::latest()->first() ?? new Permintaan();
        //$request['id_permintaan'] = 'P'. tambah_nol_didepan((int)$permintaan->id_permintaan +1, 6);

        $permintaan = new Permintaan();
        //$member->kode_member = tambah_nol_didepan($kode_member, 5);
        //$permintaan->id_permintaan = $request->id_permintaan;
        $permintaan->id_produk = $request->id_produk;
        $permintaan->id_member = $request->id_member;
        $permintaan->jml_minta = $request->jml_minta;
        $permintaan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permintaan = Permintaan::find($id);

        return response()->json($permintaan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permintaan = Permintaan::find($id);
        $permintaan->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permintaan = Permintaan::find($id);
        $permintaan->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->iid_permintaan as $id) {
            $permintaan = Permintaan::find($id);
            $permintaan->delete();
        }

        return response(null, 204);
    }

    public function cetakBarcode(Request $request)
    {
        $datapermintaan = array();
        foreach ($request->id_permintaan as $id) {
            $permintaan = Permintaan::find($id);
            $datapermintaan[] = $permintaan;
        }

        $no  = 1;
        $pdf = PDF::loadView('permintaan.barcode', compact('datapermintaan', 'no'));
        $pdf->setPaper('a7', 'potrait');
        return $pdf->stream('permintaan.pdf');
    }
}
