@extends('layouts.app')

@section('title', 'Event Details')

@section('content')
    <h2>{{ $event->name }}</h2>
    <p> Description : {{ $event->description }}</p>
    <p>Start Date: {{ $event->start_date }}</p>
    <p>End Date: {{ $event->end_date }}</p>
    <p>Organizer: {{ $event->organizer }}</p>

    <h4>Tickets</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket No</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($event->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_no }}</td>
                    <td>{{ $ticket->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
    <a href="{{ route('events.index') }}" class="btn btn-primary">Back to Events</a>
@endsection
