<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai Pilmapres</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="12">REKAPITULASI NILAI PILMAPRES</th>
            </tr>
            <tr>
                <th colspan="12">Periode {{ $periodeAktif->tahun ?? 'N/A' }}</th>
            </tr>
            <tr>
                <th colspan="12"></th>
            </tr>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Nama Peserta</th>
                <th rowspan="2">NIM</th>
                <th rowspan="2">Program Studi</th>
                <th colspan="2">Capaian Unggulan (CU)</th>
                <th colspan="2">Gagasan Kreatif (GK)</th>
                <th colspan="2">Bahasa Inggris (BI)</th>
                <th rowspan="2">Nilai Akhir</th>
                <th rowspan="2">Status</th>
            </tr>
            <tr>
                <th>Kontribusi</th>
                <th>Skor</th>
                <th>Kontribusi</th>
                <th>Skor</th>
                <th>Kontribusi</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @php $peringkat = 0; @endphp
            @foreach ($pesertas as $peserta)
                @if($peserta->status_verifikasi == 'ditolak')
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $peserta->nama_lengkap }}</td>
                        <td>{{ $peserta->nim }}</td>
                        <td>{{ $peserta->prodi }}</td>
                        <td colspan="6" style="text-align: center;">DIDISKUALIFIKASI</td>
                        <td>0.00</td>
                        <td>Didiskualifikasi</td>
                    </tr>
                @else
                    @php $peringkat++; @endphp
                    <tr>
                        <td>{{ $peringkat }}</td>
                        <td>{{ $peserta->nama_lengkap }}</td>
                        <td>{{ $peserta->nim }}</td>
                        <td>{{ $peserta->prodi }}</td>
                        <td>45%</td>
                        <td>{{ number_format($peserta->total_skor_cu ?? 0, 2) }}</td>
                        <td>35%</td>
                        <td>{{ number_format($peserta->total_skor_gk ?? 0, 2) }}</td>
                        <td>20%</td>
                        <td>{{ number_format($peserta->total_skor_bi ?? 0, 2) }}</td>
                        <td>{{ number_format($peserta->skor_akhir ?? 0, 2) }}</td>
                        <td>Diverifikasi</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>