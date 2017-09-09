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
    private $specificEndpoint;

    private $ownedAlbumId;
    private $ownedStoryId;
    private $baseObject = [
        'id' => null,
        'description' => 'A description',
        'happenedAt' => null,
        'albumId' => 1,
        'creatorId' => 1,
        'assetSource' => null,
        'favorited' => false
    ];

    private function getEndpointWithValidPatientId($patientId = null)
    {
        return str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
    }
    private function getEndpointWithInvalidPatientId()
    {
        return str_replace('{patientId}', 0, $this->baseEndpoint);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $ownedAlbum = \App\Patient::find($this->testPatientId)
                                ->albums()->get()->values()->first();
        $this->ownedAlbumId = $ownedAlbum->id;
        $this->ownedStoryId = $ownedAlbum->stories()->first()->id;
        $this->baseObject['albumId'] = $this->ownedAlbumId;
        $this->baseObject['creatorId'] = $this->testUserId;
        $this->endpoint = $this->getEndpointWithValidPatientId();
        $this->specificEndpoint = "$this->endpoint/$this->ownedStoryId";
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->getJson($this->specificEndpoint, $headers)
            ->assertStatus(401);
    }

    public function testResourceIsRestricted()
    {
        $patient = \App\Patient::find($this->testPatientId);
        $patient->users()->detach($this->testUserId);
        $response = $this->getJson($this->specificEndpoint, $this->headers)
            ->assertStatus(403);
    }

    public function testGetStory($location = null)
    {
        $endpoint = $this->specificEndpoint;
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
        $endpoint = $this->getEndpointWithInvalidPatientId() . '/' . $this->ownedStoryId;
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

    public function testGetStoryBelongingToAnotherPatient()
    {
        $endpoint = $this->getEndpointWithValidPatientId($this->privatePatientId) . '/' . $this->ownedStoryId;
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
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
        $album = factory(\App\Album::class)->create([
            'patient_id' => $this->privatePatientId
        ]);

        $body = [
            'description' => str_random(16),
            'albumId' => $album->id,
            'creatorId' => $this->testUserId
        ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
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
        $response = $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => []
            ])
            ->assertStatus(200);
    }
}
