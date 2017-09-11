<?php

namespace Tests\Feature;

use App\Patient;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatientTest extends TestCase
{
    private $baseEndpoint = 'v1/patient';
    private $endpoint;

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
        $this->endpoint = "v1/patient/$this->testPatientId";
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->getJson($this->endpoint, $headers)
            ->assertStatus(401);
    }

    public function testGetPatient($location = null)
    {
        $endpoint = $this->endpoint;
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
        $response = $this->getJson($this->baseEndpoint . '/0', $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->getJson($this->endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testCreatePatient()
    {
        $body = $this->baseObject;
        unset($body['id']);
        $response = $this->postJson($this->baseEndpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => array_keys($this->baseObject)
             ])
            ->assertStatus(201)
            ->getData();
        $this->testGetPatient($response->meta->location);
    }

    public function testUserIsConnectedByDefault()
    {
        $body = $this->baseObject;
        unset($body['id']);
        $response = $this->postJson($this->baseEndpoint, $body, $this->headers)->getData();
        $patient = Patient::find($response->response->id);
        $isConnected = $patient->users()->exists($this->testUserId);
        $this->assertTrue($isConnected);
    }

    public function testCreatePatientWithoutRequiredFields()
    {
        $requiredKeys = [ 'firstName', 'lastName' ];
        foreach ($requiredKeys as $key) {
            $body = $this->baseObject;
            unset($body[$key]);
            $response = $this->postJson($this->baseEndpoint, $body, $this->headers)
                ->assertJsonStructure($this->exceptionResponseStructure)
                ->assertStatus(400);
        }
    }
}
