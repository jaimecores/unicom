<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class AuthenticationTest extends TestCase
{
    /**
     * Testing the required fields for registration.
     *
     * @return void
     */
    public function testRequiredFieldsForRegistration()
    {
        $response = $this->json('POST', 'api/v1/register', ['Accept' => 'application/json']);

        $response->assertStatus(422);
        
        $response->assertJson([
                "success" => false,
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "repeat_password" => ["The repeat password field is required."],
                ]
            ]);
    }

    /**
     * Testing repeat password for registration.
     *
     * @return void
     */
    public function testRepeatPasswordForRegistration()
    {
        $userData = [
            "name" => "Test name",
            "email" => fake()->safeEmail(),
            "password" => "test1234"
        ];

        $response = $this->json('POST', 'api/v1/register', $userData, ['Accept' => 'application/json']);

        $response->assertStatus(422);

        $response->assertJson([
            "success" => false,
            "message" => "The given data was invalid.",
            "errors" => [
                "repeat_password" => ["The repeat password field is required."]
            ]
        ]);
    }

    /**
     * Testing a successful registration.
     *
     * @return void
     */
    public function testSuccessfulRegistration()
    {
        $userData = [
            "name" => "Test name",
            "email" => fake()->safeEmail(),
            "password" => "test1234",
            "repeat_password" => "test1234"
        ];

        $response = $this->json('POST', 'api/v1/register', $userData, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            "success" => true,
            "message" => "User registered successfully.",
        ]);

        $response->assertJsonStructure([
            "success",
            "data" => [
                'token',
            ],
             "message"
         ]);
    }

    /**
     * Testing the required fields for login.
     *
     * @return void
     */
    public function testRequiredFieldsForLogin()
    {
        $response = $this->json('POST', 'api/v1/login', ['Accept' => 'application/json']);

        $response->assertStatus(422);
        
        $response->assertJson([
                "success" => false,
                "message" => "The given data was invalid.",
            ]);
    }

    /**
     * Testing a successful login.
     *
     * @return void
     */
    public function testSuccessfulLogin()
    {
        $testEmail = fake()->safeEmail();
        $user = \App\Models\User::factory(1)->create([
            'email' => $testEmail,
            'password' => bcrypt('test123'),
        ]);
 
        $loginData = ['email' => $testEmail, 'password' => 'test123'];
 
        $response =$this->json('POST', 'api/v1/login', $loginData, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "success",
            "data" => [
                'token',
            ],
            "message"
        ]);
    }

}
