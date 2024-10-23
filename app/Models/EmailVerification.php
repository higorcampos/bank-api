<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailVerification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'token'
    ];

    public static function generateToken($userId)
    {
        $token = Str::random(60);

        self::create([
            'user_id' => $userId,
            'token' => $token,
        ]);

        return $token;
    }
}
