<x-layout>
    <x-slot:heading>Website Performance Details</x-slot:heading>
    <div class="container mx-auto py-8 space-y-8">

        @php
            $latest = $pagespeedResults->last();
            $score  = $latest?->healthScore();
            $scoreColor = $score === null ? 'text-gray-400' : ($score >= 90 ? 'text-green-500' : ($score >= 50 ? 'text-yellow-500' : 'text-red-500'));
            $metrics = [
                'LCP'  => ['value' => $latest?->lcp,  'threshold' => 2.5,  'unit' => 's',  'key' => 'lcp'],
                'INP'  => ['value' => $latest?->inp,  'threshold' => 200,  'unit' => 'ms', 'key' => 'inp'],
                'CLS'  => ['value' => $latest?->cls,  'threshold' => 0.1,  'unit' => '',   'key' => 'cls'],
                'FCP'  => ['value' => $latest?->fcp,  'threshold' => 1.8,  'unit' => 's',  'key' => 'fcp'],
                'TTFB' => ['value' => $latest?->ttfb, 'threshold' => 0.6,  'unit' => 's',  'key' => 'ttfb'],
            ];
        @endphp

        {{-- ── Header met acties ── --}}
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl font-bold">{{ $website->name }}</h2>
                    <p class="text-sm text-gray-400">{{ $website->url }}</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Health score badge --}}
                    @if ($score !== null)
                        <div class="text-center">
                            <p class="text-3xl font-bold {{ $scoreColor }}">{{ $score }}</p>
                            <p class="text-xs text-gray-500">/ 100</p>
                        </div>
                    @endif

                    {{-- Scan-knop --}}
                    <form method="POST" action="{{ route('websites.scan', $website->id) }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                            Scan nu
                        </button>
                    </form>

                    {{-- CSV export --}}
                    <a href="{{ route('websites.export', $website->id) }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">
                        Export CSV
                    </a>
                </div>
            </div>

            {{-- Succes/fout melding --}}
            @if (session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            {{-- Desktop / Mobiel tabs --}}
            <div class="flex gap-2 mt-6 border-b">
                <a href="{{ route('websites.results', [$website->id, 'strategy' => 'desktop']) }}"
                   class="px-4 py-2 text-sm font-medium {{ $strategy === 'desktop' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Desktop
                </a>
                <a href="{{ route('websites.results', [$website->id, 'strategy' => 'mobile']) }}"
                   class="px-4 py-2 text-sm font-medium {{ $strategy === 'mobile' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Mobiel
                </a>
            </div>

            {{-- Metric kaarten --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                @foreach ($metrics as $name => $data)
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <h3 class="text-lg font-semibold">{{ $name }}</h3>
                        <p class="text-2xl font-bold mt-1
                            {{ $data['value'] === null ? 'text-gray-400' : ($data['value'] > $data['threshold'] ? 'text-red-500' : 'text-green-500') }}">
                            {{ $data['value'] !== null ? $data['value'] . $data['unit'] : 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Drempel: &lt; {{ $data['threshold'] }}{{ $data['unit'] }}</p>
                        <p class="text-sm font-medium mt-1
                            {{ $data['value'] === null ? 'text-gray-400' : ($data['value'] > $data['threshold'] ? 'text-red-500' : 'text-green-500') }}">
                            {{ $data['value'] === null ? '–' : ($data['value'] > $data['threshold'] ? 'Slecht' : 'Goed') }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ url('/') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Terug naar overzicht
                </a>
            </div>
        </div>

        {{-- ── Grafieken over tijd ── --}}
        @if ($pagespeedResults->count() > 1)
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
                <h2 class="text-xl font-bold mb-6">Performance over tijd — {{ ucfirst($strategy) }} (laatste {{ $pagespeedResults->count() }} metingen)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($metrics as $name => $data)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 mb-2">{{ $name }}</h3>
                            <canvas id="chart-{{ $data['key'] }}"></canvas>
                        </div>
                    @endforeach
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
            <script>
                const labels = @json($pagespeedResults->pluck('created_at')->map(fn($d) => $d->format('d M H:i')));

                function makeChart(id, values, threshold, label, unit) {
                    const ctx = document.getElementById('chart-' + id);
                    if (!ctx) return;
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label,
                                    data: values,
                                    borderColor: 'rgb(59,130,246)',
                                    backgroundColor: 'rgba(59,130,246,0.1)',
                                    tension: 0.3,
                                    fill: true,
                                    pointRadius: 4,
                                },
                                {
                                    label: 'Drempel (' + threshold + unit + ')',
                                    data: Array(labels.length).fill(threshold),
                                    borderColor: 'rgb(239,68,68)',
                                    borderDash: [6, 3],
                                    pointRadius: 0,
                                    fill: false,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } },
                            scales: { y: { beginAtZero: true, ticks: { callback: v => v + unit } } }
                        }
                    });
                }

                makeChart('lcp',  @json($pagespeedResults->pluck('lcp')),  2.5,  'LCP',  's');
                makeChart('inp',  @json($pagespeedResults->pluck('inp')),  200,  'INP',  'ms');
                makeChart('cls',  @json($pagespeedResults->pluck('cls')),  0.1,  'CLS',  '');
                makeChart('fcp',  @json($pagespeedResults->pluck('fcp')),  1.8,  'FCP',  's');
                makeChart('ttfb', @json($pagespeedResults->pluck('ttfb')), 0.6,  'TTFB', 's');
            </script>
        @else
            <p class="text-center text-gray-500 text-sm">Er zijn minimaal 2 metingen nodig om grafieken te tonen.</p>
        @endif

        {{-- ── Historietabel ── --}}
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Alle metingen — {{ ucfirst($strategy) }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Datum</th>
                            <th class="px-4 py-3">Score</th>
                            <th class="px-4 py-3">LCP (s)</th>
                            <th class="px-4 py-3">INP (ms)</th>
                            <th class="px-4 py-3">CLS</th>
                            <th class="px-4 py-3">FCP (s)</th>
                            <th class="px-4 py-3">TTFB (s)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($allResults as $row)
                            @php $s = $row->healthScore(); @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $row->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 font-bold {{ $s === null ? 'text-gray-400' : ($s >= 90 ? 'text-green-500' : ($s >= 50 ? 'text-yellow-500' : 'text-red-500')) }}">
                                    {{ $s ?? '–' }}
                                </td>
                                @foreach ([[$row->lcp, 2.5], [$row->inp, 200], [$row->cls, 0.1], [$row->fcp, 1.8], [$row->ttfb, 0.6]] as [$value, $threshold])
                                    <td class="px-4 py-3 font-medium
                                        {{ $value === null ? 'text-gray-400' : ($value > $threshold ? 'text-red-500' : 'text-green-500') }}">
                                        {{ $value ?? '–' }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Nog geen metingen beschikbaar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layout>