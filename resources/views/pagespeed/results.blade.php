<x-layout>
    <x-slot:heading>Results</x-slot:heading>
    <x-slot:actions>
        <form method="POST" action="{{ route('pagespeed.runAll') }}">
            @csrf
            <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                Scan alle websites
            </button>
        </form>
    </x-slot:actions>
    <div class="container mx-auto py-8">

        @if (session('success'))
            <div class="mb-6 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        @if ($websites->isEmpty())
            <p class="text-center text-gray-500">Nog geen websites toegevoegd. <a href="{{ url('websites/create') }}" class="text-blue-500 hover:underline">Voeg er één toe</a>.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($websites as $website)
                    @php $result = $website->latestPagespeedResult; @endphp
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-xl font-semibold truncate">{{ $website->name }}</p>
                        <p class="text-sm text-gray-400 truncate mb-4">{{ $website->url }}</p>

                        @if ($result)
                            @php $score = $result->healthScore(); @endphp
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-2xl font-bold {{ $score === null ? 'text-gray-400' : ($score >= 90 ? 'text-green-500' : ($score >= 50 ? 'text-yellow-500' : 'text-red-500')) }}">
                                    {{ $score ?? '–' }}
                                </span>
                                <span class="text-xs text-gray-400">/ 100</span>
                            </div>
                            <div class="text-sm space-y-2">
                                @foreach ([
                                    'LCP'  => [$result->lcp,  2.5,  's'],
                                    'INP'  => [$result->inp,  200,  'ms'],
                                    'CLS'  => [$result->cls,  0.1,  ''],
                                    'FCP'  => [$result->fcp,  1.8,  's'],
                                    'TTFB' => [$result->ttfb, 0.6,  's'],
                                ] as $name => [$value, $threshold, $unit])
                                    <p>
                                        <strong>{{ $name }}:</strong>
                                        <span class="{{ $value === null ? 'text-gray-400' : ($value > $threshold ? 'text-red-500' : 'text-green-500') }}">
                                            {{ $value !== null ? $value . $unit : 'N/A' }}
                                        </span>
                                    </p>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-400 mt-3">Gemeten op: {{ $result->created_at->format('d M Y H:i') }}</p>
                        @else
                            <p class="text-sm text-gray-400">Nog geen meting beschikbaar.</p>
                        @endif

                        <a href="{{ route('websites.results', $website->id) }}"
                           class="text-blue-500 hover:underline mt-4 block text-sm">
                            Bekijk geschiedenis →
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>