<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Task
 * @package App
 * @property integer $id
 * @property $name
 * @property integer $project_id
 * @property integer $user_id
 */
class Task extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $fillable = ['project_id', 'name', 'user_id', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
