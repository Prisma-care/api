<?php

namespace Tests;

use JWTAuth;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $metaResponseStructure = ['code', 'message'];
    protected $metaCreatedResponseStructure = ['code', 'message', 'location'];
    protected $exceptionResponseStructure = [
        'meta' => ['code', 'message'],
        'response' => []
    ];

    protected $headers = [
        'HTTP_Authorization' => 'Bearer <token>'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');
    }

    protected function authenticate($user = null)
    {
        if (!$user) {
            $user = User::first();
        }
        $token = JWTAuth::fromUser($user);
        $this->headers['HTTP_Authorization'] = 'Bearer ' . $token;
        $this->refreshApplication();
    }

    public function createUsers($amount = 5)
    {
        $user = factory(\App\User::class, $amount)->make();
    }

    /**
     * Returns everything after the third slash in a string
     * So for URL 'https://localhost/v1/something/1', it returns 'v1/something/1'
     */
    protected function parseResourceLocation($location)
    {
        return substr($location, (strpos($location, '/', strpos($location, '/') + 2) + 1));
    }
}
