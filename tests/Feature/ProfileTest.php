<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /**
     * Testing the basic university profile.
     *
     * @return void
     */
    public function testBasiProfile()
    {
        // Order by name asc and get the first occurrence
        $university = \App\Models\University::where('enabled', true)
            ->where('premium', false)
            ->first();
            
        $response = $this->json('GET', 'api/v1/profile/'.$university->id, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Check the basic profile
        $response->assertJson([
            "success" => true,
            "data" => [
                "name" => $university->name,
                'description' => $university->description,
                'logo_image_path' => $university->logo_image_path,
            ],
            "message" => "University basic profile found"
        ]);

        // Check address is not there
        $response->assertJsonMissing([
            "data" => [
                "address" => $university->address,
            ],
        ]);
    }

    /**
     * Testing the premium university profile.
     *
     * @return void
     */
    public function testPremiumProfile()
    {
        // Order by name asc and get the first occurrence
        $university = \App\Models\University::where('enabled', true)
            ->where('premium', true)
            ->first();
            
        $response = $this->json('GET', 'api/v1/profile/'.$university->id, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Check the preimum profile
        $response->assertJson([
            "success" => true,
            "data" => [
                "name" => $university->name,
                'description' => $university->description,
                'logo_image_path' => $university->logo_image_path,
                "address" => $university->address,
            ],
            "message" => "University premium profile found"
        ]);
    }
}
