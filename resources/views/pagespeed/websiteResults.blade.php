<x-layout>
    <x-slot:heading>Website Performance Details</x-slot:heading>
    <body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold">{{ $website->url }}</h2>
                <p class="text-sm text-gray-500">Performance metrics for {{ $website->name }}</p>
            </div>

            @php
                $latest = $pagespeedResults->last();
                $metrics = [
                    'LCP'  => ['value' => $latest?->lcp,  'threshold' => 2.5,  'unit' => 's'],
                    'INP'  => ['value' => $latest?->inp,  'threshold' => 200,  'unit' => 'ms'],
                    'CLS'  => ['value' => $latest?->cls,  'threshold' => 0.1,  'unit' => ''],
                    'FCP'  => ['value' => $latest?->fcp,  'threshold' => 1.8,  'unit' => 's'],
                    'TTFB' => ['value' => $latest?->ttfb, 'threshold' => 0.6,  'unit' => 's'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($metrics as $name => $data)
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <h3 class="text-lg font-semibold">{{ $name }}</h3>
                        <p class="text-gray-700 mt-2">
                            Result:
                            <span class="{{ $data['value'] > $data['threshold'] ? 'text-red-500' : 'text-green-500' }}">
                                {{ $data['value'] > $data['threshold'] ? 'Poor' : 'Good' }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Recommended: &lt; {{ $data['threshold'] }}{{ $data['unit'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ url('/') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Back to Results
                </a>
            </div>
        </div>
    </div>
    </body>
</x-layout>
