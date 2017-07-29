<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $exceptionResponseStructure = [
		'meta' => [ 'code', 'message' ],
		'response' => []
    ];

    public function setUp()
	{
		parent::setUp();
		$this->artisan('migrate:refresh');
	    $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
	}
}
