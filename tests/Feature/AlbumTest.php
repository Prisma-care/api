<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlbumTest extends TestCase
{
    private $endpoint = 'v1/patient/1/album';
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
    $body = [ 'title' => str_random(16) ];
    $response = $this->postJson('v1/patient/0/album', $body, $this->headers)
      ->assertJsonStructure($this->exceptionResponseStructure)
      ->assertStatus(400);
  }
}
