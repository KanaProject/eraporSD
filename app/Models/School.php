<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name', 'npsn', 'address', 'phone', 'email',
        'principal_name', 'principal_nip', 'logo_path', 'city', 'province',
    ];

    /** Singleton: always return or create the single school record */
    public static function getInstance(): static
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
