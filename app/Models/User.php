<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function putRateMark()
{
    $throw_Times = $this->games()->count();
    $number_All_Games = intval($throw_Times);

    $won_times = $this->games()->where('win', true)->count();
    $numberGetSeven = intval($won_times);

    $rate = round(($numberGetSeven / $number_All_Games) * 100, 2);
    $this->rate = $rate;
    $this->save();

    return $rate;
}

            

}
