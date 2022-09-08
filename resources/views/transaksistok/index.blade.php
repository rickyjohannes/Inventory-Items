@extends('layouts.master')

@section('title')
    Daftar Permintaan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Permintaan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="tampilRequester()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transaksi Baru</button>                          
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-requester">
                    <thead>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%">Kode Requester</th>
                        <th width="12%">Kode Member</th>
                        <th>Nama</th>
                        <th>Total Item</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('transaksistok.requester')
@includeIf('transaksistok.detail')

@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-requester').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksistok.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'kode_requester'},
                {data: 'kode_member'},
                {data: 'nama'},
                {data: 'total_item'},
//                {data: 'total_harga'},
//                {data: 'diskon'},
//                {data: 'bayar'},
//                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-requester2').DataTable();
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
//                {data: 'harga_jual'},
                {data: 'jumlah'},
//                {data: 'subtotal'},
            ]
        })  
    });

    function tampilRequester() {
    $('#modal-requester2').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush