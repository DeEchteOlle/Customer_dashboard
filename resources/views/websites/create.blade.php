<x-layout>
    <x-slot:heading>Create</x-slot:heading>
    <form action="{{ url('websites') }}" method="POST">
        @csrf
        <input type="Url" name="name" placeholder="Url" required>
        <button type="submit">Create Website</button>
    </form>
</x-layout>
