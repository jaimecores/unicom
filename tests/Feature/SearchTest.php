<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    
    /**
     * Testing the search
     *
     * @return void
     */
    public function testSearch()
    {
        $response = $this->json('GET', 'api/v1/search', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "success",
            "data" => [
                "total",
                "results",
            ],
            "message"
        ]);
    }

    /**
     * Testing the total results from a search
     *
     * @return void
     */
    public function testSearchTotal()
    {
        // Get enabled universities   
        $universities = \App\Models\University::where('enabled', true)->get();

        $response = $this->json('GET', 'api/v1/search', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Return the number of enabled universities
        $response->assertJson([
            "data" => [
                'total' => $universities->count(),
            ],
        ]);
    }

    /**
     * Testing to search using a keyword
     *
     * @return void
     */
    public function testSearchByKeyword()
    {
        // Create an univeristy
        $name = "University of ".fake()->name();
        $university = \App\Models\University::factory(1)->create([
            "name" => $name,
            "enabled" => 1,
            "premium" => 1,
        ]);

        // Filter by keyword
        $response = $this->json('GET', 'api/v1/search?keyword='.$name, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Find the university
        $response->assertJson([
            "success" => true,
            "data" => [
                "total" => 1,
                "results"  => [ 
                    [
                        "name" => $name,
                    ]                    
                ],
            ],
            "message" => "Search results"
        ]);
    }

    /**
     * Testing to search and order results by name asc
     *
     * @return void
     */
    public function testSearchOrderByNameAsc()
    {
        // Order by name asc and get the first occurrence
        $university = \App\Models\University::where('enabled', true)
            ->orderBy('name', 'asc')
            ->first();

        $response = $this->json('GET', 'api/v1/search?keyword=&sort=name&order=asc', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Check the first occurrence
        $response->assertJson([
            "success" => true,
            "data" => [
                "results"  => [ 
                    [
                        "name" => $university->name,
                    ]                    
                ],
            ],
            "message" => "Search results"
        ]);
    }

    /**
     * Testing to search and order results by name desc
     *
     * @return void
     */
    public function testSearchOrderByNameDesc()
    {
        // Order by name desc and get the first occurrence
        $university = \App\Models\University::where('enabled', true)
            ->orderBy('name', 'desc')
            ->first();

        $response = $this->json('GET', 'api/v1/search?keyword=&sort=name&order=desc', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Check the first occurrence
        $response->assertJson([
            "success" => true,
            "data" => [
                "results"  => [ 
                    [
                        "name" => $university->name,
                    ]                    
                ],
            ],
            "message" => "Search results"
        ]);
    }

    /**
     * Testing to search and order results by rating asc
     *
     * @return void
     */
    public function testSearchOrderByRatingAsc()
    {
        // Order by name asc and get the first occurrence
        $university = \App\Models\University::where('enabled', true)
            ->orderBy('rating', 'asc')
            ->first();

        $response = $this->json('GET', 'api/v1/search?keyword=&sort=rating&order=asc', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Check the first occurrence
        $response->assertJson([
            "success" => true,
            "data" => [
                "results"  => [ 
                    [
                        "name" => $university->name,
                    ]                    
                ],
            ],
            "message" => "Search results"
        ]);
    }

    /**
     * Testing to search and order results by rating desc
     *
     * @return void
     */
    public function testSearchOrderByRatingDesc()
    {
        // Order by name desc and get the first occurrence
        $university = \App\Models\University::where('enabled', true)
            ->orderBy('rating', 'desc')
            ->first();

        $response = $this->json('GET', 'api/v1/search?keyword=&sort=rating&order=desc', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        // Check the first occurrence
        $response->assertJson([
            "success" => true,
            "data" => [
                "results"  => [ 
                    [
                        "name" => $university->name,
                    ]                    
                ],
            ],
            "message" => "Search results"
        ]);
    }

}
