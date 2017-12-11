<?php

namespace Tests\Feature;

use App\User;
use App\Album;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefaultAlbumTest extends TestCase
{
    private $endpoint = 'v1/album';
    private $specificEndpoint;
    private $baseObject = [
        'id' => null,
        'title' => 'Sports',
        'heritage' => [
            [
              'id' => 1,
              'description' => 'A description',
              'asset_type' => 'image',
              'asset_name' => null,
              'album_id' => 0
            ]
        ]
    ];

    private $baseObjectStructure;

    public function loginAsSuperAdmin()
    {
        $user = factory(User::class)->create(['user_type' => 'superadmin']);
        $this->authenticate($user);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $this->baseObjectStructure = array_merge(
            array_keys($this->baseObject),
            [
              'heritage' => [
                '*' => array_keys($this->baseObject['heritage'][0])
              ]
            ]
        );
        $defaultAlbum = Album::with('heritage')->get()
                            ->where('patient_id', '=', null)->values()->first();
        $this->specificEndpoint = "$this->endpoint/$defaultAlbum->id";
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->getJson($this->endpoint, $headers)
            ->assertStatus(401);
    }

    public function testIndexDefaultAlbums()
    {
        $this->getJson($this->endpoint, $this->headers)
             ->assertJsonStructure([
                 'meta' => $this->metaResponseStructure,
                 'response' => ['*' => $this->baseObjectStructure]
             ])
             ->assertStatus(200);
    }

    public function testIndexDefaultAlbumsDoesntShowNormalAlbums()
    {
        $albums = $this->getJson($this->endpoint, $this->headers)->getData()->response;
        foreach ($albums as $album) {
            $this->assertEquals($album->patient_id, null);
        }
    }

    public function testGetDefaultAlbum($location = null)
    {
        $endpoint = $this->specificEndpoint;
        if ($location) {
            $endpoint = $this->parseResourceLocation($location);
        }
        $this->getJson($endpoint, $this->headers)
             ->assertJsonStructure([
                 'meta' => $this->metaResponseStructure,
                 'response' => $this->baseObjectStructure
             ])
             ->assertStatus(200);
    }

    public function testCreateDefaultAlbum()
    {
        $this->loginAsSuperAdmin();
        $body = ['title' => str_random(16)];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => ['id', 'title']
            ])
            ->assertStatus(201)
            ->getData();
        $this->testGetDefaultAlbum($response->meta->location);
    }

    public function testOnlySuperAdminCanCreateDefaultAlbum()
    {
        // copy user types without superadmin
        $userTypes = array_diff($this->userTypes, ['superadmin']);
        foreach ($userTypes as $userType) {
            $user = factory(User::class)->create(['user_type' => $userType]);
            $this->authenticate($user);
            $this->postJson($this->endpoint, ['title' => str_random(16)], $this->headers)
                 ->assertStatus(403);
        }
    }

    public function testUpdateAlbum()
    {
        $album = factory(Album::class)->create();
        $endpoint = $this->endpoint.'/'.$album->id;
        $newTitle = str_random(20);
        $this->loginAsSuperAdmin();
        $this->patchJson($endpoint, ['title' => $newTitle], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => []
            ])
            ->assertStatus(200);
        $album = Album::find($album->id);
        $this->assertEquals($album->title, $newTitle);
    }

    public function testOnlySuperAdminCanUpdateDefaultAlbum()
    {
        $this->patchJson($this->specificEndpoint, ['title' => str_random(16)], $this->headers)
            ->assertStatus(403);
    }

    public function testRouteDoesntAllowNormalAlbumUpdate()
    {
        $album = factory(Album::class)->create(['patient_id' => $this->testPatientId]);
        $this->loginAsSuperAdmin();
        $endpoint = $this->endpoint.'/'.$album->id;
        $this->patchJson($endpoint, ['title' => str_random(20)], $this->headers)
            ->assertJsonStructure($this->exceptionResponseStructure)
            ->assertStatus(400);
    }

    public function testDeleteDefaultAlbum()
    {
        $this->loginAsSuperAdmin();
        $response = $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaResponseStructure,
                'response' => []
            ])
            ->assertStatus(200);
    }

    public function testOnlySuperAdminCanDeleteDefaultAlbum()
    {
        $this->deleteJson($this->specificEndpoint, [], $this->headers)
            ->assertStatus(403);
    }

    public function testRouteDoesntAllowNormalAlbumDeletion()
    {
        $album = factory(Album::class)->create(['patient_id' => $this->testPatientId]);
        $this->loginAsSuperAdmin();
        $endpoint = $this->endpoint.'/'.$album->id;
        $this->deleteJson($endpoint, [], $this->headers)
            ->assertStatus(400);
    }
}
