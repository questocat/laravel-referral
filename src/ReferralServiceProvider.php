<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Questocat\Referral;

use Illuminate\Support\ServiceProvider;

class ReferralServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->setupConfig();
        $this->setupMigrations();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/referral.php');

        $this->publishes([
            $source => config_path('referral.php'),
        ], 'config');

        $this->mergeConfigFrom($source, 'referral');
    }

    /**
     * Setup the migrations.
     */
    protected function setupMigrations()
    {
        $timestamp = date('Y_m_d_His');
        $migrationsSource = realpath(__DIR__.'/../database/migrations/add_referral_to_users_table.php');
        $migrationsTarget = database_path("/migrations/{$timestamp}_add_referral_to_users_table.php");

        $this->publishes([
            $migrationsSource => $migrationsTarget,
        ], 'migrations');
    }
}
