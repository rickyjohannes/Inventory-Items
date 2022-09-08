<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="header">MASTER</li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Kategori</span>
                </a>
            </li>
            <!--
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-file-text"></i> <span>Permintaan</span>
                </a>
            </li>
            -->

            <li>
                <a href="{{ route('requester.index') }}">
                    <i class="fa fa-file-text-o"></i> <span>Permintaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Karyawan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Supplier</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
<!--            <li>
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money"></i> <span>Pengeluaran</span>
                </a>
            </li>
-->
            <li>
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-download"></i> <span>Transaksi Stok Masuk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-upload"></i> <span>Transaksi Stok Keluar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksistok.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Daftar Permintaan</span>
                </a>
            </li>
 <!--           
            <li>
                <a href="{{ route('transaksi.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
                </a>
            </li>
        -->            
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-edit"></i> <span>Manual Stok Keluar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('trx.baru') }}">
                    <i class="fa fa-files-o"></i> <span>Form Requester Baru</span>
                </a>
            </li>
            
<!--            
            <li class="header">REPORT</li>
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
                </a>
            </li>
        -->            
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route("setting.index") }}">
                    <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                </a>
            </li>
            
            @else

            <li>
                <a href="{{ route('requester.index') }}">
                    <i class="fa fa-upload"></i> <span>Daftar Permintaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('trx.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Form Requester Baru</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Karyawan</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>