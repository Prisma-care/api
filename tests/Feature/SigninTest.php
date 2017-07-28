<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SigninTest extends TestCase
{
	public function testSignin()
	{
		$request = [
		    'email' => 'testing@prisma.care',
		    'password' => 'testing@prisma.care'
		];
		$response = $this->json('POST', 'v1/user/signin', $request)
		     ->assertJsonStructure([
		         'meta' => [ 'code', 'message' ],
		         'response' => [ 'id', 'token' ]
		     ])
		     ->assertStatus(200);
	}
}
