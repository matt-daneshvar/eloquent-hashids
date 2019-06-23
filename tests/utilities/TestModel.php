<?php

use Illuminate\Database\Eloquent\Model;
use MattDaneshvar\EloquentHashids\Hashid;
use MattDaneshvar\EloquentHashids\HashidRouteKeyName;

class TestModel extends Model
{
    use Hashid, HashidRouteKeyName;

    protected $table = 'test_models';
}