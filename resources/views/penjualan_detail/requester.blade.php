<div class="modal fade" id="modal-requester" tabindex="-1" role="dialog" aria-labelledby="modal-requester">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Requester</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-requester">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode Requester</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($requester as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->kode_requester }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihRequester('{{ $item->id_requester }}', '{{ $item->kode_requester }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>