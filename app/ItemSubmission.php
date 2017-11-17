<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSubmission extends Model
{
    public function task()
    {
        return $this->belongsToMany('App\Task');
    }

    public function item()
    {
        return $this->belongsToMany('App\Item');
    }
}
