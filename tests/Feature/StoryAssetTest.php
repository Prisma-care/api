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
    private $storagePath;
    private $pathToStaticImage;

    private $baseEndpoint = 'v1/patient/{patientId}/story/{storyId}/asset';
    private $endpoint;
    private $specificEndpoint;

    private $ownedStoryId;

    private function getPopulatedEndpoint($patientId = null, $storyId = null)
    {
        $tmpEndpoint = str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
        return str_replace('{storyId}', $storyId ?: $this->ownedStoryId, $tmpEndpoint);
    }

    private function setUpStorage()
    {
        Storage::fake($this->diskName);
        $this->storagePath = Storage::disk($this->diskName)
                                ->getDriver()->getAdapter()->getPathPrefix();
    }

    private function generateImageAssetForPatient($patientId, $extension = null)
    {
        $hierarchy = "$patientId/$this->ownedStoryId";
        Storage::disk($this->diskName)->makeDirectory($hierarchy);

        $fileName = $this->ownedStoryId . '.' . $extension ?: 'jpg';
        $fullPath = $this->storagePath  . "$hierarchy/$fileName";
        Image::generate($fullPath);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $patient = Patient::find($this->testPatientId);
        $this->ownedStoryId = $patient->albums()->get()->values()->first()->stories()->first()->id;
        $this->endpoint = $this->getPopulatedEndpoint();

        $this->setUpStorage();
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
}
