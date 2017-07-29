<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatientTest extends TestCase
{
    private $endpoint = 'v1/patient';

 	public function setUp()
    {
        parent::setUp();
        $this->authenticate();
    }

    public function testResourceIsProtected()
	{
		$headers = $this->headers;
		unset($headers['HTTP_Authorization']);
		$response = $this->getJson($this->endpoint . '/1', $headers)
		    ->assertStatus(401);
	}

    public function testGetPatient()
	{
		$this->refreshApplication();
		$response = $this->getJson($this->endpoint . '/1', $this->headers)
		    ->assertStatus(200);
	}
}
