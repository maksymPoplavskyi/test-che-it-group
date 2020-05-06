<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    protected $connection = 'mysql';

    protected $table = 'loggings';

    public $timestamps = false;

    protected $fillable = [];
}
