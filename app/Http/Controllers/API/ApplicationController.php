<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Event;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ApplicationResource::collection(Application::latest()->paginate(10));
    }

    public function store(ApplicationRequest $request): ApplicationResource|\Illuminate\Http\JsonResponse
    {
        if (!Event::find($request->event_id)) {
            return response()->json(['errors' => ['Event is not found']], Response::HTTP_NOT_FOUND);
        }
        
        if (!Volunteer::find($request->volunteer_id)) {
            return response()->json(['errors' => ['Volunteer is not found']], Response::HTTP_NOT_FOUND);
        }

        if (auth()->user()->user_type != User::TYPE_ADMIN && auth()->id() != $request->volunteer_id) {
            return response()->json(['errors' => ['User is only allowed to apply for himself.']], Response::HTTP_FORBIDDEN); 
        }

        if (auth()->user()->user_type != User::TYPE_ADMIN && auth()->user()->is_approved != true) {
            return response()->json(['errors' => ['Volunteer is not approved.']], Response::HTTP_FORBIDDEN); 
        }

        $existsing_application = Application::where('volunteer_id', '=', $request->volunteer_id)
        ->where('event_id', $request->event_id)->first();

        if ($existsing_application) {
            return response()->json(['errors' => ['Application already exists.']], Response::HTTP_NOT_ACCEPTABLE);
        }

        try {
            $application = Application::create($request->validated());
            return new ApplicationResource($application);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Application $application): ApplicationResource|\Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
            &&  
            auth()->user()->id != $application->volunteer_id //Check user is owner
            &&  
            auth()->user()->id != $application->event->organization_id //Check user is organization
            ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }
        

        return ApplicationResource::make($application);
    }

    public function update(ApplicationRequest $request, Application $application): ApplicationResource|\Illuminate\Http\JsonResponse
    {
        if (!Event::find($request->event_id)) {
            return response()->json(['errors' => ['Event is not found']], Response::HTTP_NOT_FOUND);
        }
        
        if (!Volunteer::find($request->volunteer_id)) {
            return response()->json(['errors' => ['Volunteer is not found']], Response::HTTP_NOT_FOUND);
        }
        
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
            &&  
            auth()->user()->id != $application->volunteer_id //Check user is owner
            &&  
            auth()->user()->id != $application->event->organization_id //Check user is organization
            ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        if ($application->status == Application::STATUS_REJECTED) {
            return response()->json(['errors' => ['You cannot modify a rejected application.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $application->update($request->validated());

            if ($request->has('is_approved') && $request->is_approved) {
                $application->approve();
            }

            if ($request->has('is_approved') && !$request->is_approved) {
                $application->reject();
            }

            return new ApplicationResource($application);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Application $application): \Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type != User::TYPE_ADMIN  //Check user if admin
            &&  
            auth()->user()->id != $application->volunteer_id //Check user is owner
            ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $application->delete();
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
