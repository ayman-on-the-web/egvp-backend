<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return AttendanceResource::collection(Attendance::latest()->paginate(10));
    }

    public function store(AttendanceRequest $request): AttendanceResource|\Illuminate\Http\JsonResponse
    {
        if (!Event::find($request->event_id)) {
            return response()->json(['errors' => ['Event is not found']], Response::HTTP_NOT_FOUND);
        }

        if (!Volunteer::find($request->volunteer_id)) {
            return response()->json(['errors' => ['Volunteer is not found']], Response::HTTP_NOT_FOUND);
        }

        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
            &&
            auth()->user()->id !== $request->event->organization_id //Check user is organization
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $attendance = Attendance::create($request->validated());
            return new AttendanceResource($attendance);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Attendance $attendance): AttendanceResource
    {
        return AttendanceResource::make($attendance);
    }

    public function update(AttendanceRequest $request, Attendance $attendance): AttendanceResource|\Illuminate\Http\JsonResponse
    {
        if (!Event::find($request->event_id)) {
            return response()->json(['errors' => ['Event is not found']], Response::HTTP_NOT_FOUND);
        }

        if (!Volunteer::find($request->volunteer_id)) {
            return response()->json(['errors' => ['Volunteer is not found']], Response::HTTP_NOT_FOUND);
        }

        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
            &&
            auth()->user()->id !== $request->event->organization_id //Check user is organization
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $attendance->update($request->validated());
            return new AttendanceResource($attendance);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Attendance $attendance): \Illuminate\Http\JsonResponse
    {
        if (
            auth()->user()->user_type !== User::TYPE_ADMIN  //Check user if admin
        ) {
            return response()->json(['errors' => ['Unauthorized.']], 403);
        }

        try {
            $attendance->delete();
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['errors' => ['There is an error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
