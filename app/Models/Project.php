<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE   = 2;
    const STATUS_TRASH      = 3;

    public $type            = 'projects';
    protected $fillable     = ['name', 'status'];
    protected $hidden       = [];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
