<?php

namespace Tests;

use JWTAuth;
use App\User;
use App\Patient;
use App\Album;
use App\Heritage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $metaResponseStructure = ['code', 'message'];
    protected $metaCreatedResponseStructure = ['code', 'message', 'location'];
    protected $exceptionResponseStructure = [
        'meta' => ['code', 'message'],
        'response' => []
    ];

    protected $headers = [
        'HTTP_Authorization' => 'Bearer <token>'
    ];

    protected $testPatientId = 2;
    protected $testUserId = 2;
    protected $privatePatientId = 3;
    protected $nonExistentPatientId = 123;

    protected $numberOfUsers = 5;
    protected $numberOfPatients = 5;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');
        $this->seedDatabase();
    }

    protected function authenticate($user = null)
    {
        if (!$user) {
            $user = User::find($this->testUserId);
        }
        $token = JWTAuth::fromUser($user);
        $this->headers['HTTP_Authorization'] = 'Bearer ' . $token;
        $this->refreshApplication();
    }

    public function seedDatabase()
    {
        $this->seedHeritage();
        $this->seedDefaults();
        $this->seedUsers();
        $this->seedPatients();
        $this->seedPatientUsers();
    }

    private function seedDefaults()
    {
        $user = factory(User::class)->create([
            'email' => 'testing@prisma.care',
            'password' => Hash::make('testing@prisma.care')
        ]);
        $patient = factory(Patient::class)->create([
            'first_name' => 'Patient',
            'last_name' => 'Testing'
        ]);
        $patient->prepopulate();
        $patient->users()->attach($user->id);
    }

    public function seedUsers($numberOfUsers = 5)
    {
        factory(User::class, $numberOfUsers)->create();
    }

    public function seedPatients($numberOfPatients = 5)
    {
        $patients = factory(Patient::class, $numberOfPatients)->create();
        foreach ($patients as $patient) {
            $patient->prepopulate();
        }
    }

    public function seedPatientUsers()
    {
        $patient = Patient::find($this->testPatientId);
        $patient->users()->attach($this->testUserId);
        $patient = Patient::find(5);
        $patient->users()->attach($this->testUserId);
    }

    public function seedHeritage()
    {
        $defaultAlbums = factory(Album::class, 5)->create();
        foreach ($defaultAlbums as $album) {
            factory(Heritage::class, 3)->create(['album_id' => $album->id]);
        }
    }

    /**
     * Returns everything after the third slash in a string
     * So for URL 'https://localhost/v1/something/1', it returns 'v1/something/1'
     */
    protected function parseResourceLocation($location)
    {
        return substr($location, (strpos($location, '/', strpos($location, '/') + 2) + 1));
    }

    protected function disconnectTestUserFromTestPatient()
    {
        Patient::find($this->testPatientId)->users()->detach($this->testUserId);
    }
}
