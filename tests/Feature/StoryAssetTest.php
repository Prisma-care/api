<?php

namespace Tests\Feature;

use App\Story;
use App\Patient;
use Tests\TestCase;
use Tests\Utils\Image;
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

    private function generateImageAssetForPatient($patientId, $extension = null)
    {
        $extension = $extension ?: 'jpg';
        $img = Image::generate($extension);
        $path = "$patientId/$this->ownedStoryId/$this->ownedStoryId.$extension";
        Storage::disk($this->diskName)->put($path, $img);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $patient = Patient::find($this->testPatientId);
        $this->ownedStoryId = $patient->albums()->get()->values()->first()->stories()->first()->id;
        $this->endpoint = $this->getPopulatedEndpoint();

        Storage::fake($this->diskName);
        $this->generateImageAssetForPatient($patient->id);

        $this->specificEndpoint = "$this->endpoint/$this->ownedStoryId.jpg";
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->postJson($this->endpoint, [], $headers)
            ->assertStatus(401);
    }

    public function testGetStoryImageAsset($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        }
        $response = $this->getJson($endpoint, $this->headers)
            ->assertStatus(200);
    }

    public function testGetStoryImageAssetWithInvalidPatientId()
    {
        $endpoint = $this->getPopulatedEndpoint($this->nonExistentPatientId) . "/$this->ownedStoryId.jpg";
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetStoryImageAssetWithInvalidStoryId()
    {
        $endpoint = $this->getPopulatedEndpoint($this->testPatientId, 999) . "/$this->ownedStoryId.jpg";
        $response = $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testAddImageAssetToStory()
    {
        $extensions = ['jpg', 'gif', 'png'];
        foreach ($extensions as $extension) {
            $body = [ 'asset' => UploadedFile::fake()->image("image.$extension") ];
            $response = $this->postJson($this->endpoint, $body, $this->headers)
                ->assertJsonStructure([
                    'meta' => $this->metaCreatedResponseStructure,
                    'response' => [ 'id' ]
                ])
                ->assertStatus(201)
                ->getData();
            $this->testGetStoryImageAsset($response->meta->location);
        }
    }

    public function testAddImageAssetToStoryWithoutAssetTypeSpecified()
    {
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
        $this->testGetStoryImageAsset($response->meta->location);
    }

    public function testAddYoutubeAssetToStoryWithoutAssetTypeSpecified()
    {
        $body = [ 'asset' => $this->youtubeAsset ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }
}
