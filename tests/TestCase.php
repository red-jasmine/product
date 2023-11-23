<?php

namespace RedJasmine\Product\Tests;

use function Orchestra\Testbench\artisan;


class TestCase extends \Orchestra\Testbench\TestCase
{


    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function defineEnvironment($app)
    {

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('app.debug', true);


    }

    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;


    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate');

        // $this->beforeApplicationDestroyed(
        //     //fn () => artisan($this, 'migrate:rollback')
        // );

    }


}
