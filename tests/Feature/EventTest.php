<?php

namespace Tests\Feature;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_event_does_not_have_attendees() {
        Event::factory()->create([
            'user_id' => $this->createUser()->id
        ]);

        $response = $this->json('GET', 'api/events/1/attendees');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data');


    }

    public function test_event_has_10_attendees() {

        $event = Event::factory()->create([
            'user_id' => $this->createUser()->id
        ]);
        
        $attendees = User::factory()->count(10)->create();
        
        $event->attendees()->saveMany($attendees);

        
    }


    // public function test_create_event(): void
    // {

    //     $data = [
    //         'name' => 'DISCO NIGHT EVENT',
    //         'user_id' => 10022,
    //         'start_time' => date('Y-m-d'),
    //         'end_time' => date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))))
    //     ];

    //     $response = $this->json('POST', '/api/events', $data);

    //      $this->assertDatabaseHas('events', [
    //         'name' => 'DISCO NIGHT EVENT'
    //     ]);

    //     $get = $this->json('GET', '/api/events');

    //     dd($get);
    //         // ->assertStatus(201);

        
        
    //     // $event = new Event();
    //     // $event->name = 'event test name';
    //     // $event->user_id = 3000;
    //     // $event->start_time = date('Y-m-d');
    //     // $event->end_time = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
    //     // $event->save();

    //     // $this->assertDatabaseHas('events', [
    //     //     'name' => 'event test name'
    //     // ]);

        
    //     $response = $this->get('/api/events');

    //     $response->assertStatus(200);
    // }


    // public function test_get_events(): void
    // {
    //     $response = $this->get('api/events');
    //     $this->assertTrue(true);
    //     // $response = $this->get('/');

    //     // $response->assertStatus(200);
    // }
}
