<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            @foreach ($datapermintaan as $permintaan)
                <td class="text-center" style="border: 1px solid #333;">
                    <p>NIK : {{ $permintaan->id_member }}</p>
                    <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$permintaan->id_permintaan", 'QRCODE') }}" alt="qrcode">
                    <br>
                    {{ $permintaan->id_permintaan }}
                    <br>
                    Harap membawa nomor ini untuk pengambilan fasilitas ke HRD & GA Dept
                </td>
                @if ($no++ % 3 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>