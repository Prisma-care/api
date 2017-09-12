<?php

namespace Tests\Feature;

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
}
