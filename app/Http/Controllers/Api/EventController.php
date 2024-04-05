<?php

namespace App\Http\Controllers\Api;

use App\Events\EventStored;
use App\Events\EventUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\Event\EventResource;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\CacheTrait;


class EventController extends Controller
{
    use CacheTrait;

    private array $relationships = ['user', 'attendees', 'attendees.user'];

    public function __construct() {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->authorizeResource(Event::class);
    }

    public function index()
    { 
        $events =  Event::with($this->relationships[0])
        ->orderBy('id', 'desc');

        $cached = $this->cacheData('all-events', 3600*24*30, $events->get());
        
        return EventResource::collection($cached);
    }

    public function store(StoreEventRequest $request)
    {

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $event = Event::create($data);

        event(new EventStored($event));

        return new EventResource($event->load($this->relationships[0]));

    }

    public function show(Event $event)
    {
        return new EventResource($event->load($this->relationships));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {

        // $message = 'update this event';
        // $this->checkAndUpdatePermission('update-event', $event, $message);

        $event->update($request->all());

        event(new EventUpdated($event));

        return new EventResource($event->load($this->relationships));
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->noContent();

        // return response()->json([
        //     'message' => 'Event deleted succesffuly'
        // ]);
    }

}
