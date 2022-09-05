<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    /**
     * Testing to write a review with incorrect rating.
     *
     * @return void
     */
    public function testWriteReviewWithIncorrectRating()
    {
        $university = \App\Models\University::where('enabled', true)
            ->first();

        $reviewData = [
            'university_id' => $university->id,
            'user_name' => fake()->name(),
            'review_comment' => fake()->text(200),
            'rating' => 6,
        ];
 
        $response =$this->json('POST', 'api/v1/reviews', $reviewData, ['Accept' => 'application/json']);

        $response->assertStatus(400);

        $response->assertJson([
            "success" => false,
            "message" => "Validation Error",
            "errors" => [
                "rating" => [
                    "The rating must be between 1 and 5."
                ]
            ]
        ]);
    }

    /**
     * Testing to write a review successfully.
     *
     * @return void
     */
    public function testWriteReviewSuccessfully()
    {
        $university = \App\Models\University::where('enabled', true)
            ->first();

        $reviewData = [
            'university_id' => $university->id,
            'user_name' => fake()->name(),
            'review_comment' => fake()->text(200),
            'rating' => fake()->numberBetween(1,5),
        ];
 
        $response =$this->json('POST', 'api/v1/reviews', $reviewData, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            "success" => true,
            "message" => "The review has been added successfully."
        ]);

        $response->assertJsonStructure([
            "success",
            "data" => [
                'university_id',
                'user_name',
                'review_comment',
                'rating',
                'updated_at',
                'created_at',
                'id'
            ],
             "message"
        ]);
    }
}
