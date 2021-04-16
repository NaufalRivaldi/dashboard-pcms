<html>
<head>
	<title>Summary Import</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        .c-col-4{
            width: 50%;
            float: left;
        }

        .c-col-2{
            width: 20%
            float: left;
        }
    </style>
</head>
<body>
 
	<div class="container">
		<center>
			<h4>SUMMARY IMPORT DATA</h4>
		</center>
		
        <br/>

        <!-- Start - row 1 -->
		<table width="100%">
            <tr>
                <td>Kode Cabang:</td>
                <td>{{ $summary->cabang->kode }}</td>
            </tr>
            <tr>
                <td>Nama Cabang:</td>
                <td>{{ $summary->cabang->nama }}</td>
            </tr>
            <tr>
                <td>Bulan:</td>
                <td>{{ setMonth($summary->bulan) }}</td>
            </tr>
            <tr>
                <td>Tahun:</td>
                <td>{{ $summary->tahun }}</td>
            </tr>
		</table>
        <!-- End - row 1 -->

        <hr style="opacity: 0">

        <!-- Start - row 2 -->
		<table width="100%">
            <tr>
                <td>Penerimaan Uang Pendaftaran:</td>
                <td align="right">{{ number_format($summary->uang_pendaftaran) }}</td>
            </tr>
            <tr>
                <td>Penerimaan Uang Kursus:</td>
                <td align="right">{{ number_format($summary->uang_kursus) }}</td>
            </tr>
            <tr>
                <td>Total Penerimaan:</td>
                <td align="right">{{ number_format($summary->uang_pendaftaran + $summary->uang_kursus) }}</td>
            </tr>
            <tr>
                <td>Royalti (10%):</td>
                <td align="right">{{ number_format(($summary->uang_pendaftaran + $summary->uang_kursus) * 0.1) }}</td>
            </tr>
		</table>
        <!-- End - row 2 -->

        <hr style="opacity: 0">

        <!-- Start - row 3 -->
		<table width="100%">
            <tr>
                <td>Jumlah Siswa Aktif:</td>
                <td align="right">{{ number_format($summary->siswa_aktif) }}</td>
            </tr>
            <tr>
                <td>Jumlah Siswa Baru:</td>
                <td align="right">{{ number_format($summary->siswa_baru) }}</td>
            </tr>
            <tr>
                <td>Jumlah Siswa Cuti:</td>
                <td align="right">{{ number_format($summary->siswa_cuti) }}</td>
            </tr>
            <tr>
                <td>Jumlah Siswa Keluar:</td>
                <td align="right">{{ number_format($summary->siswa_keluar) }}</td>
            </tr>
		</table>
        <!-- End - row 3 -->

        <hr style="opacity: 0">

        <!-- Start - row 4 -->
        <p>Siswa Aktif berdasarkan Jurusan;</p>
		<table width="100%">
            @foreach($summary->summary_sa_materi as $row)
                <tr>
                    <td>{{ $row->materi->nama }}</td>
                    <td align="right">{{ $row->jumlah }}</td>
                </tr>
            @endforeach
		</table>
        <!-- End - row 4 -->

        <hr style="opacity: 0">

        <!-- Start - row 5 -->
        <p>Siswa Aktif berdasarkan Pendidikan;</p>
		<table width="100%">
            @foreach($summary->summary_sa_pendidikan as $row)
                <tr>
                    <td>{{ $row->pendidikan->nama }}</td>
                    <td align="right">{{ $row->jumlah }}</td>
                </tr>
            @endforeach
		</table>
        <!-- End - row 5 -->

        <hr style="opacity: 0">

        <div class="row">
            <div class="c-col-4">
                <table width="100%">
                    <tr>
                        <td>Submitted:</td>
                        <td>{{ date('d F Y', strtotime($summary->created_at)) }}</td>
                    </tr>
                    <tr>
                        <td>Submitted by</td>
                        <td>{{ $summary->user->nama }}</td>
                    </tr>
                </table>
            </div>
            <div class="c-col-2"></div>
            <div class="c-col-4">
            <table width="100%">
                    <tr>
                        <td>Approved:</td>
                        <td>{{ date('d F Y', strtotime($summary->updated_at)) }}</td>
                    </tr>
                    <tr>
                        <td>Approved by</td>
                        <td>{{ $summary->user_approve->nama }}</td>
                    </tr>
                </table>
            </div>
        </div>
 
	</div>
 
</body>
</html>