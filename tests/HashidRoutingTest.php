<?php

namespace MattDaneshvar\Hashids\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\SubstituteBindings;

class HashidRoutingTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->withMiddleware(SubstituteBindings::class);
    }

    /** @test */
    public function it_maps_hashid_as_the_route_key()
    {
        $model = new \TestModel();

        Route::get('test_models/{model}', function (\TestModel $model) {
            return "You're viewing test model #{$model->id}.";
        })->middleware(SubstituteBindings::class);

        $model->save();

        $this->get("test_models/{$model->hashid}")->assertStatus(200);
    }
}
