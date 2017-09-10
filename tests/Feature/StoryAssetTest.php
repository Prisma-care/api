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
    private $baseEndpoint = 'v1/patient/{patientId}/story/{storyId}/asset';
    private $diskName = 'story-asset-tests';
    private $endpoint;
    private $ownedStoryId;
    private $storagePath;

    private function getPopulatedEndpoint($patientId = null, $storyId = null)
    {
        $tmpEndpoint = str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
        return str_replace('{storyId}', $storyId ?: $this->ownedStoryId, $tmpEndpoint);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $patient = Patient::find($this->testPatientId);
        $this->ownedStoryId = $patient->albums()->get()->values()->first()->stories()->first()->id;
        $this->endpoint = $this->getPopulatedEndpoint();

        Storage::fake($this->diskName);
        $this->storagePath = "$patient->id/$this->ownedStoryId";
        UploadedFile::fake()->image("$this->storagePath/$this->ownedStoryId.jpg");
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->postJson($this->endpoint, [], $headers)
            ->assertStatus(401);
    }
}
