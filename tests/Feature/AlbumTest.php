<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlbumTest extends TestCase
{
    private $existingPatientId = 1;
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
          'source' => 'A source'
        ]
      ]
	  ];
  private $objectStructure;

 	public function setUp()
  {
    parent::setUp();
    $this->authenticate();
    $this->baseObjectStructure = array_merge(
      array_keys($this->baseObject),
      [
        'stories' => [
          '*' => array_keys($this->baseObject['stories'][0])
        ]
      ]
    );
    $this->endpoint = $this->getEndpointWithValidPatientId();
  }

  private function getEndpointWithValidPatientId() {
    return str_replace('{patientId}', $this->existingPatientId, $this->baseEndpoint);
  }
  private function getEndpointWithInvalidPatientId() {
    return str_replace('{patientId}', 0, $this->baseEndpoint);
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
            '*' => $this->baseObjectStructure
          ]
      ])
      ->assertStatus(200);
  }

  public function testIndexAlbumWithInvalidPatientId()
  {
    $endpoint = $this->getEndpointWithInvalidPatientId();
    $response = $this->getJson($endpoint, $this->headers)
      ->assertJsonStructure($this->exceptionResponseStructure)
      ->assertStatus(400);
  }

  public function testGetAlbum($location = null)
  {
    $endpoint = $this->endpoint . '/1';
    if ($location) {
      $endpoint = $this->parseResourceLocation($location);
    }
    $response = $this->getJson($endpoint, $this->headers)
      ->assertJsonStructure([
        'meta' => $this->metaResponseStructure,
        'response' => $this->baseObjectStructure
      ])
      ->assertStatus(200);
  }

  public function testGetAlbumWithInvalidPatientId()
  {
    $endpoint = $this->getEndpointWithInvalidPatientId() . '/1';
    $response = $this->getJson($endpoint, $this->headers)
      ->assertJsonStructure($this->exceptionResponseStructure)
      ->assertStatus(400);
  }

  public function testGetAlbumWithInvalidAlbumId()
  {
    $endpoint = $this->endpoint . '/0';
    $response = $this->getJson($endpoint, $this->headers)
      ->assertJsonStructure($this->exceptionResponseStructure)
      ->assertStatus(400);
  }

  public function testCreateAlbum()
  {
    $body = [ 'title' => str_random(16) ];
    $response = $this->postJson($this->endpoint, $body, $this->headers)
      ->assertJsonStructure([
        'meta' => $this->metaCreatedResponseStructure,
        'response' => [ 'id', 'title' ]
      ])
      ->assertStatus(201)
      ->getData();
    $this->testGetAlbum($response->meta->location);
  }

  public function testCreateAlbumWithInvalidPatientId()
  {
    $endpoint = $this->getEndpointWithInvalidPatientId();
    $body = [ 'title' => str_random(16) ];
    $response = $this->postJson($endpoint, $body, $this->headers)
      ->assertJsonStructure($this->exceptionResponseStructure)
      ->assertStatus(400);
  }

  public function testCreateAlbumWithTakenTitle()
  {
    $body = [ 'title' => 'Taken' ];
    $response = $this->postJson($this->endpoint, $body, $this->headers)
      ->assertJsonStructure($this->exceptionResponseStructure)
      ->assertStatus(400);
  }

  public function testUpdateAlbum()
  {
    $album = \App\Album::create(['title' => str_random(20), 'patient_id' => 1]);
    $endpoint = $this->endpoint . '/' . $album->id;
    $newTitle = str_random(20);
    $response = $this->patchJson($endpoint, ['title' => $newTitle], $this->headers)
      ->assertJsonStructure([
        'meta' => $this->metaResponseStructure,
        'response' => []
      ])
      ->assertStatus(200);
    $album = \App\Album::find($album->id);
    $this->assertEquals($album->title, $newTitle);
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
