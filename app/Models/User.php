<?php

namespace App\Models;

// INI KUNCI UTAMANYA: Panggil mesin Auth khusus MongoDB
use MongoDB\Laravel\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Pastikan ngarah ke MongoDB
    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];
}