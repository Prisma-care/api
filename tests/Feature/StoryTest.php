<?php

namespace Tests\Feature;

use App\Story;
use App\Album;
use App\Patient;
use Tests\TestCase;

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
        'favorited' => false,
    ];

    private function getEndpointWithPatientId($patientId = null)
    {
        return str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $ownedAlbum = Patient::find($this->testPatientId)
                                ->albums()->get()->values()->first();
        $this->ownedAlbumId = $ownedAlbum->id;
        $this->ownedStoryId = $ownedAlbum->stories()->first()->id;
        $this->baseObject['albumId'] = $this->ownedAlbumId;
        $this->baseObject['creatorId'] = $this->testUserId;
        $this->endpoint = $this->getEndpointWithPatientId();
        $this->specificEndpoint = "$this->endpoint/$this->ownedStoryId";
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->getJson($this->specificEndpoint, $headers)
            ->assertStatus(401);
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
                'response' => array_keys($this->baseObject),
            ])
            ->assertStatus(200);
    }

    public function testGetStoryWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithPatientId($this->nonExistentPatientId).'/'.$this->ownedStoryId;
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetStoryWithInvalidStoryId()
    {
        $endpoint = $this->endpoint.'/0';
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetStoryBelongingToUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->getJson($this->specificEndpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testCreateStory()
    {
        $body = ['description' => str_random(16), 'albumId' => $this->ownedAlbumId, 'creatorId' => $this->testUserId];
        $expectedResponseObject = $this->baseObject;
        unset($expectedResponseObject['assetSource']);
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => array_keys($expectedResponseObject),
            ])
            ->assertStatus(201)
            ->getData();
        $this->testGetStory($response->meta->location);
    }

    public function testCreateStoryWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithPatientId($this->nonExistentPatientId);
        $body = ['description' => str_random(16), 'albumId' => $this->ownedAlbumId, 'creatorId' => $this->testUserId];
        $response = $this->postJson($endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateStoryWithInvalidAlbumId()
    {
        $body = ['description' => str_random(16), 'albumId' => 0, 'creatorId' => $this->testUserId];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateStoryForUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $body = ['description' => str_random(16), 'albumId' => $this->ownedAlbumId, 'creatorId' => $this->testUserId];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testCreateStoryBelongingToAlbumOfAnotherPatient()
    {
        $album = factory(Album::class)->create([
            'patient_id' => $this->privatePatientId,
        ]);

        $body = [
            'description' => str_random(16),
            'albumId' => $album->id,
            'creatorId' => $this->testUserId,
        ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testCreateStoryWithoutRequiredFields()
    {
        $requiredKeys = ['albumId', 'creatorId'];
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
        $story = Story::create([
            'description' => str_random(16),
            'album_id' => $this->ownedAlbumId,
            'user_id' => $this->testUserId,
        ]);
        $endpoint = $this->endpoint.'/'.$story->id;
        $newDescription = str_random(20);
        $response = $this->patchJson($endpoint, ['description' => $newDescription], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => [],
            ])
            ->assertStatus(200);
        $story = Story::find($story->id);
        $this->assertEquals($story->description, $newDescription);
    }

    public function testUpdateStoryBelongingToUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->patchJson($this->specificEndpoint, ['description' => str_random(20)], $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testDeleteStory()
    {
        $response = $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => [],
            ])
            ->assertStatus(200);
    }

    public function testDeleteStoryBelongingToUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }
}
