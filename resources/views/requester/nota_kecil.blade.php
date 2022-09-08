<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Out Permintaan</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()" style="background-image: url(img/bg.png)">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h3>
        <p>{{ strtoupper($setting->alamat) }}</p>
    </div>
    <br>

        <p class="text-center">Tanggal Permintaan</p>
        <p class="text-center">{{ date('d-m-Y') }}</p>
<!--        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p> -->
    <br>    
    <div class="clear-both" style="clear: both;"></div>
    <p class="text-center">Nomor Permintaan</p>
    <p class="text-center">{{ ($requester->kode_requester) }}</p>
    <p class="text-center"><img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$requester->kode_requester", 'QRCODE') }}" alt="qrcode"
        height="75"
        widht="75"></p>
    <p class="text-center">===================================</p>
    <p class="text-center">-----------------------------------</p>
    <p class="text-center">List Item Permintaan</p>
    <br>
    <table width="100%" style="border: 0;">
        @foreach ($detail as $item)
            <tr align="center">
                <i class="fa fa-check icon"></i>
                <td colspan="3"># {{ $item->produk->nama_produk }} @ {{ $item->jumlah }}</td>
            </tr>
            <tr>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>
    <p class="text-center">===================================</p>
    <p class="text-center">Requester : {{ $requester->member->nama ?? '' }}</p>
    <p class="text-center">-- Mohon Untuk Disimpan --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>