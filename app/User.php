<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App
 * @property integer $id
 * @property $firstname
 * @property $lastname
 * @property string $email
 * @property string $password
 * @property-read Task[]|Collection $tasks
 */

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password',
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
     * @return string
     */
    public function getFullnameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function employers()
    {
        $this->belongsToMany(self::class, 'teams', 'owner_id', 'employer_id');
    }

    public function owners()
    {
        $this->belongsToMany(self::class, 'teams', 'employer_id', 'owner_id');
    }

}
