<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class FavouriteTest extends TestCase
{
    /**
     * Testing to add an university as favourite.
     *
     * @return void
     */
    public function testAddFavourite()
    {
        // Create an university
        $university = \App\Models\University::factory(1)->create([
            'enabled' => true,
        ])->first();

        // Create an authenticated user
        $user = Sanctum::actingAs(
            \App\Models\User::factory()->create()
        );

        $favouriteData = [
            'university_id' => $university->id,
        ];
 
        $response =$this->json('POST', 'api/v1/favourites', $favouriteData, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            "success" => true,
            "message" => "The university has been added as favourite."
        ]);

        $response->assertJsonStructure([
            "success",
            "data" => [
                'user_id',
                'university_id'
            ],
             "message"
        ]);
    }

    /**
     * Testing to remove an university as favourite.
     *
     * @return void
     */
    public function testRemoveFavourite()
    {
        // Create an university
        $university = \App\Models\University::factory(1)->create([
            'enabled' => true,
        ])->first();

        // Create an authenticated user
        $user = Sanctum::actingAs(
            \App\Models\User::factory()->create()
        );

        $favouriteData = [
            'university_id' => $university->id,
        ];

        // Add university as favourite
        $this->json('POST', 'api/v1/favourites', $favouriteData, ['Accept' => 'application/json']);

        // Remove university from favourites
        $response =$this->json('DELETE', 'api/v1/favourites/'.$university->id, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            "success" => true,
            "message" => "The university has been removed from favourites."
        ]);
    }
}
