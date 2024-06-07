@extends('layouts.app')

@section('title', 'Event List')

@section('content')
    <h2>Events</h2>
    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">Create New Event</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Organizer</th>
                <th>Tickets</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $key => $event)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->description }}</td>
                    <td>{{ $event->start_date }}</td>
                    <td>{{ $event->end_date }}</td>
                    <td>{{ $event->organizer }}</td>
                    <td>
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
                    </td>
                    <td>
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-warning">view</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
