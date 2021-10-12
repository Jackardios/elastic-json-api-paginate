<?php

namespace Jackardios\ElasticJsonApiPaginate\Tests;

use Carbon\Carbon;
use ElasticClient\ServiceProvider as ElasticClientServiceProvider;
use ElasticScoutDriver\ServiceProvider as ElasticScoutDriverServiceProvider;
use ElasticScoutDriverPlus\ServiceProvider as ElasticScoutDriverPlusServiceProvider;
use ElasticScoutDriverPlus\Support\Query;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Jackardios\ElasticJsonApiPaginate\JsonApiPaginateServiceProvider;
use Laravel\Scout\ScoutServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create('2017', '1', '1', '1', '1', '1'));

        $this->setUpDatabase($this->app);
        $this->setUpRoutes($this->app);
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            ScoutServiceProvider::class,
            ElasticClientServiceProvider::class,
            ElasticScoutDriverServiceProvider::class,
            ElasticScoutDriverPlusServiceProvider::class,
            JsonApiPaginateServiceProvider::class
        ];
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): Application
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('scout.driver', 'elastic');
        $app['config']->set('elastic.scout_driver.refresh_documents', true);

        return $app;
    }

    /**
     * @param Application $app
     */
    protected function setUpDatabase($app): Application
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->rememberToken();
            $table->timestamps();
        });

        foreach (range(1, 40) as $index) {
            TestModel::create(['name' => "user{$index}"]);
        }

        return $app;
    }

    protected function setUpRoutes(Application $app): void
    {
        Route::any('/', function () {
            return TestModel::searchQuery(Query::matchAll())->jsonPaginate();
        });
    }
}
