<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Ticket;
use App\Models\UsersMeta;
use App\Service\RatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    private $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index()
    {
        return $this->ratingService->index();
    }

    public function create()
    {

    }

    public function store(StoreRatingRequest $request)
    {
        return $this->ratingService->store($request);
    }

    public function show($ticket_id)
    {
        return $this->ratingService->show($ticket_id);
    }

    public function edit(Rating $rating)
    {
        //
    }

    public function destroy(Rating $rating)
    {
        //
    }
}
