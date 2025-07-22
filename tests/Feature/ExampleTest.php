<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
        // Option 1: Test for redirect when not authenticated
        $response = $this->get('/');
        $response->assertStatus(302); // Redirect to login
        
        // Option 2: Test authenticated access
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }
}