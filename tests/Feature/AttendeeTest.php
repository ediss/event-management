<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeTest extends TestCase
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

    public function test_event_does_not_have_attendees(){

        $response = $this->getJson("api/events/{$this->event->id}/attendees");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data');
    }

    public function test_event_has_10_attendees(){
        $attendees = User::factory()->count(10)->create();

        foreach ($attendees as $attendee) {
            $this->event->attendees()->create([
                'user_id' => $attendee->id,
                'event_id' => $this->event->id,
            ]);
        }

        $response = $this->getJson("api/events/{$this->event->id}/attendees");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'attendee',
                    ],
                ],
                'links',
                'meta',
            ])
            ->assertJsonCount(10, 'data');
    }

    public function test_add_attendee_to_event_when_not_authenticated(){
        $response = $this->postJson("api/events/{$this->event->id}/attendees", [
            'user_id' => 10,
        ]);

        $response->assertUnauthorized();
    }

    public function test_add_attendee_to_event_when_authenticated(){
        $response = $this->actingAs($this->user)
            ->postJson("api/events/{$this->event->id}/attendees", [
                'user_id' => $this->user->id,
            ]);

        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'user_id' => $this->user->id,
                'attendee' => $this->user->name,
            ],
        ])
        ->assertCreated();

    }

    //test delete attendee
}
