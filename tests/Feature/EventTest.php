<?php

namespace Tests\Feature;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $event;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->event = Event::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_get_all_events(): void
    {

        $users = User::factory()->count(200)->create();

        for($i = 0; $i < 200; $i++) {
            $user = $users->random();

            $events[] = Event::factory()->create([
                'user_id' => $user->id
            ]);
        }
        

        foreach($users as $user) {
            $eventsToAttend = collect($events)->random(rand(1, 20));
            foreach($eventsToAttend as $event) {
                
                Attendee::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ]);
            }
        }        
        
        $response = $this->getJson("api/events");

        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'start_time',
                        'end_time',
                        'event_owner' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'attendees' => [
                            '*' => [
                                'id',
                                'user_id',
                                'attendee',
                            ],
                        ],
                    ],
                ],
            ]);

    }

    public function test_single_event_with_no_attendees() {
        
        $response = $this->getJson("api/events/{$this->event->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [    
                    'id',
                    'name',
                    'start_time',
                    'end_time',
                    "event_owner" => [
                        'id',
                        'name',
                        'email'
                    ]
                ]    
            ])
            ->assertJsonCount(0, 'data.attendees');;  
    }

    public function test_create_event_when_not_authenticated() {

        $response = $this->postJson("api/events", [
            'name' => 'Edis test event',
            'start_time' => '2024-03-29 10:00:00',
            'end_time' => '2024-04-20 10:00:00',
        ]);

        $response->assertUnauthorized();

    }

    public function test_create_event_when_authenticated() {

        $response = $this->actingAs($this->user)->postJson("api/events", [
            'name' => 'Edis test event',
            'start_time' => '2024-03-29 10:00:00',
            'end_time' => '2024-04-20 10:00:00',
        ]);


        $response->assertExactJson([
            'data' => [
                'id' => 2,
                'name' => 'Edis test event',
                'start_time' => '2024-03-29 10:00:00',
                'end_time' => '2024-04-20 10:00:00',
                'event_owner' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ],
            ],
        ])
        ->assertCreated();

    }

    public function test_update_event_when_not_authenticated() {

        $eventID = $this->event->id;
        $response = $this->putJson("api/events/$eventID", [
            'name' => 'Updated event name',
            'start_time' => '2025-03-29 10:00:00',
            'end_time' => '2025-04-20 10:00:00',
        ]);

        $response->assertUnauthorized();


    }

    public function test_update_event_when_authenticated() {

        $user = User::factory()->create();


        $eventID = $this->event->id;
        $response = $this->actingAs($this->user)->putJson("api/events/$eventID", [
            'name' => 'Updated event name',
            'start_time' => '2025-03-29 10:00:00',
            'end_time' => '2025-04-20 10:00:00',
        ]);

        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Updated event name',
                'start_time' => '2025-03-29 10:00:00',
                'end_time' => '2025-04-20 10:00:00',
                'event_owner' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ],
                'attendees' => []
            ],
        ])
        ->assertStatus(200);

    }


    public function test_update_event_not_allowed_with_unauthorized_user() {

        $user = User::factory()->create();


        $eventID = $this->event->id;
        $response = $this->actingAs($user)->putJson("api/events/$eventID", [
            'name' => 'Updated event name',
            'start_time' => '2025-03-29 10:00:00',
            'end_time' => '2025-04-20 10:00:00',
        ]);

        $response->assertForbidden();


    }

    //create and update with invalid form data
}
