<?php

namespace MattDaneshvar\EloquentHashids;

trait HashidRouteKeyName
{
    /**
     * Change model's route key name to the hash_id column.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hashid';
    }
}