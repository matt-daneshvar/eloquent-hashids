<?php

namespace MattDaneshvar\EloquentHashids;

trait HashidRouting
{
    /**
     * Change model's route key name to the hash_id column.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return static::$hashidColumn ?? static::$defaultHashidColumn;
    }
}
