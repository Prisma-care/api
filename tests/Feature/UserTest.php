<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->artisan('migrate:refresh');
	    $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
	}

	public function testSignup()
	{
		$requestBody = [
			'firstName' => 'User',
			'lastName' => 'Test',
		    'email' => 'unique@prisma.care',
		    'password' => 'unique@prisma.care'
		];
		$response = $this->json('POST', 'v1/user', $requestBody)
		     ->assertJsonStructure([
		         'meta' => [ 'code', 'message', 'location' ],
		         'response' => [ 'id', 'email' ]
		     ])
		     ->assertStatus(201);
	}

   	public function testSignin()
	{
		$requestBody = [
		    'email' => 'testing@prisma.care',
		    'password' => 'testing@prisma.care'
		];
		$response = $this->json('POST', 'v1/user/signin', $requestBody)
		     ->assertJsonStructure([
		         'meta' => [ 'code', 'message' ],
		         'response' => [ 'id', 'token' ]
		     ])
		     ->assertStatus(200);
	}
}
