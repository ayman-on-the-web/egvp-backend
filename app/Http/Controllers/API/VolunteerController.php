<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VolunteerRequest;
use App\Http\Resources\VolunteerResource;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VolunteerController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return VolunteerResource::collection(Volunteer::latest()->paginate(10));
    }

    public function store(VolunteerRequest $request): VolunteerResource|\Illuminate\Http\JsonResponse
    {
        if ($request->user_type == User::TYPE_ADMIN) {
            return response()->json(['errors' => ['You are not allowed to create an admin user.']], 403); 
        }
        
        try {
            $volunteer = Volunteer::create($request->validated());
            return new VolunteerResource($volunteer);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Volunteer $volunteer): VolunteerResource
    {
        return VolunteerResource::make($volunteer);
    }

    public function update(VolunteerRequest $request, Volunteer $volunteer): VolunteerResource|\Illuminate\Http\JsonResponse
    {

        if (auth()->user()->user_type != User::TYPE_ADMIN &&  auth()->id() != $volunteer->id) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {

            $updates = $request->validated();
            if (auth()->user()->user_type != User::TYPE_ADMIN) {
                //Allow to update self and minimal fields only
                $allowed_keys = ['name', 'email', 'password', 'skills', 'details', 'profile_photo_base64', 'address', 'phone'];
                $allowed_updates = [];

                foreach ($updates as $update_key => $update_value) {
                    if (!in_array($update_key, $allowed_keys)) {
                        return response()->json(['errors' => ['Unauthorized.']], 403);
                    };

                    $allowed_updates[$update_key] = $update_value;
                }

                $volunteer->update($allowed_updates);
                return new VolunteerResource($volunteer);
            }

            //Admin update, allow all updates
            $volunteer->update($request->validated());
            return new VolunteerResource($volunteer);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Volunteer $volunteer): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->user_type != User::TYPE_ADMIN) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $volunteer->delete();
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
