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
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
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
        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
            ||
            (auth()->user()->user_type !== User::TYPE_ORGANIZATION  && auth()->user()->id !== $organization->id)
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
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
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
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
