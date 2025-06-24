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
        header("Content-type: image/png");

        $default_base64 = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhLS0gTGljZW5zZTogQ0MwLiBNYWRlIGJ5IFNWRyBSZXBvOiBodHRwczovL3d3dy5zdmdyZXBvLmNvbS9zdmcvMjIxMDI4L3VzZXItYXZhdGFyIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTI1NiwwQzExNC44MzcsMCwwLDExNC44MzcsMCwyNTZzMTE0LjgzNywyNTYsMjU2LDI1NnMyNTYtMTE0LjgzNywyNTYtMjU2UzM5Ny4xNjMsMCwyNTYsMHogTTI1Niw0OTAuNjY3CgkJCWMtNTAuODU5LDAtOTcuODU2LTE2LjQ0OC0xMzYuMzQxLTQ0LjA1M2w1NS4xMjUtMTguMzg5YzMzLjM2NS0xMy45MDksNDQuNDgtNjQsNDQuNDgtNzYuMjI0YzAtMy4yLTEuNDI5LTYuMjA4LTMuODgzLTguMjM1CgkJCWMtMTEuOTI1LTkuODM1LTI0LjU3Ni0yNi45MDEtMjQuNTc2LTM5LjE2OGMwLTE0LjM1Ny01Ljg2Ny0yMi41MDctMTEuNTg0LTI2LjQ5NmMtMi42NjctNy4zODEtNi45NzYtMjAuODIxLTcuMzM5LTI5LjI5MQoJCQljNS4zMTItMC41OTcsOS40NTEtNS4xMiw5LjQ1MS0xMC42MDN2LTU2Ljg5NmMwLTMwLjQ0MywyOS4wNzctNzQuNjY3LDc0LjY2Ny03NC42NjdjNDIuODM3LDAsNTQuMTIzLDE4LjQ1Myw1NS41NTIsMjUuNzQ5CgkJCWMtMC4zODQsMS4zNjUtMC41MzMsMi43MDktMC40MDUsMy45MDRjMC42MTksNS43ODEsNC45NDksOC41MzMsNy4yNzUsMTAuMDA1YzMuNjY5LDIuMzI1LDEyLjI0NSw3Ljc4NywxMi4yNDUsMzUuMDI5djU2Ljg5NgoJCQljMCw1LjkwOSwyLjg1OSwxMC4wMDUsOC43NDcsMTAuMDA1YzAuMTkyLDAuMTkyLDAuNDQ4LDAuNjYxLDAuNjgzLDEuMTMxYy0wLjUxMiw4LjUxMi00LjY1MSwyMS4zOTctNy4zMTcsMjguNzM2CgkJCWMtNS42OTYsMy45ODktMTEuNTg0LDEyLjEzOS0xMS41ODQsMjYuNDk2YzAsMTIuMjY3LTEyLjY1MSwyOS4zMzMtMjQuNTc2LDM5LjE2OGMtMi40NzUsMi4wMjctMy44ODMsNS4wNTYtMy44ODMsOC4yMzUKCQkJYzAsMTIuMjAzLDExLjEzNiw2Mi4zMTUsNDUuMjI3LDc2LjQ4bDU0LjM3OSwxOC4xMzNDMzUzLjg3Nyw0NzQuMjE5LDMwNi44NTksNDkwLjY2NywyNTYsNDkwLjY2N3ogTTQwOC4yNTYsNDM0LjIxOQoJCQljLTAuOTgxLTMuMTU3LTMuMjQzLTUuODY3LTYuNjEzLTYuOTk3bC01Ni4xNDktMTguNjg4Yy0xOS42MjctOC4xNzEtMjguNzM2LTM5LjU3My0zMC44NjktNTIuMTM5CgkJCWMxNC41MjgtMTMuNTA0LDI3Ljk0Ny0zMy42MjEsMjcuOTQ3LTUxLjc5N2MwLTYuMTY1LDEuNzQ5LTguNTU1LDEuNDA4LTguNjE5YzMuMzI4LTAuODMyLDYuMDM3LTMuMiw3LjMxNy02LjM3OQoJCQljMS4wNDUtMi42MjQsMTAuMjQtMjYuMDY5LDEwLjI0LTQxLjg3N2MwLTAuODUzLTAuMTA3LTEuNzI4LTAuMzItMi41ODFjLTEuMzQ0LTUuMzU1LTQuNDgtMTAuNzUyLTkuMTczLTE0LjEyM3YtNDkuNjY0CgkJCWMwLTMwLjcyLTkuMzY1LTQzLjU2My0xOS4yNDMtNTEuMDA4Yy0yLjIxOS0xNS4yNTMtMTguNTYtNDQuOTkyLTc2Ljc1Ny00NC45OTJjLTU5LjQ3NywwLTk2LDU1LjkxNS05Niw5NnY0OS42NjQKCQkJYy00LjY5MywzLjM3MS03LjgyOSw4Ljc2OC05LjE3MywxNC4xMjNjLTAuMjEzLDAuODMyLTAuMzIsMS43MDctMC4zMiwyLjU4MWMwLDE1LjgwOCw5LjE5NSwzOS4yNTMsMTAuMjQsNDEuODc3CgkJCWMxLjI4LDMuMTc5LDIuOTY1LDUuMjA1LDYuMjkzLDYuMDM3YzAuNjgzLDAuNDA1LDIuNDMyLDIuNzczLDIuNDMyLDguOTZjMCwxOC4xNzYsMTMuNDE5LDM4LjI5MywyNy45NDcsNTEuNzk3CgkJCWMtMi4xMzMsMTIuNTY1LTExLjE1Nyw0My45MjUtMzAuMTQ0LDUxLjg2MWwtNTYuODk2LDE4Ljk2NWMtMy4zOTIsMS4xMzEtNS42NTMsMy44NjEtNi42MzUsNy4wNAoJCQlDNTMuNDE5LDM5MS4xNjgsMjEuMzMzLDMyNy4zMTcsMjEuMzMzLDI1NmMwLTEyOS4zODcsMTA1LjI4LTIzNC42NjcsMjM0LjY2Ny0yMzQuNjY3UzQ5MC42NjcsMTI2LjYxMyw0OTAuNjY3LDI1NgoJCQlDNDkwLjY2NywzMjcuMjc1LDQ1OC42MDMsMzkxLjEyNSw0MDguMjU2LDQzNC4yMTl6Ii8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg==";

        if (!$user->profile_photo_base64) {
            return base64_decode($default_base64);
        }

        return base64_decode($user->profile_photo_base64);
    }
}
