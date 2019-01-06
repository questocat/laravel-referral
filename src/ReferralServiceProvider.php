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
     * Bootstrap the application events.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->setupConfig();
            $this->setupMigrations();
        }
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
        $source = __DIR__.'/../config/referral.php';

        $this->publishes([
            $source => config_path('referral.php'),
        ], 'referral-config');

        $this->mergeConfigFrom($source, 'referral');
    }

    /**
     * Setup the migrations.
     */
    protected function setupMigrations()
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'referral-migrations');
    }
}
