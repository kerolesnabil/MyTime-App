<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {

        $response = $this->post('/api/v1/user/register',[
            "user_name"          => "keroles nabil",
            "user_address"       => "asdsad",
            "user_phone"         => "999999999",
            "user_email"         => "asdasdas@gmail.com",
            "user_long"          => "85.752527",
            "user_lat"           => "45.25274",
            "user_date_of_birth" => "2022-07-07",
        ]);

        $response->assertStatus(401);
    }
}
