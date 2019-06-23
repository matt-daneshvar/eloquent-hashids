<?php

use Illuminate\Database\Eloquent\Model;
use MattDaneshvar\EloquentHashids\Hashid;
use MattDaneshvar\EloquentHashids\HashidRouting;

class TestModel extends Model
{
    use Hashid, HashidRouting;

    protected $table = 'test_models';
}
