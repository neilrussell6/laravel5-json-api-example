<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE   = 2;
    const STATUS_TRASH      = 3;
    
    protected $fillable     = ['name', 'status', 'type', 'priority'];
    protected $hidden       = [];

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
