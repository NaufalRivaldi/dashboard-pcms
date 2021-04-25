<html>
<head>
	<title>Summary Import</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <h3>Dashboard PCMS</h3>

                @if($type == 1)
                <p>
                    Summary Import Cabang {{ $cabang }} periode {{ setMonth($summary->bulan).' '.$summary->tahun }} sudah di approve.<br><br><br><br>
                    Best regards,<br><br>

                    {{ Auth::user()->nama }}
                </p>
                @endif

                @if($type == 2)
                <p>
                    Summary Import Cabang {{ $cabang }} periode {{ setMonth($summary->bulan).' '.$summary->tahun }} masih ada kesalahan data, segera perbaiki dan import ulang data.<br><br><br><br>
                    Best regards,<br><br>

                    {{ Auth::user()->nama }}
                </p>
                @endif

                @if($type == 3)
                <p>
                    Summary Import Cabang {{ $cabang }} periode {{ setMonth($summary->bulan).' '.$summary->tahun }} sudah diimport, silahkan lakukan verifikasi data.<br><br><br><br>
                    Best regards,<br><br>

                    {{ Auth::user()->nama }}
                </p>
                @endif
            </div>
        </div>
    </div>
 
</body>
</html>