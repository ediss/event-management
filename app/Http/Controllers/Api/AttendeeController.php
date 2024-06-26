<?php

namespace App\Http\Controllers\Api;

use App\Events\AttendeeStored;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendee\StoreAttendeeRequest;
use App\Http\Resources\Attendee\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;

class AttendeeController extends Controller
{

    public function __construct() {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();

        return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendeeRequest $request, Event $event)
    {

        $this->authorize('add-attendee', [$event, $request->user_id]);


        $attendee = $event->attendees()->create([
            'user_id' => $request->user_id
        ]);

        event(new AttendeeStored());

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();

        return response()->noContent();
    }
}
