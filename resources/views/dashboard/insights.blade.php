<section class="insights-grid" aria-label="Grafik ringkasan">
@foreach($insights as $chart)
<article class="insight-card">
    <header class="insight-heading"><span class="insight-kicker">{{ $chart['type'] === 'line' ? 'TREN' : 'RINGKASAN' }}</span><h3>{{ $chart['title'] }}</h3><p>{{ $chart['caption'] }}</p></header>
    @if(!$chart['hasData'])
        <div class="insight-empty"><i class="fa-solid fa-chart-simple" aria-hidden="true"></i><strong>Data belum tersedia</strong><p>{{ $chart['empty'] }}</p></div>
    @elseif($chart['type'] === 'line')
        @php
            $count = count($chart['rows']);
            $points = [];
            foreach ($chart['rows'] as $i => $row) {
                $points[] = $row['value'] === null ? null : [48 + $i * 504 / max(1, $count - 1), 172 - $row['value'] / $chart['maximum'] * 140];
            }
        @endphp
        <div class="insight-plot"><svg viewBox="0 0 600 212" role="img" aria-label="{{ $chart['title'] }}. Angka lengkap tersedia pada rincian data di bawah.">
            @foreach([0, 0.5, 1] as $tick)
            <line x1="48" x2="552" y1="{{ 172 - 140*$tick }}" y2="{{ 172 - 140*$tick }}" class="plot-grid"/>
            <text x="38" y="{{ 176 - 140*$tick }}" text-anchor="end" class="plot-label">{{ round($chart['maximum']*$tick, 1) }}</text>
            @endforeach
            @foreach($points as $i => $point)
                @if($point !== null)
                    @if($i > 0 && $points[$i-1] !== null)<line x1="{{ $points[$i-1][0] }}" y1="{{ $points[$i-1][1] }}" x2="{{ $point[0] }}" y2="{{ $point[1] }}" class="plot-line"/>@endif
                    <circle cx="{{ $point[0] }}" cy="{{ $point[1] }}" r="5" class="plot-dot"><title>{{ $chart['rows'][$i]['label'] }}: {{ $chart['rows'][$i]['value'] }} {{ $chart['unit'] }}</title></circle>
                @endif
                <text x="{{ 48 + $i*504/max(1,$count-1) }}" y="200" text-anchor="middle" class="plot-label">{{ $chart['rows'][$i]['label'] }}</text>
            @endforeach
        </svg></div>
    @else
        <div class="insight-bars" aria-label="{{ $chart['title'] }}">
        @foreach($chart['rows'] as $row)
            <div class="insight-bar-row">
                <div class="insight-bar-label"><span>{{ $row['label'] }}</span><strong>{{ $row['value'] === null ? 'Belum diisi' : $row['value'].' '.$chart['unit'] }}</strong></div>
                <div class="insight-track"><span class="tone-{{ $row['tone'] ?? 'forest' }}" style="width:{{ $row['value'] === null ? 0 : min(100, max(0, $row['value']/$chart['maximum']*100)) }}%"></span></div>
                @if(isset($row['note']))<small>{{ $row['note'] }}</small>@endif
            </div>
        @endforeach
        </div>
    @endif
    @if($chart['hasData'])
    <details class="insight-details"><summary>Lihat rincian data</summary><div class="insight-table-wrap"><table><thead><tr><th scope="col">Keterangan</th><th scope="col">{{ $chart['unit'] }}</th></tr></thead><tbody>
    @foreach($chart['rows'] as $row)<tr><th scope="row">{{ $row['label'] }} @if(isset($row['note']))<small>({{ $row['note'] }})</small>@endif</th><td>{{ $row['value'] === null ? 'Belum ada data' : $row['value'] }}</td></tr>@endforeach
    </tbody></table></div></details>
    @endif
    <footer class="insight-footer"><span class="insight-key"></span>{{ $chart['unit'] === 'poin' ? 'Hijau: mencapai KKM · Merah: di bawah KKM. Nilai nol tetap ditampilkan.' : ($chart['unit'] === '%' ? 'Dihitung per rekaman pelajaran, bukan jumlah hari atau siswa unik.' : 'Berdasarkan data yang tersimpan di sistem.') }}</footer>
</article>
@endforeach
</section>
