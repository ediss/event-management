<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\Attendee\AttendeeResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'event_owner' => new UserResource($this->whenLoaded('user')),
            'attendees' =>  AttendeeResource::collection($this->whenLoaded('attendees'))
            
        ];
    }
}
