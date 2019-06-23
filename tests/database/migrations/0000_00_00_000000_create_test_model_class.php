<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestModelClass extends Migration
{
    public  function up()
    {
        Schema::create('test_models', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('hashid')->nullable();
            $table->timestamps();
        });
    }
}