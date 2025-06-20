<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return OrganizationResource::collection(Organization::latest()->paginate(10));
    }

    public function store(OrganizationRequest $request): OrganizationResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $organization = Organization::create($request->validated());
            return new OrganizationResource($organization);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Organization $organization): OrganizationResource
    {
        return OrganizationResource::make($organization);
    }

    public function update(OrganizationRequest $request, Organization $organization): OrganizationResource|\Illuminate\Http\JsonResponse
    {
        if (auth()->user()->user_type != User::TYPE_ADMIN &&  auth()->id() != $organization->id) {
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

                $organization->update($allowed_updates);
                return new OrganizationResource($organization);
            }

            //Admin update, allow all updates
            $organization->update($request->validated());
            return new OrganizationResource($organization);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Organization $organization): \Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $organization->delete();
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
