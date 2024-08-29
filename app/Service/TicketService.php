<?php

namespace App\Service;

use App\Events\TicketSent;
use App\Models\Ticket;
use App\Models\UsersMeta;
use Illuminate\Support\Facades\Auth;

class TicketService{

    public function index()
    {
        $user = Auth::user();

        $usersMeta = UsersMeta::where('user_id', $user->id)->first();

        if (!$usersMeta) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $tickets = $usersMeta->tickets()->with(['messages.file', 'ratings'])->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function store($request)
    {
        $usersMeta = UsersMeta::with('role')->where('user_id', Auth::id())->first();
        $role_name = $usersMeta->role->name;
        //dd($role_name);

        if (!$usersMeta) {
            return response()->json(['message' => 'User not found for the authenticated user'], 404);
        }

        // Ticketni yaratish
        $ticket = Ticket::create([
            'users_meta_id' => $usersMeta->id,
            'subject' => $request['subject'],
            'description' => $request['description'],
        ]);

        broadcast(new TicketSent($ticket))->toOthers();

        return response()->json([ 'data' => $ticket ], 201);
    }

    public function assignTicketToSupport($ticket_id)
    {
        $user = Auth::user();

        $ticket = Ticket::findOrFail($ticket_id);

        if (!$ticket) {
            return response()->json(['error' => 'Bu tiket yuq'], 404);
        }

        if ($ticket->assigned_support_id) {
            return response()->json(['error' => 'Bu tiket allaqachon biriktirilgan'], 403);
        }

        $supportMeta = UsersMeta::where('user_id', $user->id)->first();

        if (!$supportMeta) {
            return response()->json(['error' => 'Berilgan support ID noto\'g\'ri'], 404);
        }

        $ticket->assigned_support_id = $user->id;
        $ticket->status = 'reviewed';
        $ticket->save();

        return response()->json([
            'message' => 'Tiket supportga biriktirildi',
            'data' => $ticket
        ]);
    }

}

?>
