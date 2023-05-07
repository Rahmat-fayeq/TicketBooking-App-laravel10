<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\User;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tickets = Ticket::all();
        $user = auth()->user();

        return view('ticket.index', [
            'tickets' => $user->isAdmin ? Ticket::latest()->get()  : $user->tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);


        if ($request->file('attachment')) {
            $this->storeFile($request, $ticket);
        }



        return redirect()->route('ticket.index')->with('message', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('ticket.show', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->except('attachment'));

        // notification
        if ($request->has('status')) {
            $user = User::find($ticket->user_id);
            //$user->notify(new TicketUpdatedNotification($ticket));
            // for preview purposes
            return (new TicketUpdatedNotification($ticket))->toMail($user);
        }

        $prevAttachment = $ticket->attachment;
        if ($request->file('attachment')) {
            if (!is_null($prevAttachment)) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $this->storeFile($request, $ticket);
        }

        return redirect()->route('ticket.index')->with('message', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $prevAttachment = $ticket->attachment;
        if (!is_null($prevAttachment)) {
            Storage::disk('public')->delete($ticket->attachment);
        }
        $ticket->delete();
        return redirect()->route('ticket.index')->with('message', 'Ticket has been deleted');
    }

    protected function storeFile($request, $ticket)
    {
        $ext = $request->file('attachment')->extension();
        $contents = file_get_contents($request->file('attachment'));
        $fileName = Str::random(25);
        $path = "attachments/$fileName.$ext";
        Storage::disk('public')->put($path, $contents);
        $ticket->update(['attachment' => $path]);
    }
}
