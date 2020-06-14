<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    // TODO: VERIFIED_USER & UNVERIFIED_USER could be integers and ADMIN_USER, REGULAT_USER could be boolean
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', 
    ];

    // TODO: We can write the login in the following functions and the constants used in UserFactory directly inside the Factories
    public function isVerified() {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin() {
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode() {
        return Str::random(40); // TODO: Check how to make this a Pseudo Random number or Cryptographically secure number
    }

    // Mutators
    public function serNameAttribute($name) {
        $this->attributes['name'] = Str::lower($name);
    }

    public function setEmailAttribute($email) {
        $this->attributes['email'] = Str::lower($email);
    }

    // Accessors
    public function getNameAttribute($name) {
        return ucwords($name);
    }   
}
