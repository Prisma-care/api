<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoryTest extends TestCase
{
    private $baseEndpoint = 'v1/patient/{patientId}/story';
    private $endpoint;
    private $ownedAlbumId;
    private $baseObject = [
        'id' => null,
        'description' => 'A description',
        'happenedAt' => null,
        'albumId' => 1,
        'creatorId' => 1,
        'assetSource' => null,
        'favorited' => false
    ];

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $this->endpoint = $this->getEndpointWithValidPatientId();
        $this->ownedAlbumId = \App\Patient::find($this->testPatientId)
                                ->albums()->get()->values()->first()->id;
        $this->baseObject['albumId'] = $this->ownedAlbumId;
        $this->baseObject['creatorId'] = $this->testUserId;
    }

    private function getEndpointWithValidPatientId($patientId = null)
    {
        return str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
    }
    private function getEndpointWithInvalidPatientId()
    {
        return str_replace('{patientId}', 0, $this->baseEndpoint);
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->getJson($this->endpoint . '/1', $headers)
            ->assertStatus(401);
    }

    public function testResourceIsRestricted()
    {
        $patient = \App\Patient::find($this->testPatientId);
        $patient->users()->detach($this->testUserId);
        $response = $this->getJson($this->endpoint . '/1', $this->headers)
            ->assertStatus(403);
    }

    public function testGetStory($location = null)
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

    public function testGetStoryWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithInvalidPatientId() . '/1';
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetStoryWithInvalidStoryId()
    {
        $endpoint = $this->endpoint . '/0';
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateStory()
    {
        $body = [ 'description' => str_random(16), 'albumId' => $this->ownedAlbumId, 'creatorId' => 1 ];
        $expectedResponseObject = $this->baseObject;
        unset($expectedResponseObject['assetSource']);
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => array_keys($expectedResponseObject)
            ])
            ->assertStatus(201)
            ->getData();
        $this->testGetStory($response->meta->location);
    }

    public function testCreateStoryWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithInvalidPatientId();
        $body = [ 'description' => str_random(16), 'albumId' => $this->ownedAlbumId, 'creatorId' => 1 ];
        $response = $this->postJson($endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateStoryWithInvalidAlbumId()
    {
        $body = [ 'description' => str_random(16), 'albumId' => 0, 'creatorId' => 1 ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateStoryBelongingToAlbumOfAnotherPatient()
    {
        $patient = factory(\App\Patient::class)->create();
        $patient->users()->attach($this->testUserId);
        $album = factory(\App\Album::class)->create(['patient_id' => $patient->id]);

        $patient2 = factory(\App\Patient::class)->create();
        $album2 = factory(\App\Album::class)->create(['patient_id' => $patient2->id]);

        $endpoint = $this->getEndpointWithValidPatientId($patient->id);
        $body = [
            'description' => str_random(16),
            'albumId' => $album2->id,
            'creatorId' => $this->testUserId
        ];
        $response = $this->postJson($endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testCreateStoryWithoutRequiredFields()
    {
        $requiredKeys = [ 'description', 'albumId', 'creatorId' ];
        foreach ($requiredKeys as $key) {
            $body = $this->baseObject;
            unset($body[$key]);
            $response = $this->postJson($this->endpoint, $body, $this->headers)
               ->assertJsonStructure($this->exceptionResponseStructure)
               ->assertStatus(400);
        }
    }

    public function testUpdateStory()
    {
        $story = \App\Story::create([ 'description' => str_random(16), 'album_id' => 1, 'user_id' => 1 ]);
        $endpoint = $this->endpoint . '/' . $story->id;
        $newDescription = str_random(20);
        $response = $this->patchJson($endpoint, ['description' => $newDescription], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => []
            ])
            ->assertStatus(200);
        $story = \App\Story::find($story->id);
        $this->assertEquals($story->description, $newDescription);
    }

    public function testDeleteAlbum()
    {
        $response = $this->deleteJson($this->endpoint . '/1', [], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => []
            ])
            ->assertStatus(200);
    }
}
