<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * Confirm that the root is reachable
     *
     * @return void
     */
    public function test_root()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Confirm that the api root is reachable
     *
     * @return void
     */
    public function test_api_root()
    {
        $response = $this->get('/api/');

        $response->assertStatus(200)->assertSeeText('hello');
    }
}
