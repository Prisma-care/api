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
    private $baseObject = [
    	'id' => null,
		'firstName' => 'Patient',
		'lastName' => 'Testing',
	    'careHome' => null,
	    'dateOfBirth' => null,
	    'birthPlace' => null,
	    'location' => null
	];

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

    public function testGetPatient($location = null)
	{
		$endpoint = $this->endpoint . '/1';
		if ($location) {
			$endpoint = $this->parseResourceLocation($location);
		}
		$response = $this->getJson($endpoint, $this->headers)
			->assertJsonStructure([
		         'meta' => $this->metaResponseStructure,
		         'response' => array_keys($this->baseObject)
		     ])
		    ->assertStatus(200);
	}

	public function testGetPatientWithInvalidId()
	{
		$response = $this->getJson($this->endpoint . '/0', $this->headers)
			->assertJsonStructure($this->exceptionResponseStructure)
		    ->assertStatus(400);
	}

	public function testCreatePatient()
	{
		$body = $this->baseObject;
		unset($body['id']);
		$response = $this->postJson($this->endpoint, $body, $this->headers)
			->assertJsonStructure([
		         'meta' => $this->metaCreatedResponseStructure,
		         'response' => array_keys($this->baseObject)
		     ])
		    ->assertStatus(201)
		    ->getData();
		$this->testGetPatient($response->meta->location);
	}

	public function testCreatePatientWithoutRequiredFields()
	{
		$requiredKeys = [ 'firstName', 'lastName' ];
		foreach ($requiredKeys as $key) {
			$body = $this->baseObject;
			unset($body[$key]);
			$response = $this->postJson($this->endpoint, $body, $this->headers)
			     ->assertJsonStructure($this->exceptionResponseStructure)
			     ->assertStatus(400);
		}
	}
}
