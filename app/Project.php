<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * @package App
 * @property integer $id
 * @property $name
 * @property string $status
 * @property integer $user_id
 */

class Project extends Model
{

    const STATUS_ACTIVE = 'active';
    const STATUS_PASSIVE = 'passive';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
