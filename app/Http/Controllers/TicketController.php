<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\UsersMeta;
use App\Service\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Validator;


class TicketController extends Controller
{
    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        return $this->ticketService->index();
    }

    public function create()
    {
        //
    }

    public function store(StoreTicketRequest $request)
    {
        return $this->ticketService->store($request->all());
    }

    public function show(Ticket $ticket)
    {
        //
    }

    public function edit(Ticket $ticket)
    {
        //
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    public function destroy(Ticket $ticket)
    {
        //
    }

    public function assignTicketToSupport($ticket_id)
    {
        return $this->ticketService->assignTicketToSupport($ticket_id);
    }

    public function closeTicket($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        $usersMeta = UsersMeta::query()->with('role')->where('user_id', Auth::id())->first();

        if ($usersMeta->role->name == 'support' && $usersMeta->user_id != $ticket->assigned_support_id) {
            return response()->json(['error' => 'Siz bu tiketni yopolmaysiz'], 403);
        }

        $ticket->status = 'closed';
        $ticket->save();

        return response()->json(['message' => 'Tiket yopildi', 'data' => $ticket]);
    }

}
