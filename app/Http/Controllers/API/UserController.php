<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Termwind\Components\Ul;

class UserController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection(User::latest()->paginate(10));
    }

    public function store(UserRequest $request): UserResource|\Illuminate\Http\JsonResponse
    {
        if ($request->user_type == User::TYPE_ADMIN) {
            return response()->json(['errors' => __('You are not allowed to create an admin user')], 403);
        }

        try {
            $user = User::create($request->validated());

            return new UserResource($user);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => __('There is an error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function update(UserRequest $request, User $user): UserResource|\Illuminate\Http\JsonResponse
    {
        if (auth()->user()->user_type != User::TYPE_ADMIN &&  auth()->id() != $user->id) {
            return response()->json(['errors' => __('Unauthorized')], 403);
        }

        try {

            $updates = $request->validated();
            if (auth()->user()->user_type != User::TYPE_ADMIN) {
                //Allow to update self and minimal fields only
                $allowed_keys = ['name', 'email', 'password', 'skills', 'details', 'profile_photo_base64', 'address', 'phone'];
                $allowed_updates = [];

                foreach ($updates as $update_key => $update_value) {
                    if (!in_array($update_key, $allowed_keys)) {
                        return response()->json(['errors' => __('Unauthorized')], 403);
                    };

                    $allowed_updates[$update_key] = $update_value;
                }

                $user->update($allowed_updates);
                return new UserResource($user);
            }

            //Admin update, allow all updates
            $user->update($request->validated());
            return new UserResource($user);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => __('There is an error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->user_type != User::TYPE_ADMIN) {
            return response()->json(['errors' => __('Unauthorized')], 403);
        }

        try {
            $user->delete();
            return response()->json(['message' => __('Deleted successfully')], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => __('There is an error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function profile_photo(User $user)
    {
        return $user->profile_photo();
    }
}
