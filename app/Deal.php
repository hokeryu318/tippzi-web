<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    //
    protected $primaryKey = 'Id';
    protected $table = 'deal';
    public $timestamps = false;
}
