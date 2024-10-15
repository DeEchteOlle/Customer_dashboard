<x-layout>
    <x-slot:heading>websites</x-slot:heading>
    @foreach($websites as $website)
        <div>
            <h3>{{ $website->name }}</h3>
            <p>{{ $website->description }}</p>
            <a href="{{ url('websites/'.$website->id.'/edit') }}">Edit</a>
            <form action="{{ url('websites/'.$website->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    @endforeach

    <a href="{{ url('websites/create') }}">Create</a>
</x-layout>
