<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Only run migrations if using MySQL
        if (config('database.default') === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->artisan('migrate:fresh');
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}