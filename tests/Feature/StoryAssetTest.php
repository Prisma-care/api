<?php

namespace Tests\Feature;

use App\Story;
use App\Patient;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoryAssetTest extends TestCase
{
    private $diskName = 'stories';
    private $pathToStaticImage;

    private $baseEndpoint = 'v1/patient/{patientId}/story/{storyId}/asset';
    private $endpoint;
    private $specificEndpoint;

    private $ownedStoryId;
    private $youtubeAsset = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';

    private function getPopulatedEndpoint($patientId = null, $storyId = null)
    {
        $tmpEndpoint = str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
        return str_replace('{storyId}', $storyId ?: $this->ownedStoryId, $tmpEndpoint);
    }

    private function clearStorage()
    {
        Storage::disk($this->diskName)->deleteDirectory($this->testPatientId);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $patient = Patient::find($this->testPatientId);
        $this->ownedStoryId = $patient->albums()->get()->values()->first()->stories()->first()->id;
        $this->endpoint = $this->getPopulatedEndpoint();
        $this->specificEndpoint = "$this->endpoint/$this->ownedStoryId.jpg";

        Storage::fake($this->diskName);
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->postJson($this->endpoint, [], $headers)
            ->assertStatus(401);
    }

    public function testGetImageStoryAsset($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        } else {
            $this->testAddImageAssetToStory();
        }
        $response = $this->getJson($endpoint, $this->headers)
            ->assertStatus(200);
    }

    public function testGetImageStoryAssetWithInvalidPatientId()
    {
        $endpoint = $this->getPopulatedEndpoint($this->nonExistentPatientId) . "/$this->ownedStoryId.jpg";
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetImageStoryAssetWithInvalidStoryId()
    {
        $endpoint = $this->getPopulatedEndpoint($this->testPatientId, 999) . "/$this->ownedStoryId.jpg";
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetYoutubeStoryAsset($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        } else {
            $this->testAddYoutubeAssetToStory();
        }
        $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => [ 'id', 'source', 'type' ]
            ])
            ->assertStatus(200);
    }

    public function testAddImageAssetToStory()
    {
        $extensions = ['jpg', 'gif', 'png'];
        foreach ($extensions as $extension) {
            $this->clearStorage();
            $body = [ 'asset' => UploadedFile::fake()->image("image.$extension") ];
            $response = $this->postJson($this->endpoint, $body, $this->headers)
                ->assertJsonStructure([
                    'meta' => $this->metaCreatedResponseStructure,
                    'response' => [ 'id' ]
                ])
                ->assertStatus(201)
                ->getData();
            $this->testGetImageStoryAsset($response->meta->location);
        }
    }

    public function testAddImageAssetToStoryWithoutAssetTypeSpecified()
    {
        $this->clearStorage();
        $body = [ 'asset' => UploadedFile::fake()->image("image.jpg") ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => [ 'id' ]
            ])
            ->assertStatus(201);
    }

    public function testAddYoutubeAssetToStory()
    {
        $body = [ 'asset' => $this->youtubeAsset, 'assetType' => 'youtube' ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => [ 'id' ]
            ])
            ->assertStatus(201)
            ->getData();
        $this->testGetYoutubeStoryAsset($response->meta->location);
    }

    public function testAddYoutubeAssetToStoryWithoutAssetTypeSpecified()
    {
        $body = [ 'asset' => $this->youtubeAsset ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }
}
