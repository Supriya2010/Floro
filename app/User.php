<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\UserActivity;
use App\Models\AuthenticationLog;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password','address', 
         'house_number', 'postal_code','city', 'telephone_number', 'is_active',
     ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function twoFactorAuth()
    {
        return $this->hasOne(TwoFactorAuth::class);
    }

    /**
     * Eloquent relation with "two_factor_backups"
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function twoFactorAuthBackups()
    {
        return $this->hasMany(TwoFactorBackup::class);
    }

    /**
     * Eloquent relation to get the user history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function userHistory()
    {
        return $this->MorphMany(UserActivity::class, 'entity')->with('modifiedBy')
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Relation to get last login information of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLastLoginDetails()
    {
        return $this->hasMany(AuthenticationLog::class)->orderBy('created_at', 'desc')->limit(1);
    }
}
