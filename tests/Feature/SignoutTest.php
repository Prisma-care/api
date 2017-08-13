<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SignoutTest extends TestCase
{
    private $endpoint = 'v1/user/signout';

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
    }

    public function testSignout()
    {
        $response = $this->postJson($this->endpoint, [], $this->headers)
        ->assertJsonStructure([
            'meta' => $this->metaResponseStructure,
            'response' => []
        ])
        ->assertStatus(200);
        if (Auth::user()) {
            $this->fail('User was not logged out');
        }
    }
}
