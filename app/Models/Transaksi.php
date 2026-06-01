<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Transaksi extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transaksi'; 
    protected $guarded = []; 
}