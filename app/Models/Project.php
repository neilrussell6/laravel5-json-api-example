<?php namespace App\Models;

use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Validatable;
    
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE   = 2;
    const STATUS_TRASH      = 3;

    protected $fillable     = ['name', 'status'];
    protected $hidden       = [];
    protected $casts        = [
        'status' => 'integer',
    ];

    public $type = 'projects';
    public $rules = [
        'name' => 'required'
    ];
    public $available_includes = ['users', 'tasks'];
    public $default_includes = ['tasks'];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
