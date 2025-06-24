<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCategoryRequest;
use App\Http\Resources\EventCategoryResource;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventCategoryController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return EventCategoryResource::collection(EventCategory::latest()->paginate(10));
    }

    public function store(EventCategoryRequest $request): EventCategoryResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
            ) {
            return response()->json(['errors' => __('Unauthorized')], 403);
        }

        try {
            $eventCategory = EventCategory::create($request->validated());
            return new EventCategoryResource($eventCategory);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => __('There is an error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(EventCategory $eventCategory): EventCategoryResource
    {
        return EventCategoryResource::make($eventCategory);
    }

    public function update(EventCategoryRequest $request, EventCategory $eventCategory): EventCategoryResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
            ) {
            return response()->json(['errors' => __('Unauthorized')], 403);
        }
        
        try {
            $eventCategory->update($request->validated());
            return new EventCategoryResource($eventCategory);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => __('There is an error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(EventCategory $eventCategory): \Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
            ) {
            return response()->json(['errors' => __('Unauthorized')], 403);
        }

        try {
            $eventCategory->delete();
            return response()->json(['message' => __('Deleted successfully')], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => __('There is an error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
