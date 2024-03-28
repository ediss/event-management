<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\Event\EventResource;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private array $relationships = ['user', 'attendees', 'attendees.user'];

    public function __construct() {
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $events =  Event::with($this->relationships)->get();

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $event = Event::create($data);

        $event->load($this->relationships);

        return new EventResource($event);

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load($this->relationships);
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {

        // $message = 'update this event';
        // $this->checkAndUpdatePermission('update-event', $event, $message);
        // $this->authorize('update-event', $event);

        $event->update($request->all());

        $event->load($this->relationships);

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->noContent();

        // return response()->json([
        //     'message' => 'Event deleted succesffuly'
        // ]);
    }

}
