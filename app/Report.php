<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 * @package App
 * @property integer $id
 * @property integer $duration
 * @property integer $task_id
 */

class Report extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

}
