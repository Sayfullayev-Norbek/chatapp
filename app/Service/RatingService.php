<?php

namespace App\Service;

use App\Events\RatingSent;
use App\Models\Rating;
use App\Models\UsersMeta;
use Illuminate\Support\Facades\Auth;

class RatingService
{
    public function store($request)
    {
        $user = Auth::user();

        $usersMeta = UsersMeta::query()
            ->where('user_id', $user->id)
            ->whereHas('tickets', function ($query) use ($request) {
                $query->where('id', $request->ticket_id);
            })
            ->with(['tickets' => function ($query) use ($request) {
                $query->where('id', $request->ticket_id);
            }])
            ->first();

        if (!$usersMeta) {
            return response()->json([
                'message' => 'Foydalanuvchi yoki ticket topilmadi'
            ], 404);
        }

        $ticket = $usersMeta->tickets->first();

        if ($ticket && $ticket->status == 'closed') {
            $rating = Rating::updateOrCreate(
                [
                    'ticket_id' => $ticket->id,
                    'users_meta_id' => $usersMeta->id,
                ],
                [
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]
            );
            
            broadcast(new RatingSent($rating))->toOthers();

            return response()->json([
                'rating' => $rating
            ], 201);
        }

        return response()->json([
            'message' => "Siz bu ticketni baho qo'ya olmaysiz"
        ], 403);
    }

    public function index()
    {
        $user = Auth::user();

        $usersMeta = UsersMeta::query()->with('ratings')->where('user_id', $user->id)->first();

        if(!$usersMeta){
            return response()->json('Sizda baholangan reting yuq!');
        }

        $ratings = [];
        foreach($usersMeta->ratings as $rating){
            $ratings[] =$rating;
        }

        return response()->json([
            'ratings'=>$ratings
        ]);
    }

    public function show($ticket_id)
    {
        $user = Auth::user();

        $usersMeta = UsersMeta::query()->where('user_id', $user->id)->first();

        if (!$usersMeta) {
            return response()->json(['message' => 'Sizda baholangan reting yuq!'], 404);
        }

        $rating = $usersMeta->ratings()->where('ticket_id', $ticket_id)->first();

        if (!$rating) {
            return response()->json(['message' => 'Bu ticket bo\'yicha rating topilmadi'], 404);
        }

        return response()->json(['rating' => $rating]);
    }

}

?>
