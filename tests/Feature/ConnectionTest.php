<?php

namespace Tests\Feature;

use Tests\TestCase;

class ConnectionTest extends TestCase
{
    // This should be an unconnected patient
    private $existingPatientId = 3;
    private $baseEndpoint = 'v1/patient/{patientId}/connection';
    private $endpoint;

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $this->endpoint = $this->getEndpointWithValidPatientId();
    }

    private function getEndpointWithValidPatientId()
    {
        return str_replace('{patientId}', $this->existingPatientId, $this->baseEndpoint);
    }
    private function getEndpointWithInvalidPatientId()
    {
        return str_replace('{patientId}', 0, $this->baseEndpoint);
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->json('LINK', $this->endpoint, [], $headers)
            ->assertStatus(401);
    }

    public function testConnectUserToPatient()
    {
        $response = $this->json('LINK', $this->endpoint, [], $this->headers)
            ->assertStatus(200);
    }

    public function testConnectUserToPatientWithInvalidId()
    {
        $endpoint = $this->getEndpointWithInvalidPatientId();
        $response = $this->json('LINK', $endpoint, [], $this->headers)
            ->assertStatus(400);
    }

    public function testDisconnectUserFromPatient()
    {
        $this->testConnectUserToPatient();
        $response = $this->json('UNLINK', $this->endpoint, [], $this->headers)
            ->assertStatus(200);
    }

    public function testDisconnectUserFromPatientWithInvalidId()
    {
        $endpoint = $this->getEndpointWithInvalidPatientId();
        $response = $this->json('UNLINK', $endpoint, [], $this->headers)
            ->assertStatus(400);
    }
}
