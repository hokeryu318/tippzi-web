<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bar extends Model
{
    //
    protected $table = 'bar';
    protected $primaryKey = 'Id';
    public $timestamps = false;
}
