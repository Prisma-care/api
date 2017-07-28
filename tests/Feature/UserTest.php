<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	private $token;

   	public function testSignin()
	{
		$requestBody = [
		    'email' => 'michiel.leyman@gmail.com',
		    'password' => '123'
		];
		$response = $this->json('POST', 'v1/user/signin', $requestBody)
		     ->assertJsonStructure([
		         'meta' => [ 'code', 'message' ],
		         'response' => [ 'id', 'token' ]
		     ])
		     ->assertStatus(200);
	}
}
