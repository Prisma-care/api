<?php

namespace Tests\Feature;

use Tests\TestCase;

use JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SigninTest extends TestCase
{
	private $endpoint = 'v1/user/signin';
	private $baseRequest = [
	    'email' => 'testing@prisma.care',
	    'password' => 'testing@prisma.care'
	];

	public function testSignin()
	{
		$response = $this->json('POST', $this->endpoint, $this->baseRequest)
		     ->assertJsonStructure([
		         'meta' => [ 'code', 'message' ],
		         'response' => [ 'id', 'token' ]
		     ])
		     ->assertStatus(200)
		     ->getData();
		$userId = $response->response->id;
		if (Auth::user()->id !== $userId) {
	        $this->fail('User was not really logged in after request');
	    }
	}

	public function testSigninWithInvalidEmail()
	{
		$request = $this->baseRequest;
		$request['email'] = 'invalid';
		$response = $this->json('POST', $this->endpoint, $request)
		     ->assertJsonStructure($this->exceptionResponseStructure)
		     ->assertStatus(400);
	}

	public function testSigninWithUnregisteredEmail()
	{
		$request = $this->baseRequest;
		$request['email'] = 'nonexistent@prisma.care';
		$response = $this->json('POST', $this->endpoint, $request)
		     ->assertJsonStructure($this->exceptionResponseStructure)
		     ->assertStatus(401);
	}

	public function testSigninWithInvalidPassword()
	{
		$request = $this->baseRequest;
		$request['password'] = 'invalid';
		$response = $this->json('POST', $this->endpoint, $request)
		     ->assertJsonStructure($this->exceptionResponseStructure)
		     ->assertStatus(401);
	}
}
