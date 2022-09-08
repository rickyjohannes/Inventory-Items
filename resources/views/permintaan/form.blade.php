<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <label for="id_produk" class="col-lg-2 col-lg-offset-1 control-label">Produk</label>
                        <div class="col-lg-6">
                            <select name="id_produk" id="id_produk" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produk as $key => $produk)
                                <option value="{{ $key }}">{{ $produk }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="id_member" class="col-lg-2 col-lg-offset-1 control-label">NIK</label>
                        <div class="col-lg-6">
                            <input type="text" name="id_member" id="id_member" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label for="jml_minta" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Permintaan</label>
                        <div class="col-lg-6">
                            <input type="text" name="jml_minta" id="merk" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>