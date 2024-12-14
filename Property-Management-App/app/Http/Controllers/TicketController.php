<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->get();
        return view('tickets.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tickets', 'public');
        }

        $validated['user_id'] = auth()->id();
        $ticket = Ticket::create($validated);

        // Send email with Resolved button
        $resolveUrl = route('tickets.resolve', ['id' => $ticket->id]);
        $emailContent = "
            <p>New support ticket received:</p>
            <p>Subject: {$ticket->subject}</p>
            <p>Priority: {$ticket->priority}</p>
            <p>Message: {$ticket->message}</p>
            <br>
            <a href='{$resolveUrl}' style='padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px;'>Mark as Resolved</a>
        ";

        // Use Mail::html to send HTML content in the email body
        Mail::html($emailContent, function ($message) {
            $message->to('harakehadi360@gmail.com')
                    ->subject('New Support Ticket');
        });

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted successfully.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function show($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

     // Method to resolve the ticket
     public function resolve($id)
     {
         // Find the ticket by ID
         $ticket = Ticket::findOrFail($id);
 
         // Update the status to 'Resolved'
         $ticket->status = 'Resolved';
         $ticket->save();
 
         return redirect()->route('tickets.index')->with('success', 'Ticket marked as resolved.');
     }
}
