<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor — {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #1E3A5F; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1E3A5F; font-size: 20px; }
        .header p { margin: 2px 0; color: #555; font-size: 11px; }
        .siswa-block { page-break-after: always; margin-bottom: 30px; }
        .siswa-block:last-child { page-break-after: auto; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 0; font-size: 12px; }
        table.nilai { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.nilai th, table.nilai td { border: 1px solid #333; padding: 6px 8px; font-size: 11px; }
        table.nilai th { background: #1E3A5F; color: white; text-align: center; }
        table.nilai td { text-align: center; }
        table.nilai td.text-left { text-align: left; }
        .signature { display: flex; justify-content: space-between; margin-top: 40px; }
        .signature div { text-align: center; width: 200px; }
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 10px 20px; background: #1E3A5F; color: white; border: none; border-radius: 8px; cursor: pointer; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak / Print PDF</button>

    @foreach($kelas->siswa as $siswa)
    <div class="siswa-block">
        <div class="header">
            <h1>PRATINJAU NILAI SISWA</h1>
            <p>Belum merupakan rapor terbit. Gunakan menu Rapor Terbit untuk hasil yang sudah disahkan.</p>
            <p>SMA Smart School — Tahun Ajaran {{ $tahunAjaran }} Semester {{ $semester }}</p>
        </div>

        <table class="info-table">
            <tr><td width="120"><strong>Nama Siswa</strong></td><td>: {{ $siswa->user->name }}</td></tr>
            <tr><td><strong>NIS</strong></td><td>: {{ $siswa->nis }}</td></tr>
            <tr><td><strong>Kelas</strong></td><td>: {{ $kelas->nama_kelas }}</td></tr>
            <tr><td><strong>Tahun Ajaran</strong></td><td>: {{ $tahunAjaran }} — Semester {{ $semester }}</td></tr>
        </table>

        <table class="nilai">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Mata Pelajaran</th>
                    <th width="70">Harian</th>
                    <th width="70">UTS</th>
                    <th width="70">UAS</th>
                    <th width="80">Nilai Akhir</th>
                    <th width="70">Predikat</th>
                    <th width="80">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php $nilaiSemester = $siswa->nilai->where('tahun_ajaran', $tahunAjaran)->where('semester', $semester); @endphp
                @forelse($nilaiSemester as $i => $n)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-left">{{ $n->mataPelajaran->nama_mapel }}</td>
                    <td>{{ $n->nilai_harian ?? '-' }}</td>
                    <td>{{ $n->nilai_uts ?? '-' }}</td>
                    <td>{{ $n->nilai_uas ?? '-' }}</td>
                    <td><strong>{{ $n->nilai_akhir !== null ? number_format($n->nilai_akhir,1) : '-' }}</strong></td>
                    <td>{{ $n->predikat ?? '-' }}</td>
                    <td>{{ $n->nilai_akhir === null ? 'Belum lengkap' : ($n->nilai_akhir >= $n->mataPelajaran->kkm ? 'Tuntas' : 'Remedial') }}</td>
                </tr>
                @empty
                <tr><td colspan="8">Belum ada nilai untuk semester ini</td></tr>
                @endforelse
            </tbody>
        </table>

        <table class="info-table" style="margin-top:10px">
            <tr><td width="120"><strong>Rata-rata</strong></td><td>: {{ $nilaiSemester->count() ? number_format($nilaiSemester->avg('nilai_akhir'), 2) : '-' }}</td></tr>
        </table>

        <div class="signature">
            <div>
                <p>Orang Tua/Wali</p>
                <br><br><br>
                <p>( {{ $siswa->orangTua?->nama_ayah ?? '...........................' }} )</p>
            </div>
            <div>
                <p>Medan, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p>Wali Kelas</p>
                <br><br>
                <p>( {{ $kelas->wali_kelas ?? '...........................' }} )</p>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
