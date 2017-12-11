<?php

namespace Tests\Feature;

use App\Album;
use Tests\TestCase;

class AlbumTest extends TestCase
{
    private $baseEndpoint = 'v1/patient/{patientId}/album';
    private $endpoint;
    private $baseObject = [
        'id' => null,
        'title' => 'Sports',
        'stories' => [
            [
              'id' => 1,
              'description' => 'A description',
              'type' => 'image',
              'favorited' => false,
              'source' => 'A source',
            ],
        ],
    ];
    private $objectStructure;

    private $ownedAlbumId;
    private $specificEndpoint;

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $this->baseObjectStructure = array_merge(
            array_keys($this->baseObject),
            [
              'stories' => [
                '*' => array_keys($this->baseObject['stories'][0]),
              ],
            ]
        );
        $this->endpoint = $this->getEndpointWithPatientId();
        $this->ownedAlbumId = \App\Patient::find($this->testPatientId)
                                ->albums()->get()->values()->first()->id;
        $this->specificEndpoint = "$this->endpoint/$this->ownedAlbumId";
    }

    private function getEndpointWithPatientId($patientId = null)
    {
        return str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->getJson($this->endpoint, $headers)
            ->assertStatus(401);
    }

    public function testIndexAlbum()
    {
        $response = $this->getJson($this->endpoint, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => [
                    '*' => $this->baseObjectStructure,
                ],
            ])
            ->assertStatus(200);
    }

    public function testIndexAlbumWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithPatientId($this->nonExistentPatientId);
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testIndexAlbumOfUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->getJson($this->endpoint, $this->headers)
            ->assertStatus(403);
    }

    public function testGetAlbum($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        }
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => $this->baseObjectStructure,
            ])
            ->assertStatus(200);
    }

    public function testGetAlbumWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithPatientId($this->nonExistentPatientId).'/'.$this->ownedAlbumId;
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetAlbumWithInvalidAlbumId()
    {
        $endpoint = $this->getEndpointWithPatientId($this->nonExistentPatientId).'/0';
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetAlbumOfUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->getJson($this->specificEndpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testCreateAlbum()
    {
        $body = ['title' => str_random(16)];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => ['id', 'title'],
            ])
            ->assertStatus(201)
            ->getData();
        $this->testGetAlbum($response->meta->location);
    }

    public function testCreateAlbumWithInvalidPatientId()
    {
        $endpoint = $this->getEndpointWithPatientId($this->nonExistentPatientId);
        $body = ['title' => str_random(16)];
        $response = $this->postJson($endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateAlbumWithTakenTitle()
    {
        $album = factory(Album::class)->create([
            'title' => 'Taken',
            'patient_id' => $this->testPatientId,
        ]);
        $body = ['title' => 'Taken'];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testCreateAlbumForUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $body = ['title' => str_random(20)];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testUpdateAlbum()
    {
        $album = Album::create(['title' => str_random(20), 'patient_id' => 1]);
        $endpoint = $this->endpoint.'/'.$album->id;
        $newTitle = str_random(20);
        $response = $this->patchJson($endpoint, ['title' => $newTitle], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => [],
            ])
            ->assertStatus(200);
        $album = Album::find($album->id);
        $this->assertEquals($album->title, $newTitle);
    }

    public function testUpdateAlbumOfUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $response = $this->patchJson($this->specificEndpoint, ['title' => str_random(20)], $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }

    public function testDeleteAlbum()
    {
        $response = $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => [],
            ])
            ->assertStatus(200);
    }

    public function testDeleteAlbumOfUnconnectedPatient()
    {
        $this->disconnectTestUserFromTestPatient();
        $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(403);
    }
}
