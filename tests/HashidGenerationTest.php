<?php

namespace MattDaneshvar\Hashids\Tests;

use Hashids\Hashids;
use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Model;
use MattDaneshvar\EloquentHashids\Hashid;

class HashidGenerationTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * The default salt value EloquentHashids use. This is
     * by default computed based on the model's table name.
     *
     *
     * @var string
     */
    protected $defaultSalt = 'test_models';

    /**
     * The default min length value EloquentHashids use.
     *
     * @var string
     */
    protected $defaultMinLength = 8;

    /**
     * The default characters EloquentHashids use.
     *
     * @var string
     */
    protected $defaultChars = 'abcdefghijklmnopqrstuvwxyz1234567890';

    /** @test */
    public function it_persists_a_hashid_to_newly_created_models()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';
        };

        $model->save();

        $model->fresh();

        $this->assertNotNull($model->hashid);
    }

    /** @test */
    public function it_respects_the_specified_hashid_column()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';

            protected static $hashidColumn = 'name';
        };

        $model->save();

        $hashidInstance = new Hashids($this->defaultSalt, $this->defaultMinLength, $this->defaultChars);

        $this->assertEquals($hashidInstance->encode(1), $model->name);
    }

    /** @test */
    public function it_respects_the_specified_hashid_minlength()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';

            protected static $hashidMinLength = 20;
        };

        $model->save();

        $this->assertGreaterThanOrEqual(20, strlen($model->hashid));
    }

    /** @test */
    public function it_respects_the_specified_hashid_chars()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';

            protected static $hashidChars = 'ABCDEFGHIJKLMNOPQRST';
        };

        $model->save();

        $hashidInstance = new Hashids($this->defaultSalt, $this->defaultMinLength, 'ABCDEFGHIJKLMNOPQRST');

        $this->assertEquals($hashidInstance->encode(1), $model->hashid);
    }

    /** @test */
    public function it_respects_the_specified_hashid_salt()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';

            protected static $hashidSalt = 'salt and pepper';
        };

        $model->save();

        $hashidInstance = new Hashids('salt and pepper', $this->defaultMinLength, $this->defaultChars);

        $this->assertEquals($hashidInstance->encode(1), $model->hashid);
    }

    /** @test */
    public function it_respects_the_specified_hashid_key()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';

            protected static $hashidKey = 'name';
        };

        $model->name = 'John Doe';
        $model->save();

        $hashidInstance = new Hashids($this->defaultSalt, $this->defaultMinLength, $this->defaultChars);

        $this->assertEquals($hashidInstance->encode($model->name), $model->hashid);
    }

    /** @test */
    public function it_respects_the_specified_hashid_instance()
    {
        $model = new class extends Model {
            use Hashid;

            protected $table = 'test_models';

            public static function boot()
            {
                parent::boot();

                static::$hashidsInstance = new Hashids('salt and pepper', 5);
            }
        };

        $model->save();

        $hashids = new Hashids('salt and pepper', 5);

        $this->assertEquals($hashids->encode(1), $model->hashid);
    }
}
