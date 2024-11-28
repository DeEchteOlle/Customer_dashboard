<x-layout>
    <x-slot:heading>Results</x-slot:heading>
    <body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">PageSpeed Results</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Iterate through results -->
            @foreach ($results as $result)
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Website URL -->
                    <p class="text-xl font-semibold">
                        Website: {{ $result->website->url ?? 'N/A' }}
                    </p>
                    <!-- Website ID -->
                    <p class="text-sm text-gray-500">
                        Website ID: {{ $result->website->id ?? 'N/A' }}
                    </p>
                    <!-- Metrics -->
                    <div class="text-sm space-y-2 mt-4">
                        <p><strong>LCP:</strong> <span class="{{ $result->lcp > 2.5 ? 'text-red-500' : 'text-green-500' }}">{{ $result->lcp }}</span></p>
                        <p><strong>INP:</strong> <span class="{{ $result->inp > 200 ? 'text-red-500' : 'text-green-500' }}">{{ $result->inp }}</span></p>
                        <p><strong>CLS:</strong> <span class="{{ $result->cls > 0.1 ? 'text-red-500' : 'text-green-500' }}">{{ $result->cls }}</span></p>
                        <p><strong>FCP:</strong> <span class="{{ $result->fcp > 1.8 ? 'text-red-500' : 'text-green-500' }}">{{ $result->fcp }}</span></p>
                        <p><strong>TTFB:</strong> <span class="{{ $result->ttfb > 0.6 ? 'text-red-500' : 'text-green-500' }}">{{ $result->ttfb }}</span></p>
                    </div>
                    <!-- View Details Link -->
                    @if ($result->website)
                        <a href="{{ route('websites.results', $result->website->id) }}"
                           class="text-blue-500 hover:underline mt-4 block">
                            View Details
                        </a>
                    @else
                        <p class="text-gray-500 mt-4">No details available</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    </body>
</x-layout>
