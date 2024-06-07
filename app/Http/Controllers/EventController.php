<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('tickets')->latest()->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer' => 'required',
            'tickets' => 'required|array',
            'tickets.*.ticket_no' => 'required|integer|digits_between:5,10',
            'tickets.*.price' => 'required|numeric',
        ], [
            'tickets.*.ticket_no.digits_between' => 'Ticket number must be at least 5 digits long.',
            'tickets.*.ticket_no.required' => 'Ticket number is required.',
            'tickets.*.price.required' => 'Ticket price is required.'
        ]);


        try {
            $event = Event::create($request->all());
            foreach ($request->tickets as $ticket) {
                $event->tickets()->create($ticket);
            }
            return redirect()->route('events.index')->with('success', 'Event created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create event. Please ensure all fields are filled out correctly and try again.')->withInput();
        }
    }

    public function show($id)
    {
        $event = Event::with('tickets')->findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::with('tickets')->findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer' => 'required',
            'tickets' => 'required|array',
            'tickets.*.ticket_no' => 'required|integer|digits_between:5,10',
            'tickets.*.price' => 'required|numeric',
        ], [
            'tickets.*.ticket_no.digits_between' => 'Ticket number must be at least 5 digits long.',
            'tickets.*.ticket_no.required' => 'Ticket number is required.',
            'tickets.*.price.required' => 'Ticket price is required.'
        ]);

        try {
            $event = Event::findOrFail($id);
            $event->update($request->all());
            $event->tickets()->delete();
            foreach ($request->tickets as $ticket) {
                $event->tickets()->create($ticket);
            }
            return redirect()->route('events.index')->with('success', 'Event updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update event. Please ensure all fields are filled out correctly and try again.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->tickets()->delete();
            $event->delete();
            return redirect()->route('events.index')->with('success', 'Event deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', 'Failed to delete event');
        }
    }
}
