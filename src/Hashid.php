<?php

namespace MattDaneshvar\EloquentHashids;

use Hashids\Hashids;

trait Hashid
{
    /**
     * You may override the default behaviour of this trait by
     * specifying any of the static properties listed below:.
     *
     * protected static $hashidColumn = 'hashid';
     * protected static $hashidMinLength = 8;
     * protected static $hashidChars = 'abcdefghijklmnopqrstuvwxyz1234567890';
     * protected static $hashidSalt = 'your unique salt';
     * protected static $hashidKey = 'id';
     */

    /**
     * Hashids instance.
     *
     * @var \Hashids\Hashids
     */
    protected static $hashidsInstance;

    /**
     * Default Hashids instance.
     * Used when $hashidInstance no present.
     *
     * @var \Hashids\Hashids
     */
    protected static $defaultHashidsInstance;

    /**
     * Default Hashids column.
     * Used when $hashidColumn no present.
     *
     * @var \Hashids\Hashids
     */
    protected static $defaultHashidColumn = 'hashid';

    /**
     * Listen for model's 'created' event to generate hashid.
     *
     * @return void
     */
    protected static function bootHashid()
    {
        static::created(function ($model) {
            $model->generateHashId();
        });
    }

    /**
     * Generate and persist hashid.
     *
     * @return void
     */
    public function generateHashId()
    {
        $key = isset(static::$hashidKey) ? $this->{static::$hashidKey} : $this->id;
        $this->{static::$hashidColumn ?? static::$defaultHashidColumn} = static::getHashIdInstance()->encode($key);
        $this->save();
    }

    /**
     * Get Hashids instance.
     *
     * @return \Hashids\Hashids
     */
    protected static function getHashIdInstance()
    {
        if (isset(static::$hashidsInstance)) {
            return static::$hashidsInstance;
        }

        return static::$defaultHashidsInstance = new Hashids(
            static::$hashidSalt ?? (new self)->getTable(),
            static::$hashidMinLength ?? 8,
            static::$hashidChars ?? 'abcdefghijklmnopqrstuvwxyz1234567890'
        );
    }

    /**
     * Find model by hashid.
     *
     * @param $hashid
     * @return mixed
     */
    public static function findByHashId($hashid)
    {
        return static::where(static::$hashidColumn ?? static::$defaultHashidColumn, $hashid)->first();
    }

    /**
     * Find model by hashid; otherwise throw an exception.
     *
     * @param $hashid
     * @return mixed
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findByHashIdOrFail($hashid)
    {
        return static::where(static::$hashidColumn ?? static::$defaultHashidColumn, $hashid)->firstOrFail();
    }
}
