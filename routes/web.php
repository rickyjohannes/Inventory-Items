<?php

use App\Http\Controllers\{
    DashboardController,
    ExporterController,
    KategoriController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
    PermintaanController,
    RequesterController,
    RequesterDetailController,
    TransaksiStokController,
    TransaksiStokDetailController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/requester-export', [ExporterController::class, 'ERequester'])->name('erequester');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/permintaan/data', [PermintaanController::class, 'data'])->name('permintaan.data');
        Route::post('/permintaan/delete-selected', [PermintaanController::class, 'deleteSelected'])->name('permintaan.delete_selected');
        Route::post('/permintaan/cetak-barcode', [PermintaanController::class, 'cetakBarcode'])->name('permintaan.cetak_barcode');
        Route::resource('/permintaan', PermintaanController::class);

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

        Route::get('/transaksistok/data', [TransaksiStokController::class, 'data'])->name('transaksistok.data');
        Route::get('/transaksistok', [TransaksiStokController::class, 'index'])->name('transaksistok.index');
        Route::get('/transaksistok/{id}', [TransaksiStokController::class, 'show'])->name('transaksistok.show');
        Route::delete('/transaksistok/{id}', [TransaksiStokController::class, 'destroy'])->name('transaksistok.destroy');

    Route::group(['middleware' => 'level:1,2'], function () {

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        Route::get('/requester/data', [RequesterController::class, 'data'])->name('requester.data');
        Route::get('/requester', [RequesterController::class, 'index'])->name('requester.index');
        Route::get('/requester/{id}', [RequesterController::class, 'show'])->name('requester.show');
        Route::delete('/requester/{id}', [RequesterController::class, 'destroy'])->name('requester.destroy');
         Route::resource('/requester', RequesterController::class);

        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/trx/baru', [RequesterController::class, 'create'])->name('trx.baru');
        Route::post('/trx/simpan', [RequesterController::class, 'store'])->name('trx.simpan');
        Route::get('/trx/selesai', [RequesterController::class, 'selesai'])->name('trx.selesai');
        Route::get('/trx/nota-kecil', [RequesterController::class, 'notaKecil'])->name('trx.nota_kecil');
        Route::get('/trx/nota-besar', [RequesterController::class, 'notaBesar'])->name('trx.nota_besar');
        Route::get('/trx/{id}', [RequesterController::class, 'notaKecil2'])->name('trx.nota_kecil2');

        Route::get('/trx/{id}/data', [RequesterDetailController::class, 'data'])->name('trx.data');
        Route::get('/trx/loadform/{diskon}/{total}/{diterima}', [RequesterDetailController::class, 'loadForm'])->name('trx.load_form');
        Route::resource('/trx', RequesterDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/transaksistokdetail/{id}/create', [TransaksiStokController::class, 'create'])->name('transaksistok.create');
        Route::post('/transaksistokdetail/simpan', [TransaksiStokController::class, 'store'])->name('transaksistok.simpan');
        Route::get('/transaksistokdetail/selesai', [TransaksiStokController::class, 'selesai'])->name('transaksistok.selesai');
        Route::get('/transaksistokdetail/nota-kecil', [TransaksiStokController::class, 'notaKecil'])->name('transaksistok.nota_kecil');
        Route::get('/transaksistokdetail/nota-besar', [TransaksiStokController::class, 'notaBesar'])->name('transaksistok.nota_besar');
    
        Route::get('/transaksistokdetail/{id}/getdata', [TransaksiStokDetailController::class, 'getData'])->name('transaksistokdetail.getData');
        Route::get('/transaksistokdetail/{id}/data', [TransaksiStokDetailController::class, 'data'])->name('transaksistokdetail.data');
        Route::get('/transaksistokdetail/loadform/{diskon}/{total}/{diterima}', [TransaksiStokDetailController::class, 'loadForm'])->name('transaksistokdetail.load_form');
        Route::resource('/transaksistokdetail', TransaksiStokDetailController::class)
            ->except('create', 'show', 'edit');
        });
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');


    });
 
    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});