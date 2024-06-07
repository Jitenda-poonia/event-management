@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <h2>Create Event</h2>
    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Event Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                    value="{{ old('start_date') }}">
                @error('start_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                @error('end_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="organizer" class="form-label">Organizer</label>
            <input type="text" class="form-control" id="organizer" name="organizer" value="{{ old('organizer') }}">
            @error('organizer')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <h4>Tickets</h4>
        @error('tickets')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <button type="button" id="add-ticket" class="btn btn-secondary mb-3">Add New Ticket</button>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ticket No</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tickets-table">
                @foreach (old('tickets', []) as $index => $ticket)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="ticket-no-display" style="display:none;"></span>
                            <input type="number" class="form-control ticket-no-input"
                                name="tickets[{{ $index }}][ticket_no]" value="{{ $ticket['ticket_no'] }}">
                            @error("tickets.{$index}.ticket_no")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <span class="price-display" style="display:none;"></span>
                            <input type="number" class="form-control price-input"
                                name="tickets[{{ $index }}][price]" step="0.01" value="{{ $ticket['price'] }}">
                            @error("tickets.{$index}.price")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <button type="button" class="btn btn-success save-ticket">Save</button>
                            <button type="button" class="btn btn-warning edit-ticket" style="display:none;">Edit</button>
                            <button type="button" class="btn btn-danger remove-ticket">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary mt-3">Save Event</button>
    </form>

    <script>
        let ticketIndex = {{ count(old('tickets', [])) }};

        function toggleEditSave(row, mode) {
            const displayMode = (mode === 'edit') ? 'none' : '';
            const editMode = (mode === 'edit') ? '' : 'none';

            row.find('.ticket-no-display').css('display', displayMode);
            row.find('.ticket-no-input').css('display', editMode);
            row.find('.price-display').css('display', displayMode);
            row.find('.price-input').css('display', editMode);
            row.find('.edit-ticket').css('display', displayMode);
            row.find('.save-ticket').css('display', editMode);
        }

        $('#add-ticket').click(function() {
            $('#tickets-table').append(`
                <tr>
                    <td>${ticketIndex + 1}</td>
                    <td>
                        <span class="ticket-no-display" style="display:none;"></span>
                        <input type="number"  class="form-control ticket-no-input" name="tickets[${ticketIndex}][ticket_no]" value="">
                    </td>
                    <td>
                        <span class="price-display" style="display:none;"></span>
                        <input type="number" class="form-control price-input" name="tickets[${ticketIndex}][price]" step="0.01" value="">
                    </td>
                    <td>
                        <button type="button" class="btn btn-success save-ticket">Save</button>
                        <button type="button" class="btn btn-warning edit-ticket" style="display:none;">Edit</button>
                        <button type="button" class="btn btn-danger remove-ticket">Delete</button>
                    </td>
                </tr>
            `);
            ticketIndex++;
        });

        $(document).on('click', '.remove-ticket', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.edit-ticket', function() {
            const row = $(this).closest('tr');
            toggleEditSave(row, 'edit');
        });

        $(document).on('click', '.save-ticket', function() {
            const row = $(this).closest('tr');
            const ticketNoInput = row.find('.ticket-no-input');
            const priceInput = row.find('.price-input');
            const ticketNo = ticketNoInput.val();
            const price = priceInput.val();

            // Validate ticket number and price
            if (!ticketNo || !price) {
                alert('Please fill in both ticket number and price.');
                return;
            }

            if (ticketNo.length < 5) {
                alert('Ticket number must be at least 5 digits long.');
                return;
            }

            row.find('.ticket-no-display').text(ticketNo).show();
            row.find('.price-display').text(price).show();
            ticketNoInput.hide();
            priceInput.hide();
            row.find('.save-ticket').hide();
            row.find('.edit-ticket').show();
        });
    </script>
@endsection
