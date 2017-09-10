<?php

namespace Tests\Feature;

use App\Story;
use App\Patient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoryAssetTest extends TestCase
{
    private $ownedStoryId;
    private $baseEndpoint = 'v1/patient/{patientId}/story/{storyId}/asset';
    private $endpoint;

    private function getPopulatedEndpoint($patientId = null, $storyId = null)
    {
        $tmpEndpoint = str_replace('{patientId}', $patientId ?: $this->testPatientId, $this->baseEndpoint);
        return str_replace('{storyId}', $storyId ?: $this->ownedStoryId, $tmpEndpoint);
    }

    public function setUp()
    {
        parent::setUp();
        $this->authenticate();
        $this->ownedStoryId = Patient::find($this->testPatientId)
                                ->albums()->get()->values()->first()
                                ->stories()->first()->id;
        $this->endpoint = $this->getPopulatedEndpoint();
    }

    public function testResourceIsProtected()
    {
        $headers = $this->headers;
        unset($headers['HTTP_Authorization']);
        $response = $this->postJson($this->endpoint, [], $headers)
            ->assertStatus(401);
    }
}
