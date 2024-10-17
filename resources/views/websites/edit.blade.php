<x-layout>
    <x-slot:heading>Edit</x-slot:heading>
    <form method="POST" action="/websites/{{ $website->id }}">
        @csrf
        @method('PUT')
        <input class="p-2" type="text" name="url" value="{{ $website->url }}"><br><br>
        <button class="p-2 bg-green-600 text-white hover:bg-green-500 rounded-2xl" type="submit">Update</button>
    </form>
</x-layout>

