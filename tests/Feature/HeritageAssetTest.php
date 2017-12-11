<?php

namespace Tests\Feature;

use App\User;
use App\Album;
use App\Heritage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HeritageAssetTest extends TestCase
{
    private $diskName = 'heritage';

    private $baseEndpoint = 'v1/album/{albumId}/heritage/{heritageId}/asset';
    private $endpoint;
    private $specificEndpoint;

    private $testAlbumId;
    private $testHeritageId;
    private $youtubeAsset = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';

    private function getPopulatedEndpoint($albumId = null, $heritageId = null)
    {
        $tmpEndpoint = str_replace('{albumId}', $albumId ?: $this->testAlbumId, $this->baseEndpoint);

        return str_replace('{heritageId}', $heritageId ?: $this->testHeritageId, $tmpEndpoint);
    }

    private function clearStorage()
    {
        Storage::disk($this->diskName)->deleteDirectory($this->testHeritageId);
    }

    public function setUp()
    {
        parent::setUp();
        $user = factory(User::class)->create(['user_type' => 'superadmin']);
        $this->authenticate($user);
        $defaultAlbum = Album::with('heritage')->get()
                                ->where('patient_id', '=', null)->values()->first();
        $this->testAlbumId = $defaultAlbum->id;
        $this->testHeritageId = $defaultAlbum->heritage()->first()->id;
        $this->endpoint = $this->getPopulatedEndpoint();
        $this->specificEndpoint = "$this->endpoint/$this->testHeritageId.jpeg";

        Storage::fake($this->diskName);
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $this->postJson($this->endpoint, [], $headers)
            ->assertStatus(401);
    }

    public function testGetImageHeritageAsset($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        } else {
            $body = ['asset' => UploadedFile::fake()->image('image.jpeg')];
            $this->postJson($this->endpoint, $body, $this->headers);
        }
        $this->getJson($endpoint, $this->headers)
            ->assertStatus(200);
    }

    public function testGetImageHeritageAssetWithInvalidAlbumId()
    {
        $endpoint = $this->getPopulatedEndpoint(999)."/$this->testHeritageId.jpg";
        $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetImageHeritageAssetWithInvalidHeritageId()
    {
        $endpoint = $this->getPopulatedEndpoint($this->testAlbumId, 999)."/$this->testHeritageId.jpg";
        $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testGetYoutubeHeritageAsset($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        } else {
            $this->testAddYoutubeAssetToHeritage();
        }
        $this->getJson($endpoint, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => ['id', 'source', 'type'],
            ])
            ->assertStatus(200);
    }

    public function testEveryUserTypeCanGetHeritageAsset()
    {
        foreach ($this->userTypes as $userType) {
            $user = factory(User::class)->create(['user_type' => $userType]);
            $this->authenticate($user);
            $this->testGetImageHeritageAsset();
        }
    }

    public function testAddHeritageAssetToHeritage()
    {
        $extensions = ['jpg', 'gif', 'png'];
        foreach ($extensions as $extension) {
            $this->clearStorage();
            $body = ['asset' => UploadedFile::fake()->image("image.$extension")];
            $response = $this->postJson($this->endpoint, $body, $this->headers)
                ->assertJsonStructure([
                    'meta' => $this->metaCreatedResponseStructure,
                    'response' => ['id'],
                ])
                ->assertStatus(201)
                ->getData();
            $this->testGetImageHeritageAsset($response->meta->location);
        }
    }

    public function testAddImageAssetToHeritageWithoutAssetTypeSpecified()
    {
        $this->clearStorage();
        $body = ['asset' => UploadedFile::fake()->image('image.jpg')];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => ['id'],
            ])
            ->assertStatus(201);
    }

    public function testAddYoutubeAssetToHeritage()
    {
        $body = ['asset' => $this->youtubeAsset, 'assetType' => 'youtube'];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => ['id'],
            ])
            ->assertStatus(201)
            ->getData();
        $this->testGetYoutubeHeritageAsset($response->meta->location);
    }

    public function testAddYoutubeAssetToHeritageWithoutAssetTypeSpecified()
    {
        $body = ['asset' => $this->youtubeAsset];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testOnlySuperadminCanAddHeritageAsset()
    {
        // copy user types without superadmin
        $userTypes = array_diff($this->userTypes, ['superadmin']);
        foreach ($userTypes as $userType) {
            $user = factory(User::class)->create(['user_type' => $userType]);
            $this->authenticate($user);
            $this->postJson($this->endpoint, ['asset' => UploadedFile::fake()->image('image.jpg')], $this->headers)
                ->assertStatus(403);
        }
    }
}
