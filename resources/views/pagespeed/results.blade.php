<x-layout>
    <x-slot:heading>Results</x-slot:heading>
    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($results as $result)
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-xl font-semibold">
                        Website: {{ $result->website->url ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Website ID: {{ $result->website->id ?? 'N/A' }}
                    </p>
                    <div class="text-sm space-y-2 mt-4">
                        <p><strong>LCP:</strong> <span class="{{ $result->lcp === null ? 'text-gray-400' : ($result->lcp > 2.5 ? 'text-red-500' : 'text-green-500') }}">{{ $result->lcp ?? 'N/A' }}</span></p>
                        <p><strong>INP:</strong> <span class="{{ $result->inp === null ? 'text-gray-400' : ($result->inp > 200 ? 'text-red-500' : 'text-green-500') }}">{{ $result->inp ?? 'N/A' }}</span></p>
                        <p><strong>CLS:</strong> <span class="{{ $result->cls === null ? 'text-gray-400' : ($result->cls > 0.1 ? 'text-red-500' : 'text-green-500') }}">{{ $result->cls ?? 'N/A' }}</span></p>
                        <p><strong>FCP:</strong> <span class="{{ $result->fcp === null ? 'text-gray-400' : ($result->fcp > 1.8 ? 'text-red-500' : 'text-green-500') }}">{{ $result->fcp ?? 'N/A' }}</span></p>
                        <p><strong>TTFB:</strong> <span class="{{ $result->ttfb === null ? 'text-gray-400' : ($result->ttfb > 0.6 ? 'text-red-500' : 'text-green-500') }}">{{ $result->ttfb ?? 'N/A' }}</span></p>
                    </div>
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
</x-layout>
