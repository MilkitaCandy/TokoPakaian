<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Pakaian extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pakaian';
    protected $guarded = [];
}
