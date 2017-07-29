<?php

namespace Tests;

use JWTAuth;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $exceptionResponseStructure = [
		'meta' => [ 'code', 'message'],
		'response' => []
    ];

    protected $params = [];
    protected $headers = [
	    'HTTP_Authorization' => 'Bearer <token>'
    ];

    public function setUp()
	{
		parent::setUp();
		$this->artisan('migrate:refresh');
	    $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
	}

	protected function authenticate($user = null)
	{
		if (!$user) {
			$user = User::first();
		}
		$token = JWTAuth::fromUser($user);
		$this->headers['HTTP_Authorization'] = 'Bearer ' . $token;
	}
}
