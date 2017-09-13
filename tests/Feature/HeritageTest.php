<?php

namespace Tests\Feature;

use App\Story;
use App\Album;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HeritageTest extends TestCase
{
    private $baseEndpoint = 'v1/album/{albumId}/heritage';
    private $endpoint;
    private $specificEndpoint;

    private $defaultAlbumId;
    private $testHeritageId;
    private $baseObject = [
        'id' => null,
        'description' => 'A description',
        'happened_at' => null,
        'album_id' => 1,
        'asset_name' => null,
        'asset_type' => 'image'
    ];

    private function getEndpointWithAlbumId($albumId = null)
    {
        return str_replace('{albumId}', $albumId ?: $this->defaultAlbumId, $this->baseEndpoint);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $defaultAlbum = Album::with('heritage')->get()
                            ->where('patient_id', '=', null)->values()->first();
        $this->defaultAlbumId = $defaultAlbum->id;
        $this->testHeritageId = $defaultAlbum->heritage()->first()->id;
        $this->baseObject['album_id'] = $this->defaultAlbumId;
        $this->endpoint = $this->getEndpointWithAlbumId();
        $this->specificEndpoint = "$this->endpoint/$this->testHeritageId";
    }
}
