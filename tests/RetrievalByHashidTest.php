<?php

namespace MattDaneshvar\Hashids\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MattDaneshvar\EloquentHashids\Hashid;
use Orchestra\Testbench\TestCase;

class RetrievalByHashidTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /** @test */
    public function it_can_find_the_model_by_its_hashid()
    {
        $model = new class extends Model
        {
            use Hashid;

            protected $table = 'test_models';
        };

        $model->save();

        $result = $model->findByHashid($model->hashid);
        
        $this->assertNotNull($result);

        $this->assertEquals($model->id, $result->id);
    }

    /** @test */
    public function it_can_find_the_model_by_its_hashid_with_custom_column()
    {
        $model = new class extends Model
        {
            use Hashid;

            protected $table = 'test_models';

            protected static $hashidColumn = 'name';
        };

        $model->save();

        $result = $model->findByHashid($model->name);

        $this->assertNotNull($result);

        $this->assertEquals($model->id, $result->id);
    }

    /** @test */
    public function it_fails_if_no_model_matches_the_specified_hashid()
    {
        $model = new class extends Model
        {
            use Hashid;

            protected $table = 'test_models';
        };

        $model->save();

        $this->expectException(ModelNotFoundException::class);
        
        $model->findByHashidOrFail('random string');
    }
}


