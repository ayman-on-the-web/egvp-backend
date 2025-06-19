<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return EventResource::collection(Event::latest()->paginate(10));
    }

    public function store(EventRequest $request): EventResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
            &&
            auth()->user()->user_type !== User::TYPE_ORGANIZATION  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $event = Event::create($request->validated());
            return new EventResource($event);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Event $event): EventResource
    {
        return EventResource::make($event);
    }

    public function update(EventRequest $request, Event $event): EventResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $event->update($request->validated());
            return new EventResource($event);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Event $event): \Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }
        
        try {
            $event->delete();
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
