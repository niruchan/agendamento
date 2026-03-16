<?php

namespace App\Models;

// use App\Models\Appointment; // ← これももしあったら消してOKです
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * リレーション設定：UserはたくさんのAppointmentを持っています
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}