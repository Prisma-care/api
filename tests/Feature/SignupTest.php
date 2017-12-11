<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SignupTest extends TestCase
{
    private $endpoint = 'v1/user';
    private $baseObject = [
        'firstName' => 'Signup',
        'lastName' => 'Test',
        'email' => 'signup@test.com',
        'password' => 'signup@test.com'
    ];

    public function testSignup()
    {
        $response = $this->postJson($this->endpoint, $this->baseObject)
        ->assertJsonStructure([
            'meta' => $this->metaCreatedResponseStructure,
            'response' => ['id', 'email']
        ])
        ->assertStatus(201);
    }

    public function testSignupWithoutRequiredFields()
    {
        $requiredKeys = ['firstName', 'lastName', 'email', 'password'];
        foreach ($requiredKeys as $key) {
            $body = $this->baseObject;
            unset($body[$key]);
            $response = $this->postJson($this->endpoint, $body)
                ->assertJsonStructure($this->exceptionResponseStructure)
                ->assertStatus(400);
        }
    }

    public function testSignupWithInvalidEmail()
    {
        $body = $this->baseObject;
        $body['email'] = 'signup@prisma';
        $response = $this->postJson($this->endpoint, $body)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testSignupWithTakenEmail()
    {
        $body = $this->baseObject;
        $body['email'] = 'testing@prisma.care';
        $response = $this->postJson($this->endpoint, $body)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }
}
