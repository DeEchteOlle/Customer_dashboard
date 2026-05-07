<x-layout>
    <x-slot:heading>Login</x-slot:heading>
    <body class="bg-gray-100 text-gray-900">
    <div class="sm:col-span-4 flex justify-center items-start min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md sm:max-w-md w-full mt-6">
            <h2 class="text-2xl font-semibold mb-4 text-center">Login to Your Account</h2>
            <form method="POST" action="/login">
                @csrf
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                            <input type="email" name="email" id="email" placeholder="you@example.com"
                                   class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" required>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                   class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" required>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                        Login
                    </button>
                </div>
                <div class="py-2 flex items-center justify-center">
                    <h3> Or </h3>
                </div>
                <div class="">
                    <a href="{{ url('register') }}">
                        <button type="button"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                            register
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </div>
    </body>
</x-layout>
