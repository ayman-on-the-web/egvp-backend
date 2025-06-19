<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Event;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return RatingResource::collection(Rating::latest()->paginate(10));
    }

    public function store(RatingRequest $request): RatingResource|\Illuminate\Http\JsonResponse
    {
        $event = Event::find($request->event_id);

        if (!$event) {
            return response()->json(['errors' => ['Event is not found']], Response::HTTP_NOT_FOUND);
        }

        if (
            !$event->volunteers->contins(auth()->user()->id)
            ||
            auth()->user()->id != $request->volunteer_id
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }
        
        try {
            $rating = Rating::create($request->validated());
            return new RatingResource($rating);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Rating $rating): RatingResource
    {
        return RatingResource::make($rating);
    }

    public function update(RatingRequest $request, Rating $rating): RatingResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $rating->update($request->validated());
            return new RatingResource($rating);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Rating $rating): \Illuminate\Http\JsonResponse
    {
        try {
            $rating->delete();
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
