<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SignupTest extends TestCase
{
	private $baseRequest = [
		'firstName' => 'Signup',
		'lastName' => 'Test',
	    'email' => 'signup@test.com',
	    'password' => 'signup@test.com'
	];

	public function testSignup()
	{
		$response = $this->json('POST', 'v1/user', $this->baseRequest)
		     ->assertJsonStructure([
		         'meta' => [ 'code', 'message', 'location' ],
		         'response' => [ 'id', 'email' ]
		     ])
		     ->assertStatus(201);
	}

	public function testSignupWithoutRequiredFields()
	{
		$requiredKeys = [ 'firstName', 'lastName', 'email', 'password'];
		foreach ($requiredKeys as $key) {
			$request = $this->baseRequest;
			unset($request[$key]);
			$response = $this->json('POST', 'v1/user', $request)
		     ->assertJsonStructure($this->exceptionResponseStructure)
		     ->assertStatus(400);
		}
	}

	public function testSignupWithInvalidEmail()
	{
		$request = $this->baseRequest;
		$request['email'] = 'signup@prisma';
		$response = $this->json('POST', 'v1/user', $request)
		     ->assertJsonStructure($this->exceptionResponseStructure)
		     ->assertStatus(400);
	}

	public function testSignupWithTakenEmail()
	{
		$request = $this->baseRequest;
		$request['email'] = 'testing@prisma.care';
		$response = $this->json('POST', 'v1/user', $request)
		     ->assertJsonStructure($this->exceptionResponseStructure)
		     ->assertStatus(400);
	}
}
