<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Stubs\UserStub;

class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup DB before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');
        $this->app['config']->set('app.key', 'base64:jkHe+0IUIG1lp9d2LIaLjcsRZg4TIZ5Ccya2g/8ByGs=');
        if (empty($this->config)) {
            $this->config = require __DIR__.'/../config/referral.php';
        }
        $this->app['config']->set('referral', $this->config);
        $this->migrate();
        $this->seedDatabase();
    }

    /**
     * run package database migrations.
     */
    public function migrate()
    {
        $fileSystem = new Filesystem();
        foreach ($fileSystem->files(__DIR__.'/../tests/database/migrations') as $file) {
            $fileSystem->requireOnce($file);
        }
        (new \CreateUsersTable())->up();
        (new \AddAffiliateIdToUsersTable())->up();
        (new \CreateReferralsTable())->up();
    }

    /**
     * Seed testing database.
     */
    public function seedDatabase()
    {
        UserStub::create([
            'name' => 'questocat',
            'email' => 'questocat@test.com',
            'password' => bcrypt('secret'),
        ]);
        UserStub::create([
            'name' => 'zhengchaopu',
            'email' => 'zhengchaopu@gmail.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
