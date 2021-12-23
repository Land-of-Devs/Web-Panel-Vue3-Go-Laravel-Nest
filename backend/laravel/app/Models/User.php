<?php

namespace App\Models;

use App\Domain\Interfaces\Users\UserEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, UserEntity
{
    use Notifiable, HasFactory;

    protected $table = 'users';
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'username', 'email'
    ];

    protected $casts = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'two_step_secret'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'AdminAccessToken' => 0,
        ];
    }

    public function getUuid(): ?string
    {
        return $this->getKey();
    }

    public function getRole(): int
    {
        return $this->attributes['role'] ?? 0;
    }

    public function getName(): ?string
    {
        return $this->attributes['username'] ?? null;
    }

    public function setName(string $name)
    {
        $this->attributes['username'] = $name;
    }

    public function getEmail(): ?string
    {
        return $this->attributes['email'] ?? null;
    }

    public function setEmail(string $email)
    {
        $this->attributes['email'] = $email;
    }

    public function getTwoStepSecret(): ?string
    {
        return $this->attributes['two_step_secret'] ?? null;
    }
}
