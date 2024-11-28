<x-layout>
    <x-slot:heading>Website Performance Details</x-slot:heading>
    <body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto py-8">
        <!-- Website Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold">{{ $website->url }}</h2>
                <p class="text-sm text-gray-500">Performance metrics for {{ $website->name }}</p>
            </div>

            <!-- Metrics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold">Largest Contentful Paint (LCP)</h3>
                    <p class="text-gray-700 mt-2">
                        Value:
                        <span class="{{ $website->lcp > 2.5 ? 'text-red-500' : 'text-green-500' }}">
                            {{ $website->lcp }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Recommended: &lt; 2.5s</p>
                </div>

                <div class="p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold">Interaction to Next Paint (INP)</h3>
                    <p class="text-gray-700 mt-2">
                        Value:
                        <span class="{{ $website->inp > 200 ? 'text-red-500' : 'text-green-500' }}">
                            {{ $website->inp }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Recommended: &lt; 200ms</p>
                </div>

                <div class="p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold">Cumulative Layout Shift (CLS)</h3>
                    <p class="text-gray-700 mt-2">
                        Value:
                        <span class="{{ $website->cls > 0.1 ? 'text-red-500' : 'text-green-500' }}">
                            {{ $website->cls }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Recommended: &lt; 0.1</p>
                </div>

                <div class="p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold">First Contentful Paint (FCP)</h3>
                    <p class="text-gray-700 mt-2">
                        Value:
                        <span class="{{ $website->fcp > 1.8 ? 'text-red-500' : 'text-green-500' }}">
                            {{ $website->fcp }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Recommended: &lt; 1.8s</p>
                </div>

                <div class="p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold">Time to First Byte (TTFB)</h3>
                    <p class="text-gray-700 mt-2">
                        Value:
                        <span class="{{ $website->ttfb > 0.6 ? 'text-red-500' : 'text-green-500' }}">
                            {{ $website->ttfb }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Recommended: &lt; 0.6s</p>
                </div>
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

