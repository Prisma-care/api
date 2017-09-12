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
                 'response' => [ '*' => $this->baseObjectStructure ]
             ])
             ->assertStatus(200);
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
        $body = [ 'title' => str_random(16) ];
        $response = $this->postJson($this->endpoint, $body, $this->headers)
            ->assertJsonStructure([
                'meta' => $this->metaCreatedResponseStructure,
                'response' => [ 'id', 'title' ]
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
            $body = [ 'title' => str_random(16) ];
            $this->postJson($this->endpoint, $body, $this->headers)
                 ->assertStatus(403);
        }
    }
}
